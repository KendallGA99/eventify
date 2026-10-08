<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';

try {
    if (Eventify\Support\Config::get('APP_ENV') !== 'production' || $argc !== 3 || $argv[1] !== '--confirm-evaluation-fixture') { throw new RuntimeException(); }
    $path = realpath($argv[2]);
    if ($path === false || !is_file($path) || !is_readable($path) || filesize($path) > 8192
        || str_starts_with($path, PROJECT_ROOT . '/public/')) { throw new RuntimeException(); }
    $accounts = json_decode((string) file_get_contents($path), true, 32, JSON_THROW_ON_ERROR);
    if (!is_array($accounts)) { throw new RuntimeException(); }
    (new Eventify\Infrastructure\EvaluationProvisioner(Eventify\Infrastructure\Database::connect()))->run($accounts);
    echo "Tres cuentas ficticias creadas; consulte las claves en el archivo privado original.\n";
} catch (Throwable) {
    fwrite(STDERR, "Aprovisionamiento rechazado. Revise autorización explícita, plan privado, producción/TLS y ausencia de usuarios previos. No se muestran credenciales.\n");
    exit(1);
}
