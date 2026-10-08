<?php
declare(strict_types=1);
require dirname(__DIR__, 2) . '/app/bootstrap.php';
use Eventify\Infrastructure\Database;
use Eventify\Infrastructure\EvaluationProvisioner;
use Eventify\Support\Config;
try {
    if (Config::get('APP_ENV') !== 'production' || Config::get('DB_DATABASE') !== 'eventify_tls_test' || $argc !== 2) { throw new RuntimeException(); }
    $accounts = json_decode((string) file_get_contents($argv[1]), true, 32, JSON_THROW_ON_ERROR);
    $pdo = Database::connect();
    $snapshot = static fn (): array => $pdo->query('SELECT u.email,u.password_hash,u.is_active,r.code FROM users u JOIN roles r ON r.id=u.role_id ORDER BY u.email')->fetchAll();
    $saved = $snapshot();
    if (count($saved) !== 3) { throw new RuntimeException(); }
    foreach ($accounts as $account) {
        $found = array_values(array_filter($saved, static fn ($row): bool => $row['email'] === $account['email']));
        if (count($found) !== 1 || $found[0]['code'] !== $account['role'] || (int) $found[0]['is_active'] !== 1
            || !password_verify($account['password'], $found[0]['password_hash']) || $account['password'] === $found[0]['password_hash']) { throw new RuntimeException(); }
    }
    $provisioner = new EvaluationProvisioner($pdo);
    $denied = false;
    try { $provisioner->run($accounts); } catch (RuntimeException) { $denied = true; }
    if (!$denied || $snapshot() !== $saved || $pdo->inTransaction()) { throw new RuntimeException(); }
    putenv('APP_ENV=local');
    $denied = false;
    try { $provisioner->run($accounts); } catch (RuntimeException) { $denied = true; }
    finally { putenv('APP_ENV=production'); }
    if (!$denied || $snapshot() !== $saved) { throw new RuntimeException(); }
    $invalid = $accounts;
    $invalid[0]['email'] = 'no-autorizado@eventify.test';
    unset($invalid[0]['role']);
    $denied = false;
    try { $provisioner->run($invalid); } catch (RuntimeException) { $denied = true; }
    if (!$denied || $snapshot() !== $saved) { throw new RuntimeException(); }
    echo "Cuentas de evaluación: roles/hashes correctos; repetición, entorno local y plan inválido rechazados sin cambios.\n";
} catch (Throwable) {
    fwrite(STDERR, "Ensayo de cuentas falló; detalles privados omitidos.\n");
    exit(1);
}
