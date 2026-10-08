<?php
declare(strict_types=1);
require dirname(__DIR__, 2) . '/app/bootstrap.php';
use Eventify\Domain\AdminService;
use Eventify\Domain\BusinessError;
use Eventify\Domain\ReservationService;
use Eventify\Infrastructure\Database;
use Eventify\Support\Config;

// Instrumentación exclusiva de prueba: pausar DESPUÉS del lock real del servicio.
final class RaceStatement extends PDOStatement
{
    protected function __construct(private Closure $barrier) {}
    public function execute(?array $params = null): bool
    {
        $eventLock = str_contains($this->queryString, 'FROM events WHERE id = ? FOR UPDATE');
        if ($eventLock) { ($this->barrier)('attempt'); }
        $result = parent::execute($params);
        if ($eventLock) { ($this->barrier)('locked'); }
        return $result;
    }
}
if (Config::get('APP_ENV') !== 'test' || Config::get('DB_DATABASE') !== 'eventify_test' || $argc !== 3 || !in_array($argv[2], ['0','1'], true)) { exit(1); }
try {
    $job = json_decode((string) file_get_contents($argv[1]), true, 512, JSON_THROW_ON_ERROR);
    $index = (int)$argv[2]; $dir = dirname($argv[1]); $operation = $job['operations'][$index];
    $pdo = Database::connect();
    $pdo->exec('SET SESSION innodb_lock_wait_timeout = 10');
    $barrier = static function (string $phase) use ($dir, $index): void {
        if ($phase === 'attempt') { if (file_put_contents($dir . '/attempt-' . $index, 'attempt') === false) { throw new RuntimeException('Barrera no disponible.'); } }
        elseif ($index === 0) {
            if (file_put_contents($dir . '/locked', 'locked') === false) { throw new RuntimeException('Barrera no disponible.'); }
            $deadline = microtime(true) + 20;
            while (!is_file($dir . '/release')) { if (microtime(true) > $deadline) { throw new RuntimeException('Barrera vencida.'); } usleep(10000); }
        }
    };
    $pdo->setAttribute(PDO::ATTR_STATEMENT_CLASS, [RaceStatement::class, [$barrier]]);
    if ($operation === 'reserve') { (new ReservationService($pdo))->create($job['user_id'], $job['event_id'], $job['quantity']); }
    elseif ($operation === 'capacity') { (new AdminService($pdo))->event($job['event_id'], [...$job['input'], 'capacity' => '1']); }
    elseif ($operation === 'cancel') { (new AdminService($pdo))->state($job['event_id'], 'CANCELADO'); }
    else { throw new RuntimeException('Operación de prueba inválida.'); }
    echo 'OK';
} catch (BusinessError $exception) {
    if ($exception->httpStatus !== 409) { fwrite(STDERR, 'Conflicto inesperado.'); exit(1); }
    echo 'CONFLICT';
} catch (Throwable $exception) {
    fwrite(STDERR, 'Proceso de carrera falló: ' . get_class($exception) . ($exception instanceof PDOException ? ' MySQL=' . (int)($exception->errorInfo[1] ?? 0) : ''));
    exit(1);
}
