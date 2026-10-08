<?php
declare(strict_types=1);
namespace Eventify\Infrastructure;

use Eventify\Support\Config;
use PDO;
use RuntimeException;
use Throwable;

final class EvaluationProvisioner
{
    public function __construct(private readonly PDO $pdo) {}

    public function run(array $accounts): void
    {
        if (Config::get('APP_ENV') !== 'production' || $this->pdo->inTransaction()) {
            throw new RuntimeException('Aprovisionamiento explícito de producción requerido.');
        }
        $expected = ['admin-evaluacion@eventify.test' => 'ADMINISTRADOR',
            'usuario-evaluacion@eventify.test' => 'USUARIO', 'otro-evaluacion@eventify.test' => 'USUARIO'];
        if (count($accounts) !== 3) { throw new RuntimeException('Plan de evaluación inválido.'); }
        $seen = [];
        $passwords = [];
        foreach ($accounts as $account) {
            if (!is_array($account) || !is_string($account['email'] ?? null)
                || !isset($expected[$account['email']])
                || $expected[$account['email']] !== ($account['role'] ?? null)
                || isset($seen[$account['email']]) || !is_string($account['full_name'] ?? null)
                || trim($account['full_name']) === '' || strlen($account['full_name']) > 100
                || !is_string($account['password'] ?? null)
                || !preg_match('/\A[a-f0-9]{32}\z/', $account['password'])
                || in_array($account['password'], $passwords, true)) {
                throw new RuntimeException('Plan de evaluación inválido.');
            }
            $seen[$account['email']] = true;
            $passwords[] = $account['password'];
        }
        $lockName = 'eventify:evaluation:' . substr(hash('sha256', (string) $this->pdo->query('SELECT DATABASE()')->fetchColumn()), 0, 32);
        $lock = $this->pdo->prepare('SELECT GET_LOCK(?, 10)');
        $lock->execute([$lockName]);
        if ((int) $lock->fetchColumn() !== 1) { throw new RuntimeException('Aprovisionamiento ocupado.'); }
        try {
            $this->pdo->exec('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');
            $this->pdo->beginTransaction();
            // Lectura bloqueante de toda la tabla: no sobrescribir cuentas ni competir con altas públicas.
            if ($this->pdo->query('SELECT id FROM users FOR UPDATE')->fetch() !== false) {
                throw new RuntimeException('El aprovisionamiento exige una base sin usuarios.');
            }
            $roles = $this->pdo->query('SELECT code, id FROM roles')->fetchAll(PDO::FETCH_KEY_PAIR);
            if (!isset($roles['ADMINISTRADOR'], $roles['USUARIO'])) { throw new RuntimeException('Faltan migraciones.'); }
            $insert = $this->pdo->prepare('INSERT INTO users (role_id, full_name, email, password_hash) VALUES (?, ?, ?, ?)');
            foreach ($accounts as $account) {
                $insert->execute([$roles[$account['role']], trim($account['full_name']), $account['email'], password_hash($account['password'], PASSWORD_DEFAULT)]);
            }
            $this->pdo->commit();
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) { $this->pdo->rollBack(); }
            throw $exception;
        } finally {
            $release = $this->pdo->prepare('SELECT RELEASE_LOCK(?)');
            $release->execute([$lockName]);
        }
    }
}
