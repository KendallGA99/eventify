<?php

declare(strict_types=1);

namespace Eventify\Infrastructure;

use Eventify\Support\Config;
use PDO;
use RuntimeException;

final class Database
{
    public static function connect(): PDO
    {
        $host = Config::get('DB_HOST', 'db');
        $port = Config::get('DB_PORT', '3306');
        $database = Config::get('DB_DATABASE', 'eventify');
        if (!preg_match('/\A[a-zA-Z0-9_.-]+\z/', $host)
            || !ctype_digit($port) || (int) $port < 1 || (int) $port > 65535
            || !preg_match('/\A[a-zA-Z0-9_]+\z/', $database)) {
            throw new RuntimeException('La configuración de base de datos no es válida.');
        }
        $pdo = new PDO(
            'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database . ';charset=utf8mb4',
            Config::required('DB_USERNAME'),
            Config::required('DB_PASSWORD'),
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 3]
        );
        $pdo->exec("SET time_zone = '+00:00'");
        return $pdo;
    }
}
