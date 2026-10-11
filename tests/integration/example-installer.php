<?php

declare(strict_types=1);

use Eventify\Infrastructure\ExampleInstaller;

$examplePath = PROJECT_ROOT . '/storage/example-test-credentials.json';
$examples = new ExampleInstaller($pdo, $examplePath);
$assert($examples->run(), 'Catálogo inicial en base vacía');
$assert((int) $pdo->query("SELECT COUNT(*) FROM events WHERE status='PUBLICADO' AND starts_at > UTC_TIMESTAMP()")->fetchColumn() === 8, 'Ocho eventos futuros publicados');
$assert((int) $pdo->query('SELECT COUNT(*) FROM reservations')->fetchColumn() === 0, 'Catálogo inicial sin reservas inventadas');
$assert((int) $pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn() === 5, 'Cinco categorías de ejemplo');
$access = json_decode((string) file_get_contents($examplePath), true, 512, JSON_THROW_ON_ERROR);
$assert(count($access) === 2, 'Administrador y visitante iniciales');
foreach ($access as $account) {
    $q = $pdo->prepare('SELECT password_hash, r.code FROM users u JOIN roles r ON r.id=u.role_id WHERE email=?');
    $q->execute([$account['email']]);
    $row = $q->fetch();
    $assert(password_verify($account['password'], $row['password_hash']) && $row['code'] === $account['role'], 'Clave con hash y rol correcto');
}
$assert($access[0]['password'] !== $access[1]['password'], 'Claves independientes');
$snapshot = $pdo->query('SELECT * FROM events ORDER BY id')->fetchAll();
$hash = hash_file('sha256', $examplePath);
$assert(!$examples->run(), 'Arranque repetido conserva catálogo');
$assert($snapshot === $pdo->query('SELECT * FROM events ORDER BY id')->fetchAll(), 'Fechas y contenido conservados');
$assert($hash === hash_file('sha256', $examplePath), 'Claves conservadas en reinicio');
foreach ($snapshot as $event) {
    $assert(is_file(PROJECT_ROOT . '/public' . $event['image_path']), 'Portada local presente');
}
$pdo->exec("DELETE FROM demo_seed_runs WHERE seed_key='catalogo_entrega_v1'");
$assert(!(new ExampleInstaller($pdo, PROJECT_ROOT . '/storage/unexpected-credentials.json'))->run(), 'No alterar una instalación utilizada');
$assert(!file_exists(PROJECT_ROOT . '/storage/unexpected-credentials.json'), 'No crear claves en instalación existente');

// Esta base efímera permite verificar el retiro público sin tocar datos reales.
$pdo->exec("UPDATE events SET slug=CONCAT('anterior-',slug)");
$pdo->exec("UPDATE users SET email=CONCAT('anterior-',email)");
$pdo->exec("INSERT INTO reservations (user_id,event_id,code,quantity,unit_price,total,status) SELECT (SELECT MIN(id) FROM users),id,REPEAT('7',32),1,price,price,'CONFIRMADA' FROM events ORDER BY id LIMIT 1");
$pdo->exec("INSERT INTO tickets (reservation_id,code) SELECT id,REPEAT('8',64) FROM reservations LIMIT 1");
$history = $pdo->query('SELECT * FROM reservations')->fetchAll();
$ticketHistory = $pdo->query('SELECT * FROM tickets')->fetchAll();
putenv('APP_ENV=production');
$denied = false;
try {
    $examples->run();
} catch (RuntimeException) {
    $denied = true;
}
$assert($denied, 'Arranque público no crea cuentas automáticamente');
$publicPath = PROJECT_ROOT . '/storage/example-public-test-credentials.json';
$publicExamples = new ExampleInstaller($pdo, $publicPath);
$assert($publicExamples->run(true), 'Preparación pública explícita');
$assert((int) $pdo->query("SELECT COUNT(*) FROM events WHERE status='PUBLICADO'")->fetchColumn() === 8, 'Catálogo público limpio con ocho eventos');
$assert((int) $pdo->query("SELECT COUNT(*) FROM events WHERE status='CANCELADO'")->fetchColumn() === 1, 'Evento anterior con historial retirado sin borrar');
$assert((int) $pdo->query("SELECT COUNT(*) FROM events WHERE status='BORRADOR'")->fetchColumn() === 7, 'Eventos anteriores sin historial archivados');
$assert($history === $pdo->query('SELECT * FROM reservations')->fetchAll(), 'Reservas y precios históricos conservados');
$assert($ticketHistory === $pdo->query('SELECT * FROM tickets')->fetchAll(), 'Tickets anteriores conservados');
$publicHash = hash_file('sha256', $publicPath);
$assert(!$publicExamples->run(true), 'Preparación pública repetida no duplica');
$assert(hash_file('sha256', $publicPath) === $publicHash, 'Clave pública conservada');
$publicAccess = json_decode((string) file_get_contents($publicPath), true, 512, JSON_THROW_ON_ERROR);
$assert($publicAccess[0]['password'] !== $access[0]['password'], 'Claves distintas entre instalaciones');
putenv('APP_ENV=test');
foreach (['tickets', 'reservations', 'events', 'categories', 'users', 'demo_seed_runs'] as $table) {
    $pdo->exec('DELETE FROM ' . $table);
}
unlink($examplePath);
unlink($publicPath);
$failed = false;
try {
    (new ExampleInstaller($pdo, '/directorio-inexistente/credentials.json'))->run();
} catch (RuntimeException) {
    $failed = true;
}
$assert($failed, 'Almacenamiento privado inválido rechazado');
$assert((int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() === 0, 'Fallo de almacenamiento revierte cuentas');
$assert((int) $pdo->query('SELECT COUNT(*) FROM events')->fetchColumn() === 0, 'Fallo de almacenamiento revierte eventos');
