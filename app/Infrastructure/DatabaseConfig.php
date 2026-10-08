<?php

declare(strict_types=1);

namespace Eventify\Infrastructure;

use Eventify\Support\Config;
use PDO;
use RuntimeException;

final class DatabaseConfig
{
    public static function connection(): array
    {
        $host = Config::get('DB_HOST', 'db');
        $port = Config::get('DB_PORT', '3306');
        $database = Config::get('DB_DATABASE', 'eventify');
        $environment = Config::get('APP_ENV', 'local');
        $mode = Config::get('DB_TLS_MODE', $environment === 'production' ? 'verify_identity' : 'disabled');
        if (!preg_match('/\A[a-zA-Z0-9_.-]+\z/', $host)
            || !ctype_digit($port) || (int) $port < 1 || (int) $port > 65535
            || !preg_match('/\A[a-zA-Z0-9_]+\z/', $database)
            || !in_array($environment, ['local', 'test', 'production'], true)
            || !in_array($mode, ['disabled', 'verify_identity'], true)
            || ($environment === 'production' && $mode !== 'verify_identity')) {
            throw new RuntimeException('La configuración de base de datos no es válida.');
        }
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_TIMEOUT => 3];
        $ca = Config::get('DB_TLS_CA');
        if ($mode === 'verify_identity') {
            // Archivo montado al ejecutar; nunca leer certificados mediante URL.
            $resolved = $ca !== '' && !str_contains($ca, "\0") && !str_contains($ca, '://') ? realpath($ca) : false;
            $public = realpath(PROJECT_ROOT . '/public');
            if ($resolved === false || !is_file($resolved) || !is_readable($resolved)
                || filesize($resolved) === 0
                || ($public !== false && str_starts_with($resolved, $public . DIRECTORY_SEPARATOR))
                || !defined('PDO::MYSQL_ATTR_SSL_CA') || !defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
                throw new RuntimeException('La configuración TLS de base de datos no es válida.');
            }
            $options[PDO::MYSQL_ATTR_SSL_CA] = $resolved;
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
        } elseif ($ca !== '') {
            // No ignorar un certificado configurado por error con TLS deshabilitado.
            throw new RuntimeException('La configuración TLS de base de datos no es válida.');
        }
        return ['dsn' => 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database . ';charset=utf8mb4',
            'options' => $options, 'tls' => $mode === 'verify_identity'];
    }
}
