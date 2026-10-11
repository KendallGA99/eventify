<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

use Eventify\Infrastructure\Database;
use Eventify\Infrastructure\ExampleInstaller;

try {
    if (PHP_SAPI !== 'cli' || !in_array(array_slice($argv, 1), [[], ['--public-evaluation']], true)) {
        throw new RuntimeException('Uso: php bin/prepare-examples.php [--public-evaluation]');
    }
    $created = (new ExampleInstaller(Database::connect(), PROJECT_ROOT . '/storage/example-credentials.json'))
        ->run(array_slice($argv, 1) === ['--public-evaluation']);
    echo $created
        ? "Ocho eventos y cuentas preparados. Acceso privado: storage/example-credentials.json.\n"
        : "Instalación existente conservada; no se cambiaron datos, fechas ni claves.\n";
} catch (Throwable) {
    fwrite(STDERR, "No se pudo preparar el catálogo. Revise conexión, migraciones, conflictos y almacenamiento privado. No se muestran datos de acceso.\n");
    exit(1);
}
