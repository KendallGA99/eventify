<?php
declare(strict_types=1);
namespace Eventify\Http;

use Eventify\Domain\BusinessError;
use Eventify\Domain\ReservationService;
use Eventify\Infrastructure\Database;
use Eventify\Security\Auth;
use Eventify\Security\Session;

final class ReservationController
{
    public function api(?int $reservationId): void
    {
        header('Content-Type: application/json; charset=utf-8');
        Session::start();
        try {
            if (!Session::validToken($_POST['csrf'] ?? null)) { throw new BusinessError('Actualice la página antes de intentarlo nuevamente.', 403); }
            $pdo = Database::connect();
            $user = (new Auth($pdo))->user();
            if ($user === null) { throw new BusinessError('Debe iniciar sesión.', 401); }
            session_write_close();
            $service = new ReservationService($pdo);
            if ($reservationId !== null) {
                $service->cancel((int) $user['id'], $reservationId);
                echo json_encode(['message' => 'Reserva cancelada.'], JSON_THROW_ON_ERROR);
            } else {
                $eventId = ReservationService::quantity($_POST['event_id'] ?? null);
                $result = $service->create((int) $user['id'], $eventId, $_POST['quantity'] ?? null);
                http_response_code(201);
                echo json_encode(['message' => 'Reserva confirmada.', ...$result], JSON_THROW_ON_ERROR);
            }
        } catch (BusinessError $exception) {
            http_response_code($exception->httpStatus);
            echo json_encode(['message' => $exception->getMessage()], JSON_THROW_ON_ERROR);
        }
    }

    public function page(string $path): void
    {
        Session::start();
        $pdo = Database::connect();
        $user = (new Auth($pdo))->user();
        $page = 'catalog';
        $rows = [];
        $event = null;
        $ticket = null;
        if ($path === '/experiencias' || str_starts_with($path, '/tickets/')) {
            if ($user === null) { header('Location: /ingresar', true, 303); return; }
            $page = $path === '/experiencias' ? 'reservations' : 'ticket';
            $sql = 'SELECT r.id, r.code AS reservation_code, r.quantity, r.total, r.status, e.name, e.starts_at, e.location, e.status AS event_status, t.code AS ticket_code FROM reservations r JOIN events e ON e.id = r.event_id JOIN tickets t ON t.reservation_id = r.id WHERE r.user_id = ?';
            $params = [$user['id']];
            $qr = str_ends_with($path, '/qr.svg');
            if ($page === 'ticket') { $sql .= ' AND t.code = ?'; $params[] = substr($qr ? substr($path, 0, -7) : $path, 9); }
            $query = $pdo->prepare($sql . ' ORDER BY r.created_at DESC, r.id DESC');
            $query->execute($params);
            $rows = $query->fetchAll();
            if ($page === 'ticket') {
                $ticket = $rows[0] ?? null;
                if ($ticket === null) { $this->missing(); return; }
                if ($qr) {
                    header('Content-Type: image/svg+xml; charset=utf-8');
                    header("Content-Security-Policy: default-src 'none'; sandbox");
                    echo \Eventify\Support\TicketQr::svg($ticket['ticket_code']);
                    return;
                }
            }
        } else {
            $sql = "SELECT e.*, c.name AS category, e.capacity - COALESCE((SELECT SUM(r.quantity) FROM reservations r WHERE r.event_id = e.id AND r.status = 'CONFIRMADA'), 0) AS available FROM events e JOIN categories c ON c.id = e.category_id WHERE e.status = 'PUBLICADO' AND e.starts_at > UTC_TIMESTAMP()";
            $params = [];
            if ($path !== '/eventos') { $sql .= ' AND e.slug = ?'; $params[] = substr($path, 9); $page = 'event'; }
            $query = $pdo->prepare($sql . ' ORDER BY e.starts_at, e.id');
            $query->execute($params);
            $rows = $query->fetchAll();
            if ($page === 'event') { $event = $rows[0] ?? null; if ($event === null) { $this->missing(); return; } }
        }
        require PROJECT_ROOT . '/app/Views/experiences.php';
    }

    private function missing(): void
    {
        http_response_code(404);
        require PROJECT_ROOT . '/app/Views/not-found.php';
    }
}
