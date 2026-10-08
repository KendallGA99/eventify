<?php
declare(strict_types=1);
use Eventify\Domain\BusinessError;
use Eventify\Domain\ReservationService;
$service = new ReservationService($pdo);
$users = $pdo->query('SELECT email, id FROM users')->fetchAll(PDO::FETCH_KEY_PAIR);
$events = $pdo->query('SELECT slug, id FROM events')->fetchAll(PDO::FETCH_KEY_PAIR);
$owner = (int) $users['otro@eventify.test'];
$another = (int) $users['usuario@eventify.test'];
$eventId = (int) $events['taller-sql'];
$rejectBusiness = static function (callable $operation, int $expected, string $name) use ($assert): void {
    $denied = false;
    try { $operation(); } catch (BusinessError $exception) { $denied = $exception->httpStatus === $expected; }
    $assert($denied, $name);
};
$rejectBusiness(fn () => $service->create($owner, $eventId, 29), 409, 'Rechazo de sobreventa');
$reservation = $service->create($owner, $eventId, 28);
$assert($reservation['available'] === 0, 'Reserva exacta de cupos restantes');
$query = $pdo->prepare('SELECT total FROM reservations WHERE id = ?');
$query->execute([$reservation['id']]);
$assert($query->fetchColumn() === '420000.00', 'Total autoritativo decimal');
$rejectBusiness(fn () => $service->create($owner, $eventId, 1), 409, 'Duplicado activo rechazado');
$rejectBusiness(fn () => $service->cancel($another, $reservation['id']), 404, 'Cancelación ajena rechazada');
$startsAt = new DateTimeImmutable((string) $pdo->query('SELECT starts_at FROM events WHERE id = ' . $eventId)->fetchColumn(), new DateTimeZone('UTC'));
$boundary = new ReservationService($pdo, static fn () => $startsAt->modify('-24 hours'));
$rejectBusiness(fn () => $boundary->cancel($owner, $reservation['id']), 409, 'Cancelación exactamente 24 horas rechazada');
$allowed = new ReservationService($pdo, static fn () => $startsAt->modify('-24 hours -1 second'));
$allowed->cancel($owner, $reservation['id']);
$assert($pdo->query('SELECT status FROM reservations WHERE id = ' . $reservation['id'])->fetchColumn() === 'CANCELADA', 'Cancelación más de 24 horas');
$service->cancel($owner, $reservation['id']);
$assert((int) $pdo->query("SELECT COALESCE(SUM(quantity), 0) FROM reservations WHERE event_id = $eventId AND status = 'CONFIRMADA'")->fetchColumn() === 2, 'Repetición no libera cupos dos veces');
$newReservation = $service->create($owner, $eventId, 1);
$assert($newReservation['id'] !== $reservation['id'], 'Nueva reserva conserva historial');
foreach (['carrera-borrador', 'cultura-cancelado', 'deporte-finalizado', 'cocina-agotado'] as $slug) {
    $rejectBusiness(fn () => $service->create($owner, (int) $events[$slug], 1), 409, 'Evento no reservable: ' . $slug);
}
// Fallo de almacenamiento solo en eventify_test: conservar tabla/datos y restaurar en finally.
// CREATE TRIGGER puede exigir SUPER con binlog; la aplicación no necesita ese privilegio.
if (Eventify\Support\Config::get('APP_ENV') !== 'test' || Eventify\Support\Config::get('DB_DATABASE') !== 'eventify_test') { throw new RuntimeException('Se requiere base aislada para provocar el fallo.'); }
$label = 'Preparación del fallo aislado de almacenamiento de ticket';
$before = (int) $pdo->query('SELECT COUNT(*) FROM reservations')->fetchColumn();
$beforeTickets = (int) $pdo->query('SELECT COUNT(*) FROM tickets')->fetchColumn();
$pdo->exec('RENAME TABLE tickets TO isolated_ticket_storage');
$failed = false;
try {
    $label = 'Reserva ante almacenamiento de ticket no disponible';
    try { $service->create($owner, (int) $events['encuentro-tecnologia'], 1); }
    catch (PDOException $exception) { $failed = (int) ($exception->errorInfo[1] ?? 0) === 1146; }
} finally {
    $label = 'Restauración del almacenamiento aislado de ticket';
    $pdo->exec('RENAME TABLE isolated_ticket_storage TO tickets');
}
$assert($failed && !$pdo->inTransaction() && (int) $pdo->query('SELECT COUNT(*) FROM reservations')->fetchColumn() === $before && (int) $pdo->query('SELECT COUNT(*) FROM tickets')->fetchColumn() === $beforeTickets, 'Fallo de ticket revierte reserva y conserva tickets existentes');

// Dos conexiones/processos reales compiten tras una barrera de inicio común.
$race = static function (int $eventId, array $userIds, string $description) use ($pdo, $assert): void {
    $query = $pdo->prepare("SELECT COALESCE(SUM(quantity), 0) FROM reservations WHERE event_id = ? AND status = 'CONFIRMADA'");
    $query->execute([$eventId]);
    $initialQuantity = (int) $query->fetchColumn();
    $directory = sys_get_temp_dir() . '/eventify-race-' . bin2hex(random_bytes(8));
    mkdir($directory, 0700);
    $processes = [];
    $channels = [];
    try {
        foreach ($userIds as $index => $userId) {
            $process = proc_open([PHP_BINARY, __DIR__ . '/reservation-worker.php', (string) $userId, (string) $eventId, $directory . '/ready-' . $index, $directory . '/go'], [['file', '/dev/null', 'r'], ['pipe', 'w'], ['file', '/dev/null', 'a']], $pipes, PROJECT_ROOT);
            if (!is_resource($process)) { throw new RuntimeException('No fue posible iniciar prueba de carrera.'); }
            $processes[] = $process;
            $channels[] = $pipes[1];
        }
        $deadline = microtime(true) + 10;
        while (!is_file($directory . '/ready-0') || !is_file($directory . '/ready-1')) {
            if (microtime(true) > $deadline) { throw new RuntimeException('Barrera de prueba vencida.'); }
            usleep(10000);
        }
        touch($directory . '/go');
        $results = [];
        foreach ($channels as $channel) {
            stream_set_timeout($channel, 15);
            $results[] = stream_get_contents($channel);
            fclose($channel);
        }
        $channels = [];
        foreach ($processes as $process) {
            if (proc_close($process) !== 0) { throw new RuntimeException('Proceso de carrera falló.'); }
        }
        $processes = [];
        sort($results);
        $assert($results === ['CONFLICT', 'CREATED'], $description);
        $query = $pdo->prepare("SELECT COALESCE(SUM(quantity), 0) FROM reservations WHERE event_id = ? AND status = 'CONFIRMADA'");
        $query->execute([$eventId]);
        $assert((int) $query->fetchColumn() === $initialQuantity + 1, $description . ': una entrada adicional persistida');
    } finally {
        foreach ($channels as $channel) { if (is_resource($channel)) { fclose($channel); } }
        foreach ($processes as $process) { if (is_resource($process)) { proc_terminate($process); proc_close($process); } }
    }
};
$lastEvent = (int) $events['encuentro-tecnologia'];
$pdo->exec('UPDATE events SET capacity = 1 WHERE id = ' . $lastEvent);
$race($lastEvent, [$owner, $another], 'Carrera al último cupo');
$duplicateEvent = (int) $events['taller-proximo'];
$race($duplicateEvent, [$owner, $owner], 'Carrera de duplicado del mismo usuario');
