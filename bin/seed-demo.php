<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

use Eventify\Infrastructure\Database;
use Eventify\Infrastructure\DemoSeeder;

try {
    $inserted = (new DemoSeeder(Database::connect(), PROJECT_ROOT . '/storage/demo-credentials.json'))->run();
    echo $inserted ? "Demostración local creada. Credenciales únicas en storage/demo-credentials.json (privado; no subir a Git).\n" : "Demostración existente conservada; no se modificaron cuentas, claves o fechas.\n";
} catch (PDOException) {
    fwrite(STDERR, "La demostración no pudo guardarse. Compruebe MySQL, migraciones y conflictos con datos existentes; no se sobrescribieron registros.\n");
    exit(1);
} catch (RuntimeException $exception) {
    fwrite(STDERR, $exception->getMessage() . "\n");
    exit(1);
} catch (Throwable) {
    fwrite(STDERR, "No se pudo preparar la demostración local. Revise el almacenamiento privado.\n");
    exit(1);
}
