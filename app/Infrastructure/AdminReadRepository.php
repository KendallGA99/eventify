<?php
declare(strict_types=1);
namespace Eventify\Infrastructure;

use Eventify\Domain\AdminReservationFilter;
use Eventify\Domain\DashboardMetrics;
use PDO;

final class AdminReadRepository
{
    public function __construct(private readonly PDO $pdo) {}

    public function dashboard(): array
    {
        // Un SELECT: indicadores y rankings comparten snapshot e instante MySQL.
        $sql = <<<'SQL'
WITH active_events AS (
    SELECT e.id, e.name, e.category_id, e.capacity,
        COUNT(r.id) AS reservations, COALESCE(SUM(r.quantity), 0) AS entries,
        COALESCE(SUM(r.total), 0) AS potential
    FROM events e LEFT JOIN reservations r ON r.event_id = e.id AND r.status = 'CONFIRMADA'
    WHERE e.status = 'PUBLICADO' AND e.starts_at > UTC_TIMESTAMP()
    GROUP BY e.id, e.name, e.category_id, e.capacity
), category_stats AS (
    SELECT category_id, SUM(entries) AS entries FROM active_events GROUP BY category_id
), top_event AS (
    SELECT id, name, entries FROM active_events WHERE entries > 0 ORDER BY entries DESC, id LIMIT 1
), top_category AS (
    SELECT a.category_id, c.name, a.entries FROM category_stats a JOIN categories c ON c.id = a.category_id
    WHERE a.entries > 0 ORDER BY a.entries DESC, a.category_id LIMIT 1
)
SELECT UTC_TIMESTAMP() AS as_of,
    (SELECT COUNT(*) FROM events) AS total_events,
    COUNT(*) AS active_events,
    COALESCE(SUM(reservations), 0) AS active_reservations,
    COALESCE(SUM(entries), 0) AS reserved_entries,
    COALESCE(SUM(capacity), 0) AS total_capacity,
    COALESCE(SUM(potential), 0) AS potential_revenue,
    (SELECT id FROM top_event) AS top_event_id,
    (SELECT name FROM top_event) AS top_event_name,
    (SELECT entries FROM top_event) AS top_event_entries,
    (SELECT COUNT(*) FROM active_events WHERE entries = (SELECT entries FROM top_event)) AS top_event_ties,
    (SELECT category_id FROM top_category) AS top_category_id,
    (SELECT name FROM top_category) AS top_category_name,
    (SELECT entries FROM top_category) AS top_category_entries,
    (SELECT COUNT(*) FROM category_stats WHERE entries = (SELECT entries FROM top_category)) AS top_category_ties
FROM active_events
SQL;
        $result = $this->pdo->query($sql)->fetch();
        $result['occupation'] = DashboardMetrics::occupation((string) $result['reserved_entries'], (string) $result['total_capacity']);
        return $result;
    }

    private const RESERVATION = "SELECT r.id, r.code, r.quantity, r.unit_price, r.total, r.status, r.created_at, r.cancelled_at,
        e.id AS event_id, e.name AS event_name, e.starts_at, e.location, e.status AS event_status,
        u.full_name, u.email, t.code AS ticket_code,
        CASE WHEN e.status = 'PUBLICADO' AND e.starts_at <= UTC_TIMESTAMP() THEN 'FINALIZADO' ELSE e.status END AS display_event_status
        FROM reservations r JOIN events e ON e.id = r.event_id JOIN users u ON u.id = r.user_id
        JOIN tickets t ON t.reservation_id = r.id";

    public function reservations(AdminReservationFilter $filter): array
    {
        $sql = self::RESERVATION . ' WHERE 1 = 1'; $params = [];
        $literal = static fn (string $value): string => '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $value) . '%';
        if ($filter->event !== '') { $sql .= " AND e.name LIKE ? ESCAPE '!'"; $params[] = $literal($filter->event); }
        if ($filter->user !== '') { $sql .= " AND (u.full_name LIKE ? ESCAPE '!' OR u.email LIKE ? ESCAPE '!')"; $params[] = $literal($filter->user); $params[] = $literal($filter->user); }
        if ($filter->state !== '') { $sql .= ' AND r.status = ?'; $params[] = $filter->state; }
        $query = $this->pdo->prepare($sql . ' ORDER BY r.created_at DESC, r.id DESC LIMIT 51 OFFSET ?');
        foreach ($params as $index => $value) { $query->bindValue($index + 1, $value); }
        $query->bindValue(count($params) + 1, ($filter->page - 1) * 50, PDO::PARAM_INT); $query->execute();
        $rows = $query->fetchAll();
        return ['rows' => array_slice($rows, 0, 50), 'hasMore' => count($rows) > 50];
    }

    public function reservation(int $id): ?array
    {
        $query = $this->pdo->prepare(self::RESERVATION . ' WHERE r.id = ?'); $query->execute([$id]);
        return $query->fetch() ?: null;
    }
}
