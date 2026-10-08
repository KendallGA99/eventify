<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';

use Eventify\Infrastructure\Database;
use Eventify\Support\Config;

try {
    $database = Config::get('DB_DATABASE');
    $mode = $argv[1] ?? '';
    if ($argc !== 2 || Config::get('APP_ENV') !== 'local'
        || !in_array($database, ['eventify_qa', 'eventify_restore'], true)
        || !in_array($mode, ['snapshot', 'empty'], true)
        || ($mode === 'empty' && $database !== 'eventify_restore')) {
        throw new RuntimeException('Destino de ensayo inválido.');
    }
    $pdo = Database::connect();
    if ($pdo->query('SELECT DATABASE()')->fetchColumn() !== $database) {
        throw new RuntimeException('Base inesperada.');
    }
    if ($mode === 'empty') {
        if ($pdo->query('SHOW TABLES')->fetchAll() !== []) {
            throw new RuntimeException('La restauración requiere una base vacía.');
        }
        echo "Destino de ensayo vacío verificado.\n";
        exit(0);
    }
    $keys = ['roles'=>'id', 'users'=>'id', 'categories'=>'id', 'events'=>'id',
        'reservations'=>'id', 'tickets'=>'id', 'schema_migrations'=>'version',
        'demo_seed_runs'=>'seed_key', 'auth_limits'=>'bucket', 'weather_budget'=>'scope'];
    $actual = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    $expected = array_keys($keys);
    sort($actual); sort($expected);
    if ($actual !== $expected) { throw new RuntimeException('Esquema de ensayo inesperado.'); }
    $snapshot = ['tables'=>[], 'files'=>[]];
    $pdo->beginTransaction();
    try {
        foreach ($keys as $table => $key) {
            $rows = $pdo->query('SELECT * FROM ' . $table . ' ORDER BY ' . $key)->fetchAll();
            $snapshot['tables'][$table] = ['count'=>count($rows),
                'sha256'=>hash('sha256', json_encode($rows, JSON_THROW_ON_ERROR))];
        }
    } finally { $pdo->rollBack(); }
    foreach (['demo-credentials.json', 'qa-persistence-marker.bin'] as $name) {
        $file = PROJECT_ROOT . '/storage/' . $name;
        if (!is_file($file) || is_link($file) || ($hash = hash_file('sha256', $file)) === false) {
            throw new RuntimeException('Archivo persistente inválido.');
        }
        $snapshot['files'][$name] = $hash;
    }
    $images = glob(PROJECT_ROOT . '/public/assets/images/*.svg') ?: [];
    if (count($images) < 3) { throw new RuntimeException('Galería incompleta.'); }
    foreach ($images as $file) {
        if (($hash = hash_file('sha256', $file)) === false) { throw new RuntimeException('Imagen ilegible.'); }
        $snapshot['files']['image:' . basename($file)] = $hash;
    }
    echo json_encode($snapshot, JSON_THROW_ON_ERROR) . "\n";
} catch (Throwable $error) {
    fwrite(STDERR, 'Ensayo de transferencia falló (' . get_class($error)
        . "). No se muestran consultas, registros ni credenciales.\n");
    exit(1);
}
