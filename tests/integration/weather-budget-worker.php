<?php
declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/bootstrap.php';

use Eventify\Infrastructure\Database;
use Eventify\Infrastructure\WeatherBudget;
use Eventify\Support\Config;

try {
    if (Config::get('APP_ENV') !== 'test' || Config::get('DB_DATABASE') !== 'eventify_test') {
        throw new RuntimeException('Base aislada requerida.');
    }
    $directory = $argv[1] ?? '';
    $index = $argv[2] ?? '';
    $time = $argv[3] ?? '';
    if (!is_dir($directory) || !in_array($index, ['0', '1'], true) || !ctype_digit($time)) {
        throw new RuntimeException('Trabajo inválido.');
    }
    $pdo = Database::connect();
    touch($directory . '/ready-' . $index);
    $deadline = microtime(true) + 10;
    while (!is_file($directory . '/go')) {
        if (microtime(true) > $deadline) { throw new RuntimeException('Barrera vencida.'); }
        usleep(10000);
    }
    touch($directory . '/attempt-' . $index);
    echo (new WeatherBudget($pdo))->allowRequest((int) $time) ? 'ALLOW' : 'DENY';
} catch (Throwable) {
    fwrite(STDERR, "Proceso de presupuesto falló; detalles privados omitidos.\n");
    exit(1);
}
