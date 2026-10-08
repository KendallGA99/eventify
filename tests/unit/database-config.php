<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/bootstrap.php';

use Eventify\Infrastructure\Database;
use Eventify\Infrastructure\DatabaseConfig;

$checks = 0;
$expect = static function (bool $condition, string $message) use (&$checks): void {
    if (!$condition) { throw new RuntimeException($message); }
    $checks++;
};
$keys = ['APP_ENV', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_TLS_MODE', 'DB_TLS_CA'];
$original = [];
foreach ($keys as $key) { $original[$key] = getenv($key); }
$reset = static function (): void {
    foreach (['APP_ENV' => 'local', 'DB_HOST' => 'db', 'DB_PORT' => '3306', 'DB_DATABASE' => 'eventify_test'] as $key => $value) {
        putenv($key . '=' . $value);
    }
    putenv('DB_TLS_MODE'); putenv('DB_TLS_CA');
};
$reject = static function (array $changes) use ($reset, $expect): void {
    $reset();
    foreach ($changes as $key => $value) { putenv($key . '=' . $value); }
    try { DatabaseConfig::connection(); $thrown = false; }
    catch (RuntimeException $error) {
        $thrown = true;
        $expect(in_array($error->getMessage(), [
            'La configuración de base de datos no es válida.',
            'La configuración TLS de base de datos no es válida.',
        ], true), 'El error reveló configuración interna.');
    }
    $expect($thrown, 'Se aceptó configuración insegura.');
};
$ca = tempnam(sys_get_temp_dir(), 'eventify-ca-');
$publicFile = tempnam(PROJECT_ROOT . '/public', 'eventify-ca-test-');
if ($ca === false || $publicFile === false) { throw new RuntimeException('No se pudo preparar el ensayo.'); }
try {
    $reset();
    $local = DatabaseConfig::connection();
    $expect($local['tls'] === false, 'Se alteró el modo local predeterminado.');
    $expect($local['options'][PDO::ATTR_EMULATE_PREPARES] === false, 'Consultas preparadas debilitadas.');
    $expect(!isset($local['options'][PDO::MYSQL_ATTR_SSL_CA]), 'CA inesperada en conexión local.');
    foreach ([
        ['APP_ENV' => 'production'],
        ['APP_ENV' => 'production', 'DB_TLS_MODE' => 'disabled'],
        ['APP_ENV' => 'staging'],
        ['DB_TLS_MODE' => 'preferred'],
        ['DB_TLS_MODE' => 'verify_identity', 'DB_TLS_CA' => '/no-existe-eventify.pem'],
        ['DB_TLS_MODE' => 'verify_identity', 'DB_TLS_CA' => 'https://example.invalid/ca.pem'],
        ['DB_TLS_MODE' => 'verify_identity', 'DB_TLS_CA' => "invalid\0path"],
        ['DB_TLS_MODE' => 'verify_identity', 'DB_TLS_CA' => sys_get_temp_dir()],
        ['DB_TLS_MODE' => 'verify_identity', 'DB_TLS_CA' => $ca],
        ['DB_HOST' => 'db;dbname=otra'],
        ['DB_DATABASE' => 'eventify;host=otra'],
        ['DB_PORT' => '0'], ['DB_PORT' => '65536'], ['DB_PORT' => '3306;extra'],
        ['DB_TLS_CA' => '/certificado-no-usado.pem'],
    ] as $case) { $reject($case); }
    // Certificado efímero, sin clave privada publicada ni dependencia de proveedor.
    $key = openssl_pkey_new(['private_key_bits' => 2048]);
    $csr = openssl_csr_new(['commonName' => 'eventify-unit.invalid'], $key);
    $certificate = openssl_csr_sign($csr, null, $key, 1);
    if ($certificate === false || !openssl_x509_export($certificate, $pem)) {
        throw new RuntimeException('No se pudo preparar certificado de ensayo.');
    }
    file_put_contents($ca, $pem); file_put_contents($publicFile, $pem);
    $reject(['DB_TLS_MODE' => 'verify_identity', 'DB_TLS_CA' => $publicFile]);
    $reset(); putenv('APP_ENV=production'); putenv('DB_TLS_CA=' . $ca);
    putenv('DB_HOST=mysql.example.invalid'); putenv('DB_PORT=12345');
    $secure = DatabaseConfig::connection();
    $expect($secure['tls'] === true, 'Producción no exige TLS.');
    $expect($secure['options'][PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] === true, 'Verificación de servidor deshabilitada.');
    $expect($secure['options'][PDO::MYSQL_ATTR_SSL_CA] === realpath($ca), 'CA no configurada en PDO.');
    $expect($secure['dsn'] === 'mysql:host=mysql.example.invalid;port=12345;dbname=eventify_test;charset=utf8mb4', 'Host/puerto externos incorrectos.');
    $reset(); putenv('APP_ENV=production');
    try { Database::connect(); $blocked = false; }
    catch (RuntimeException $error) { $blocked = $error->getMessage() === 'La configuración TLS de base de datos no es válida.'; }
    $expect($blocked, 'Se intentó conectar antes de validar TLS.');
    echo "Configuración MySQL/TLS: $checks comprobaciones correctas (sin conexión real).\n";
} finally {
    unlink($ca); unlink($publicFile);
    foreach ($original as $key => $value) { putenv($value === false ? $key : $key . '=' . $value); }
}
