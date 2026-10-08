<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/bootstrap.php';

use Eventify\Infrastructure\Database;
use Eventify\Infrastructure\DemoSeeder;
use Eventify\Infrastructure\MigrationRunner;
use Eventify\Support\Config;
use Eventify\Security\Auth;
use Eventify\Security\Session;

if (Config::get('APP_ENV') !== 'test' || Config::get('DB_DATABASE') !== 'eventify_test') {
    fwrite(STDERR, "Estas pruebas requieren la base aislada eventify_test.\n");
    exit(1);
}
$checks = 0;
$label = 'conexión';
$assert = static function (bool $condition, string $description) use (&$checks, &$label): void {
    $label = $description;
    if (!$condition) {
        throw new RuntimeException('Comprobación fallida.');
    }
    ++$checks;
};
try {
    Session::start();
    $pdo = Database::connect();
    $assert(str_starts_with((string) $pdo->query('SELECT VERSION()')->fetchColumn(), '8.4.'), 'MySQL objetivo 8.4');
    $runner = new MigrationRunner($pdo, PROJECT_ROOT . '/database/migrations');
    $assert($runner->run() === ['001_initial_schema', '002_auth_limits'], 'Primera migración');
    $assert($runner->run() === [], 'Repetición sin modificar esquema');
    require __DIR__ . '/admin-read.php';
    $credentialsPath = PROJECT_ROOT . '/storage/test-credentials.json';
    $seeder = new DemoSeeder($pdo, $credentialsPath);
    $assert($seeder->run(), 'Primera demostración');
    $snapshot = $pdo->query('SELECT id, starts_at FROM events ORDER BY id')->fetchAll();
    $credentialsHash = hash_file('sha256', $credentialsPath);
    $assert(!$seeder->run(), 'Demostración repetida no duplica');
    $assert(hash_file('sha256', $credentialsPath) === $credentialsHash, 'Credenciales conservadas');
    $assert($pdo->query('SELECT id, starts_at FROM events ORDER BY id')->fetchAll() === $snapshot, 'Fechas y registros conservados');
    foreach (['users' => 3, 'categories' => 4, 'events' => 8, 'reservations' => 7, 'tickets' => 7] as $table => $count) {
        $assert((int) $pdo->query('SELECT COUNT(*) FROM ' . $table)->fetchColumn() === $count, 'Cantidad de ' . $table);
    }
    $credentials = json_decode((string) file_get_contents($credentialsPath), true, 512, JSON_THROW_ON_ERROR);
    foreach ($credentials as $account) {
        $query = $pdo->prepare('SELECT password_hash FROM users WHERE email = ?');
        $query->execute([$account['email']]);
        $assert(password_verify($account['password'], (string) $query->fetchColumn()), 'Hash de cuenta demo');
    }
    $availability = $pdo->query("SELECT e.slug, e.capacity - COALESCE(SUM(CASE WHEN r.status = 'CONFIRMADA' THEN r.quantity ELSE 0 END), 0) AS available FROM events e LEFT JOIN reservations r ON r.event_id = e.id GROUP BY e.id")->fetchAll(PDO::FETCH_KEY_PAIR);
    $assert((int) $availability['musica-ultimos-cupos'] === 2, 'Últimos espacios');
    $assert((int) $availability['cocina-agotado'] === 0, 'Evento agotado');
    $assert((int) $availability['taller-sql'] === 28, 'Canceladas excluidas de ocupación');

    // Cada intento inválido se revierte en la base efímera, incluso si el control falla.
    $reject = static function (string $sql, string $description) use ($pdo, $assert, &$label): void {
        $label = $description;
        $pdo->beginTransaction();
        $rejected = false;
        try {
            $pdo->exec($sql);
        } catch (PDOException $exception) {
            $rejected = in_array((int) ($exception->errorInfo[1] ?? 0), [1062, 1451, 1452, 3819], true);
        } finally {
            $pdo->rollBack();
        }
        $assert($rejected, $description);
    };
    $reject("UPDATE users SET email = 'USUARIO@EVENTIFY.TEST' WHERE email = 'otro@eventify.test'", 'Correo duplicado sin distinguir mayúsculas');
    $reject('UPDATE events SET capacity = 0 LIMIT 1', 'Capacidad inválida');
    $reject('UPDATE events SET price = -1 LIMIT 1', 'Precio negativo');
    $reject('UPDATE events SET category_id = 999999 LIMIT 1', 'Categoría inexistente');
    $reject("DELETE FROM categories WHERE slug = 'tecnologia'", 'Categoría referenciada protegida');
    $reject('UPDATE reservations SET quantity = 0, total = 0 LIMIT 1', 'Cantidad cero');
    $reject('UPDATE reservations SET total = total + 1 LIMIT 1', 'Total inconsistente');
    $reject("UPDATE reservations SET status = 'CANCELADA', cancelled_at = NULL WHERE status = 'CONFIRMADA' LIMIT 1", 'Cancelación sin fecha');
    $copy = "INSERT INTO reservations (user_id, event_id, code, quantity, unit_price, total, status) SELECT user_id, event_id, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', quantity, unit_price, total, 'CONFIRMADA' FROM reservations WHERE code = (SELECT code FROM (SELECT code FROM reservations WHERE status = 'CONFIRMADA' ORDER BY id LIMIT 1) sample)";
    $reject($copy, 'Reserva activa duplicada');
    $pdo->beginTransaction();
    try {
        $id = (int) $pdo->query("SELECT id FROM reservations WHERE status = 'CONFIRMADA' ORDER BY id LIMIT 1")->fetchColumn();
        $pdo->exec("UPDATE reservations SET status = 'CANCELADA', cancelled_at = UTC_TIMESTAMP() WHERE id = " . $id);
        $pdo->exec("INSERT INTO reservations (user_id, event_id, code, quantity, unit_price, total, status) SELECT user_id, event_id, 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb', quantity, unit_price, total, 'CONFIRMADA' FROM reservations WHERE id = " . $id);
        $assert(true, 'Nueva reserva después de cancelación permitida');
    } finally {
        $pdo->rollBack();
    }
    $reject("INSERT INTO tickets (reservation_id, code) SELECT reservation_id, REPEAT('c', 64) FROM tickets LIMIT 1", 'Un ticket por reserva');
    $reject('DELETE FROM reservations WHERE id IN (SELECT reservation_id FROM tickets)', 'Historial con ticket protegido');
    putenv('APP_ENV=production');
    $denied = false;
    try {
        $seeder->run();
    } catch (RuntimeException $exception) {
        $denied = true;
    } finally {
        putenv('APP_ENV=test');
    }
    $assert($denied, 'Demostración bloqueada en producción');

    $auth = new Auth($pdo);
    $input = ['full_name' => 'Persona de integración', 'email' => 'auth@eventify.test', 'password' => 'Prueba-segura-2026', 'password_confirmation' => 'Prueba-segura-2026', 'role' => 'ADMINISTRADOR', 'role_id' => 1];
    $assert($auth->register($input) === [], 'Registro válido');
    $assert($auth->register($input) !== [], 'Registro duplicado rechazado');
    $assert(!$auth->authenticate($input['email'], 'incorrecta'), 'Ingreso incorrecto rechazado');
    $oldId = session_id();
    $oldToken = Session::token();
    $assert($auth->authenticate($input['email'], $input['password']), 'Ingreso válido');
    $assert(session_id() !== $oldId && Session::token() !== $oldToken, 'Identificador y CSRF renovados');
    $assert($auth->user()['role'] === 'USUARIO', 'Rol enviado ignorado');
    $assert(!Session::validToken($oldToken), 'CSRF anterior rechazado');
    $assert($auth->authenticate($credentials[0]['email'], $credentials[0]['password']) && $auth->user()['role'] === 'ADMINISTRADOR', 'Ingreso de administrador demo');
    $query = $pdo->prepare('UPDATE users SET is_active = 0 WHERE id = ?');
    $query->execute([$auth->user()['id']]);
    $assert($auth->user() === null, 'Desactivación revoca autorización');
    Session::logout();
    $assert($auth->user() === null && session_status() !== PHP_SESSION_ACTIVE, 'Logout destruye sesión');
    for ($attempt = 0; $attempt < 10; ++$attempt) {
        $assert($auth->allowAttempt('integration', '127.0.0.1'), 'Intento dentro del límite');
    }
    $assert(!$auth->allowAttempt('integration', '127.0.0.1'), 'Límite persistente aplicado');
    // Reactivar la cuenta demo que esta suite desactivó para probar revocación.
    $pdo->exec("UPDATE users SET is_active = 1 WHERE email = 'admin@eventify.test'");
    require __DIR__ . '/catalog.php';
    require __DIR__ . '/http-auth.php';

    // Simular archivos alterados y DDL incompleto únicamente al final, en el stack efímero.
    require __DIR__ . '/reservations.php';
    require __DIR__ . '/admin.php';
    $temporary = sys_get_temp_dir() . '/eventify-migrations-' . bin2hex(random_bytes(8));
    mkdir($temporary, 0700);
    $original = PROJECT_ROOT . '/database/migrations/001_initial_schema.php';
    $copyPath = $temporary . '/001_initial_schema.php';
    copy($original, $copyPath);
    copy(PROJECT_ROOT . '/database/migrations/002_auth_limits.php', $temporary . '/002_auth_limits.php');
    file_put_contents($copyPath, "\n// Alteración de prueba\n", FILE_APPEND);
    $denied = false;
    try {
        (new MigrationRunner($pdo, $temporary))->run();
    } catch (RuntimeException $exception) {
        $denied = str_contains($exception->getMessage(), 'cambió');
    }
    $assert($denied, 'Historial alterado rechazado');
    copy($original, $copyPath);
    file_put_contents($temporary . '/003_failure.php', "<?php return ['CREATE TABLE isolated_probe (id INT PRIMARY KEY)', 'SQL_INVALIDO_DE_PRUEBA'];");
    $denied = false;
    try {
        (new MigrationRunner($pdo, $temporary))->run();
    } catch (RuntimeException $exception) {
        $denied = true;
    }
    $assert($denied, 'Migración incompleta detectada');
    $assert((int) $pdo->query("SELECT statements_applied FROM schema_migrations WHERE version = '003_failure' AND status = 'EN_PROCESO'")->fetchColumn() === 1, 'Progreso DDL conservado');
    $denied = false;
    try {
        (new MigrationRunner($pdo, $temporary))->run();
    } catch (RuntimeException $exception) {
        $denied = str_contains($exception->getMessage(), 'incompleta');
    }
    $assert($denied, 'Reintento inseguro bloqueado');
    echo 'MySQL: ' . $checks . " comprobaciones correctas.\n";
} catch (Throwable $exception) {
    fwrite(STDERR, 'Falló la comprobación: ' . $label . ' (' . get_class($exception) . "). No se muestran credenciales ni consultas.\n");
    exit(1);
}
