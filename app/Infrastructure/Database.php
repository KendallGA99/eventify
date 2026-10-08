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
        $connection = DatabaseConfig::connection();
        $pdo = new PDO(
            $connection['dsn'],
            Config::required('DB_USERNAME'),
            Config::required('DB_PASSWORD'),
            $connection['options']
        );
        if ($connection['tls']) {
            $status = $pdo->query("SHOW SESSION STATUS LIKE 'Ssl_cipher'")->fetch(PDO::FETCH_NUM);
            if (!is_array($status) || !isset($status[1]) || trim((string) $status[1]) === '') {
                throw new RuntimeException('La conexión de base de datos requiere TLS.');
            }
        }
        $pdo->exec("SET time_zone = '+00:00'");
        return $pdo;
    }
}
