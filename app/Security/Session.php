<?php
declare(strict_types=1);
namespace Eventify\Security;

use Eventify\Support\Config;
use RuntimeException;

final class Session
{
    public static function start(): void
    {
        $directory = PROJECT_ROOT . '/storage/sessions';
        if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) {
            throw new RuntimeException('Almacenamiento de sesiones no disponible.');
        }
        session_save_path($directory);
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        session_name('eventify_session');
        session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'secure' => Config::get('APP_ENV', 'production') === 'production' || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'), 'httponly' => true, 'samesite' => 'Lax']);
        if (!session_start()) {
            throw new RuntimeException('No fue posible iniciar la sesión.');
        }
        $now = time();
        if (isset($_SESSION['last_activity']) && $now - (int) $_SESSION['last_activity'] > 1800) {
            $_SESSION = [];
            session_regenerate_id(true);
        }
        $_SESSION['last_activity'] = $now;
        header('Cache-Control: no-store');
    }

    public static function token(): string
    {
        return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
    }

    public static function validToken(mixed $token): bool
    {
        return is_string($token) && isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
    }

    public static function login(int $userId): void
    {
        $_SESSION = [];
        session_regenerate_id(true);
        $_SESSION['user_id'] = $userId;
        $_SESSION['last_activity'] = time();
        self::token();
    }

    public static function logout(): void
    {
        $_SESSION = [];
        $params = session_get_cookie_params();
        setcookie(session_name(), '', ['expires' => time() - 3600, 'path' => $params['path'], 'secure' => $params['secure'], 'httponly' => true, 'samesite' => 'Lax']);
        session_destroy();
    }
}
