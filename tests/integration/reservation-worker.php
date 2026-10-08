<?php
declare(strict_types=1);
require dirname(__DIR__, 2) . '/app/bootstrap.php';
use Eventify\Domain\BusinessError;
use Eventify\Domain\ReservationService;
use Eventify\Infrastructure\Database;
use Eventify\Support\Config;
if (Config::get('APP_ENV') !== 'test' || Config::get('DB_DATABASE') !== 'eventify_test' || $argc !== 5) { exit(1); }
try {
    $pdo = Database::connect();
    file_put_contents($argv[3], 'ready');
    $deadline = microtime(true) + 10;
    while (!is_file($argv[4])) {
        if (microtime(true) > $deadline) { exit(1); }
        usleep(10000);
    }
    (new ReservationService($pdo))->create((int) $argv[1], (int) $argv[2], 1);
    echo 'CREATED';
} catch (BusinessError $exception) {
    echo $exception->httpStatus === 409 ? 'CONFLICT' : 'ERROR';
} catch (Throwable $exception) {
    echo 'ERROR';
    exit(1);
}
