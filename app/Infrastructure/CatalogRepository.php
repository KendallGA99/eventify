<?php
declare(strict_types=1);
namespace Eventify\Infrastructure;

use Eventify\Domain\CatalogFilter;
use PDO;

final class CatalogRepository
{
    public const PAGE_SIZE = 12;
    public function __construct(private readonly PDO $pdo) {}

    private const SELECT = "SELECT e.id, e.slug, e.name, e.image_path, e.starts_at, e.location, e.capacity, e.price, e.status, c.name AS category,
        CASE WHEN e.status = 'PUBLICADO' AND e.starts_at <= UTC_TIMESTAMP() THEN 'FINALIZADO' ELSE e.status END AS display_status,
        GREATEST(0, e.capacity - COALESCE((SELECT SUM(r.quantity) FROM reservations r WHERE r.event_id = e.id AND r.status = 'CONFIRMADA'), 0)) AS available
        FROM events e JOIN categories c ON c.id = e.category_id WHERE e.status <> 'BORRADOR'";

    public function categories(): array
    {
        return $this->pdo->query('SELECT id, name FROM categories WHERE is_active = 1 ORDER BY name, id')->fetchAll();
    }

    public function find(string $slug): ?array
    {
        // La descripción solamente se consulta en el detalle.
        $query = $this->pdo->prepare(str_replace('SELECT e.id,', 'SELECT e.description, e.latitude, e.longitude, e.id,', self::SELECT) . ' AND e.slug = ?');
        $query->execute([$slug]);
        return $query->fetch() ?: null;
    }

    public function search(CatalogFilter $filter): array
    {
        $sql = self::SELECT;
        $params = [];
        if ($filter->search !== '') {
            // LIKE con escape propio: % y _ del usuario se buscan como caracteres literales.
            $sql .= " AND e.name LIKE ? ESCAPE '!'";
            $params[] = '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $filter->search) . '%';
        }
        if ($filter->category !== null) { $sql .= ' AND e.category_id = ?'; $params[] = $filter->category; }
        if ($filter->from !== '') { $sql .= ' AND e.starts_at >= ?'; $params[] = CatalogFilter::utcBoundary($filter->from); }
        if ($filter->to !== '') { $sql .= ' AND e.starts_at < ?'; $params[] = CatalogFilter::utcBoundary($filter->to, true); }
        $sql .= match ($filter->state) {
            'upcoming' => " AND e.status = 'PUBLICADO' AND e.starts_at > UTC_TIMESTAMP()",
            'cancelled' => " AND e.status = 'CANCELADO'",
            'finished' => " AND (e.status = 'FINALIZADO' OR (e.status = 'PUBLICADO' AND e.starts_at <= UTC_TIMESTAMP()))",
            default => '',
        };
        if ($filter->availability !== 'all') {
            // Disponibilidad corresponde solo a eventos reservables; cancelados/finalizados no ofrecen entradas.
            $sql .= " AND e.status = 'PUBLICADO' AND e.starts_at > UTC_TIMESTAMP() HAVING available " . ($filter->availability === 'available' ? '> 0' : '= 0');
        }
        $sql .= ' ORDER BY e.starts_at, e.id LIMIT ? OFFSET ?';
        $query = $this->pdo->prepare($sql);
        foreach ($params as $index => $value) { $query->bindValue($index + 1, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR); }
        $query->bindValue(count($params) + 1, self::PAGE_SIZE + 1, PDO::PARAM_INT);
        $query->bindValue(count($params) + 2, ($filter->page - 1) * self::PAGE_SIZE, PDO::PARAM_INT);
        $query->execute();
        $rows = $query->fetchAll();
        return ['rows' => array_slice($rows, 0, self::PAGE_SIZE), 'hasMore' => count($rows) > self::PAGE_SIZE];
    }
}
