<?php
declare(strict_types=1);
require dirname(__DIR__, 2) . '/app/bootstrap.php';

use Eventify\Infrastructure\Database;
use Eventify\Infrastructure\DatabaseConfig;
use Eventify\Support\Config;

try {
    if (Config::get('APP_ENV') !== 'production'
        || Config::get('DB_DATABASE') !== 'eventify_no_tls_test'
        || Config::get('DB_HOST') !== 'db'
        || Config::get('DB_TLS_MODE') !== 'verify_identity') {
        throw new RuntimeException();
    }
    // Validar antes del negativo: un fallo de configuración no demuestra rechazo TLS.
    $configuration = DatabaseConfig::connection();
    if (!$configuration['tls']
        || $configuration['options'][PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] !== true) {
        throw new RuntimeException();
    }
    // Control TCP deliberadamente sin TLS, limitado a esta base/red efímeras.
    // No se altera APP_ENV ni el modo de TLS de la aplicación.
    $plainConnect = static function () use ($configuration): PDO {
        $pdo = new PDO($configuration['dsn'], Config::required('DB_USERNAME'),
            Config::required('DB_PASSWORD'), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_TIMEOUT => 3]);
        $cipher = $pdo->query("SHOW SESSION STATUS LIKE 'Ssl_cipher'")->fetch(PDO::FETCH_NUM);
        $ssl = $pdo->query("SHOW GLOBAL VARIABLES LIKE 'tls_version'")->fetch(PDO::FETCH_NUM);
        if ($pdo->query('SELECT DATABASE()')->fetchColumn() !== 'eventify_no_tls_test'
            || !str_starts_with((string) $pdo->query('SELECT VERSION()')->fetchColumn(), '8.4.')
            || !is_array($cipher) || $cipher[1] !== ''
            || !is_array($ssl) || $ssl[1] !== ''
            || (int) $pdo->query('SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE()')->fetchColumn() !== 0) {
            throw new RuntimeException();
        }
        return $pdo;
    };
    $control = $plainConnect();
    $control = null;
    $rejected = false;
    try {
        Database::connect();
    } catch (PDOException) {
        $rejected = true;
    } catch (RuntimeException $exception) {
        // Algunas versiones PDO podrían conectar sin TLS; Database exige Ssl_cipher.
        if ($exception->getMessage() !== 'La conexión de base de datos requiere TLS.') {
            throw $exception;
        }
        $rejected = true;
    }
    if (!$rejected) { throw new RuntimeException(); }
    // Nueva conexión prueba que no fue una caída del servidor ni una migración.
    $control = $plainConnect();
    echo "MySQL 8.4 disponible sin TLS; producción rechaza la conexión y la base sigue vacía.\n";
} catch (Throwable) {
    fwrite(STDERR, "Ensayo sin TLS falló. No se muestran credenciales ni detalles de conexión.\n");
    exit(1);
}
