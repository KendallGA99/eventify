<?php
declare(strict_types=1);
namespace Eventify\Domain;

use PDO;
use Throwable;

final class AdminUserService
{
    public function __construct(private readonly PDO $pdo) {}

    public function promote(int $actorId, int $targetId, mixed $password, mixed $confirmation): void
    {
        if (!is_string($password) || strlen($password) > 72 || str_contains($password, "\0") || $confirmation !== '1') {
            throw new BusinessError('Confirme la autorización e ingrese su contraseña actual.');
        }
        $this->pdo->beginTransaction();
        try {
            // Se bloquean en orden para revalidar ambas cuentas sin cruzar bloqueos concurrentes.
            $query = $this->pdo->prepare('SELECT u.id, u.role_id, u.is_active, u.password_hash, r.code AS role FROM users u JOIN roles r ON r.id = u.role_id WHERE u.id IN (?, ?) ORDER BY u.id FOR UPDATE');
            $query->execute([$actorId, $targetId]);
            $accounts = [];
            foreach ($query->fetchAll() as $account) {
                $accounts[(int) $account['id']] = $account;
            }
            $actor = $accounts[$actorId] ?? null;
            $target = $accounts[$targetId] ?? null;
            if ($actor === null || !$actor['is_active'] || $actor['role'] !== 'ADMINISTRADOR') {
                throw new BusinessError('Su cuenta no tiene permiso para administrar usuarios.', 403);
            }
            if (!password_verify($password, $actor['password_hash'])) {
                throw new BusinessError('La contraseña de su cuenta no es correcta.', 403);
            }
            if ($target === null) {
                throw new BusinessError('Usuario no encontrado.', 404);
            }
            if (!$target['is_active']) {
                throw new BusinessError('Solo puede autorizar cuentas activas.', 409);
            }
            if ($target['role'] === 'ADMINISTRADOR') {
                throw new BusinessError('Esta cuenta ya es administradora.', 409);
            }
            $update = $this->pdo->prepare('UPDATE users SET role_id = ? WHERE id = ?');
            $update->execute([$actor['role_id'], $targetId]);
            $this->pdo->commit();
        } catch (Throwable $error) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $error;
        }
    }
}
