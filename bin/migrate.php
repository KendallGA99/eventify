<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

use Eventify\Infrastructure\Database;
use Eventify\Infrastructure\MigrationRunner;

try {
    $versions = (new MigrationRunner(Database::connect(), PROJECT_ROOT . '/database/migrations'))->run();
    echo $versions === [] ? "Esquema actualizado; ninguna migración repetida.\n" : 'Migraciones aplicadas: ' . implode(', ', $versions) . "\n";
} catch (PDOException) {
    fwrite(STDERR, "No fue posible conectar o preparar la base de datos. Revise servicio y configuración; no elimine volúmenes.\n");
    exit(1);
} catch (RuntimeException $exception) {
    fwrite(STDERR, $exception->getMessage() . "\n");
    exit(1);
} catch (Throwable) {
    fwrite(STDERR, "No se pudo completar la migración. Revise la instalación.\n");
    exit(1);
}
