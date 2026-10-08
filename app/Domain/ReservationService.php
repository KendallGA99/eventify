<?php
declare(strict_types=1);
namespace Eventify\Domain;

use Closure;
use DateTimeImmutable;
use DateTimeZone;
use PDO;
use PDOException;
use Throwable;

final class ReservationService
{
    public function __construct(private PDO $pdo, private ?Closure $clock = null) {}

    public static function quantity(mixed $value): int
    {
        if ((!is_string($value) && !is_int($value)) || !preg_match('/\A[1-9][0-9]{0,9}\z/', (string) $value) || (int) $value > 4294967295) {
            throw new BusinessError('Seleccione una cantidad entera positiva de entradas.');
        }
        return (int) $value;
    }

    public function create(int $userId, int $eventId, mixed $quantity): array
    {
        $quantity = self::quantity($quantity);
        $this->pdo->beginTransaction();
        try {
            $event = $this->lockEvent($eventId);
            if (!ReservationPolicy::canReserve($event['status'], new DateTimeImmutable($event['starts_at'], new DateTimeZone('UTC')), $this->now())) {
                throw new BusinessError('Este evento no admite nuevas reservas.', 409);
            }
            $query = $this->pdo->prepare("SELECT id FROM reservations WHERE user_id = ? AND event_id = ? AND status = 'CONFIRMADA'");
            $query->execute([$userId, $eventId]);
            if ($query->fetchColumn() !== false) { throw new BusinessError('Ya tiene una reserva activa para este evento.', 409); }
            $query = $this->pdo->prepare("SELECT COALESCE(SUM(quantity), 0) FROM reservations WHERE event_id = ? AND status = 'CONFIRMADA'");
            $query->execute([$eventId]);
            $available = ReservationPolicy::available((int) $event['capacity'], (int) $query->fetchColumn());
            if ($quantity > $available) { throw new BusinessError('No hay suficientes espacios disponibles.', 409); }
            // DECIMAL y precio del evento: no usar el precio/total recibido del navegador.
            $query = $this->pdo->prepare('INSERT INTO reservations (user_id, event_id, code, quantity, unit_price, total) SELECT ?, id, ?, ?, price, price * ? FROM events WHERE id = ?');
            $query->execute([$userId, bin2hex(random_bytes(16)), $quantity, $quantity, $eventId]);
            $reservationId = (int) $this->pdo->lastInsertId();
            $code = bin2hex(random_bytes(32));
            $query = $this->pdo->prepare('INSERT INTO tickets (reservation_id, code) VALUES (?, ?)');
            $query->execute([$reservationId, $code]);
            $this->pdo->commit();
            return ['id' => $reservationId, 'ticket_url' => '/tickets/' . $code, 'available' => $available - $quantity];
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) { $this->pdo->rollBack(); }
            if ($exception instanceof PDOException && (int) ($exception->errorInfo[1] ?? 0) === 1062) {
                throw new BusinessError('La reserva ya existe. Consulte sus experiencias.', 409);
            }
            throw $exception;
        }
    }

    public function cancel(int $userId, int $reservationId): void
    {
        $query = $this->pdo->prepare('SELECT event_id FROM reservations WHERE id = ? AND user_id = ?');
        $query->execute([$reservationId, $userId]);
        $eventId = $query->fetchColumn();
        if ($eventId === false) { throw new BusinessError('Reserva no encontrada.', 404); }
        $this->pdo->beginTransaction();
        try {
            // Siempre evento → reserva: mismo orden que al crear, evita invertir bloqueos.
            $event = $this->lockEvent((int) $eventId);
            $query = $this->pdo->prepare('SELECT status FROM reservations WHERE id = ? AND user_id = ? FOR UPDATE');
            $query->execute([$reservationId, $userId]);
            $status = $query->fetchColumn();
            if ($status === false) { throw new BusinessError('Reserva no encontrada.', 404); }
            if ($status === 'CANCELADA') { $this->pdo->commit(); return; }
            if (!ReservationPolicy::canCancel(new DateTimeImmutable($event['starts_at'], new DateTimeZone('UTC')), $this->now())) {
                throw new BusinessError('Solo puede cancelar si faltan más de 24 horas para el inicio.', 409);
            }
            $query = $this->pdo->prepare("UPDATE reservations SET status = 'CANCELADA', cancelled_at = UTC_TIMESTAMP() WHERE id = ? AND user_id = ? AND status = 'CONFIRMADA'");
            $query->execute([$reservationId, $userId]);
            $this->pdo->commit();
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) { $this->pdo->rollBack(); }
            throw $exception;
        }
    }

    private function lockEvent(int $eventId): array
    {
        $query = $this->pdo->prepare('SELECT id, starts_at, capacity, status FROM events WHERE id = ? FOR UPDATE');
        $query->execute([$eventId]);
        $event = $query->fetch();
        if ($event === false) { throw new BusinessError('Evento no encontrado.', 404); }
        return $event;
    }

    private function now(): DateTimeImmutable
    {
        return $this->clock !== null ? ($this->clock)() : new DateTimeImmutable((string) $this->pdo->query('SELECT UTC_TIMESTAMP()')->fetchColumn(), new DateTimeZone('UTC'));
    }
}
