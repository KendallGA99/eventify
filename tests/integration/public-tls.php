<?php
declare(strict_types=1);
require dirname(__DIR__, 2) . '/app/bootstrap.php';

use Eventify\Infrastructure\Database;
use Eventify\Support\Config;

try {
    if (Config::get('APP_ENV') !== 'production' || Config::get('DB_DATABASE') !== 'eventify_tls_test'
        || !in_array($argv[1] ?? '', ['allow', 'deny'], true)) { throw new RuntimeException(); }
    if ($argv[1] === 'deny') {
        $rejected = false;
        try { Database::connect(); } catch (PDOException) { $rejected = true; }
        if (!$rejected) { throw new RuntimeException(); }
        echo "Identidad TLS incorrecta rechazada por PDO.\n";
    } else {
        $pdo = Database::connect();
        if ($pdo->query('SELECT DATABASE()')->fetchColumn() !== 'eventify_tls_test'
            || !str_starts_with((string) $pdo->query('SELECT VERSION()')->fetchColumn(), '8.4.')
            || (int) $pdo->query("SELECT COUNT(*) FROM schema_migrations WHERE status='APLICADA'")->fetchColumn() !== 3) {
            throw new RuntimeException();
        }
        echo "TLS válido, MySQL8.4 y tres migraciones públicas verificados.\n";
    }
} catch (Throwable) {
    fwrite(STDERR, "Ensayo TLS falló. No se muestran credenciales ni detalles de conexión.\n");
    exit(1);
}
