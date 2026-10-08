<?php
declare(strict_types=1);
namespace Eventify\Domain;

use PDO;
use PDOException;
use Throwable;

final class AdminService
{
    public function __construct(private readonly PDO $pdo) {}

    public function category(?int $id, array $input): int
    {
        $data = AdminInput::category($input);
        $this->pdo->beginTransaction();
        try {
            if ($id !== null) {
                $query = $this->pdo->prepare('SELECT id FROM categories WHERE id = ? FOR UPDATE'); $query->execute([$id]);
                if (!$query->fetchColumn()) { throw new BusinessError('Categoría no encontrada.', 404); }
                $query = $this->pdo->prepare('UPDATE categories SET name = ?, slug = ?, is_active = ? WHERE id = ?');
                $query->execute([...array_values($data), $id]);
            } else {
                $query = $this->pdo->prepare('INSERT INTO categories (name, slug, is_active) VALUES (?, ?, ?)');
                $query->execute(array_values($data)); $id = (int) $this->pdo->lastInsertId();
            }
            $this->pdo->commit(); return $id;
        } catch (Throwable $error) { $this->fail($error); }
    }

    public function event(?int $id, array $input): int
    {
        $data = AdminInput::event($input);
        $this->pdo->beginTransaction();
        try {
            $existing = $id === null ? null : $this->lockEvent($id);
            if ($existing !== null && in_array($existing['status'], ['CANCELADO', 'FINALIZADO'], true)) { throw new BusinessError('Los eventos cancelados o finalizados conservan su información y no admiten edición.', 409); }
            // Orden de bloqueos compartido con reservas: evento primero; categoría después.
            $query = $this->pdo->prepare('SELECT is_active FROM categories WHERE id = ? FOR SHARE');
            $query->execute([$data['category_id']]); $active = $query->fetchColumn();
            if ($active === false || (!(bool) $active && ($existing === null || (int) $existing['category_id'] !== $data['category_id']))) { throw new BusinessError('Seleccione una categoría activa.'); }
            $now = (string) $this->pdo->query('SELECT UTC_TIMESTAMP()')->fetchColumn();
            if ($data['starts_at'] <= $now) { throw new BusinessError('La fecha de un evento nuevo o editable debe ser futura. Para uno pasado, use Finalizar.', 409); }
            if ($existing !== null) {
                $query = $this->pdo->prepare("SELECT COUNT(*) AS history_count, COALESCE(SUM(CASE WHEN status = 'CONFIRMADA' THEN quantity ELSE 0 END), 0) AS occupied FROM reservations WHERE event_id = ?");
                $query->execute([$id]); $reservations = $query->fetch();
                if ($data['capacity'] < (int) $reservations['occupied']) { throw new BusinessError('La capacidad no puede ser menor que las entradas confirmadas.', 409); }
                if ($data['slug'] !== $existing['slug']) { throw new BusinessError('El identificador del evento se conserva para mantener sus enlaces.', 409); }
                if ((int) $reservations['history_count'] > 0 && ($data['starts_at'] !== $existing['starts_at'] || $data['status'] === 'BORRADOR')) { throw new BusinessError('Un evento con historial conserva su fecha y no puede volver a borrador.', 409); }
            }
            $columns = array_keys($data);
            if ($id === null) {
                $query = $this->pdo->prepare('INSERT INTO events (' . implode(', ', $columns) . ') VALUES (' . implode(', ', array_fill(0, count($columns), '?')) . ')');
                $query->execute(array_values($data)); $id = (int) $this->pdo->lastInsertId();
            } else {
                $query = $this->pdo->prepare('UPDATE events SET ' . implode(', ', array_map(static fn (string $key): string => $key . ' = ?', $columns)) . ' WHERE id = ?');
                $query->execute([...array_values($data), $id]);
            }
            $this->pdo->commit(); return $id;
        } catch (Throwable $error) { $this->fail($error); }
    }

    public function state(int $id, mixed $status): void
    {
        if (!in_array($status, ['CANCELADO', 'FINALIZADO'], true)) { throw new BusinessError('Seleccione cancelar o finalizar.'); }
        $this->pdo->beginTransaction();
        try {
            $event = $this->lockEvent($id);
            if ($event['status'] === $status) { $this->pdo->commit(); return; }
            if (in_array($event['status'], ['CANCELADO', 'FINALIZADO'], true)) { throw new BusinessError('Este evento ya tiene un estado definitivo.', 409); }
            if ($status === 'FINALIZADO' && $event['starts_at'] > (string) $this->pdo->query('SELECT UTC_TIMESTAMP()')->fetchColumn()) { throw new BusinessError('Solo puede finalizar un evento cuyo inicio ya ocurrió.', 409); }
            $query = $this->pdo->prepare('UPDATE events SET status = ? WHERE id = ?'); $query->execute([$status, $id]);
            $this->pdo->commit();
        } catch (Throwable $error) { $this->fail($error); }
    }

    private function lockEvent(int $id): array
    {
        $query = $this->pdo->prepare('SELECT * FROM events WHERE id = ? FOR UPDATE'); $query->execute([$id]);
        $event = $query->fetch();
        if ($event === false) { throw new BusinessError('Evento no encontrado.', 404); }
        return $event;
    }

    private function fail(Throwable $error): never
    {
        if ($this->pdo->inTransaction()) { $this->pdo->rollBack(); }
        if ($error instanceof PDOException && (int) ($error->errorInfo[1] ?? 0) === 1062) { throw new BusinessError('Ese nombre de categoría o identificador ya existe.', 409); }
        throw $error;
    }
}
