<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';

use Eventify\Support\Config;
use Eventify\Infrastructure\DatabaseConfig;

try {
    if (Config::get('APP_ENV') !== 'production') { throw new RuntimeException(); }
    foreach (['APP_SECRET', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'DB_TLS_MODE', 'DB_TLS_CA'] as $key) {
        Config::required($key);
    }
    if (strlen(Config::required('APP_SECRET')) < 32) { throw new RuntimeException(); }
    DatabaseConfig::connection();
    echo "Configuración pública validada; falta conectar y migrar.\n";
} catch (Throwable) {
    fwrite(STDERR, "Arranque público rechazado: revise entorno production, claves y TLS verificado. No se muestran valores privados.\n");
    exit(1);
}
