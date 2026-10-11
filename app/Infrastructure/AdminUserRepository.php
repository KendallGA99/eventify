<?php
declare(strict_types=1);
namespace Eventify\Infrastructure;

use Eventify\Domain\BusinessError;
use PDO;

final class AdminUserRepository
{
    public function __construct(private readonly PDO $pdo) {}

    public function find(int $id): ?array
    {
        $query = $this->pdo->prepare('SELECT u.id, u.full_name, u.email, u.is_active, r.code AS role FROM users u JOIN roles r ON r.id = u.role_id WHERE u.id = ?');
        $query->execute([$id]);
        return $query->fetch() ?: null;
    }

    public function search(mixed $search, mixed $page): array
    {
        if (!is_string($search) || strlen($search) > 160 || !is_string($page) || !preg_match('/\A[1-9][0-9]{0,3}\z/', $page)) {
            throw new BusinessError('Revise la búsqueda y el número de página.');
        }
        $search = trim($search);
        $pattern = '%' . strtr($search, ['!' => '!!', '%' => '!%', '_' => '!_']) . '%';
        $query = $this->pdo->prepare("SELECT u.id, u.full_name, u.email, u.is_active, r.code AS role FROM users u JOIN roles r ON r.id = u.role_id WHERE u.full_name LIKE ? ESCAPE '!' OR u.email LIKE ? ESCAPE '!' ORDER BY u.full_name, u.id LIMIT 21 OFFSET ?");
        $query->bindValue(1, $pattern);
        $query->bindValue(2, $pattern);
        $query->bindValue(3, ((int) $page - 1) * 20, PDO::PARAM_INT);
        $query->execute();
        $rows = $query->fetchAll();
        return ['rows' => array_slice($rows, 0, 20), 'hasMore' => count($rows) > 20, 'page' => (int) $page, 'search' => $search];
    }
}
