<?php
declare(strict_types=1);
namespace Eventify\Security;

use Eventify\Domain\RegistrationInput;
use Eventify\Support\Config;
use PDO;
use PDOException;
use Throwable;

final class Auth
{
    public function __construct(private PDO $pdo) {}

    public function user(): ?array
    {
        if (!isset($_SESSION['user_id'])) { return null; }
        $query = $this->pdo->prepare('SELECT u.id, u.full_name, u.email, r.code AS role FROM users u JOIN roles r ON r.id = u.role_id WHERE u.id = ? AND u.is_active = 1');
        $query->execute([$_SESSION['user_id']]);
        $user = $query->fetch();
        if ($user === false) { unset($_SESSION['user_id']); return null; }
        return $user;
    }

    public function register(array $input): array
    {
        $errors = RegistrationInput::errors($input);
        if ($errors !== []) { return $errors; }
        try {
            // El rol enviado por el navegador nunca participa en el registro público.
            $query = $this->pdo->prepare("INSERT INTO users (role_id, full_name, email, password_hash) VALUES ((SELECT id FROM roles WHERE code = 'USUARIO'), ?, ?, ?)");
            $query->execute([trim(RegistrationInput::text($input, 'full_name')), RegistrationInput::email(RegistrationInput::text($input, 'email')), password_hash(RegistrationInput::text($input, 'password'), PASSWORD_DEFAULT)]);
        } catch (PDOException $exception) {
            if ((int) ($exception->errorInfo[1] ?? 0) === 1062) {
                return ['Ese correo ya está registrado. Puede iniciar sesión.'];
            }
            throw $exception;
        }
        return [];
    }

    public function authenticate(string $email, string $password): bool
    {
        $query = $this->pdo->prepare('SELECT id, password_hash, is_active FROM users WHERE email = ?');
        $query->execute([RegistrationInput::email($email)]);
        $user = $query->fetch();
        // Hash ficticio para conservar el trabajo de verificación en cuentas inexistentes.
        $hash = $user === false ? '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi' : $user['password_hash'];
        $valid = strlen($password) <= 72 && !str_contains($password, "\0") && password_verify($password, $hash);
        if (!$valid || $user === false || !(bool) $user['is_active']) { return false; }
        if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            $update = $this->pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
            $update->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);
        }
        Session::login((int) $user['id']);
        return true;
    }

    public function allowAttempt(string $action, string $address): bool
    {
        // Dirección remota real, sin confiar en cabeceras de proxy enviadas por el cliente.
        $bucket = hash_hmac('sha256', $action . ':' . $address, Config::required('APP_SECRET'));
        $now = time();
        $this->pdo->beginTransaction();
        try {
            $insert = $this->pdo->prepare('INSERT IGNORE INTO auth_limits (bucket, window_started, attempts) VALUES (?, ?, 0)');
            $insert->execute([$bucket, $now]);
            $query = $this->pdo->prepare('SELECT window_started, attempts FROM auth_limits WHERE bucket = ? FOR UPDATE');
            $query->execute([$bucket]);
            $row = $query->fetch();
            $fresh = $now - (int) $row['window_started'] >= 900;
            $attempts = $fresh ? 0 : (int) $row['attempts'];
            $allowed = $attempts < 10;
            if ($allowed) {
                $update = $this->pdo->prepare('UPDATE auth_limits SET window_started = ?, attempts = ? WHERE bucket = ?');
                $update->execute([$fresh ? $now : $row['window_started'], $attempts + 1, $bucket]);
            }
            $this->pdo->commit();
            return $allowed;
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) { $this->pdo->rollBack(); }
            throw $exception;
        }
    }
}
