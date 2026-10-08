<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/bootstrap.php';

use Eventify\Support\Config;

$checks = 0;
$expect = static function (bool $condition, string $message) use (&$checks): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
    $checks++;
};

putenv('EVENTIFY_TEST_CONFIG');
$expect(Config::get('EVENTIFY_TEST_CONFIG', 'fallback') === 'fallback', 'Valor por defecto incorrecto.');
foreach (['', '__GENERATE_DB_PASSWORD__'] as $value) {
    putenv('EVENTIFY_TEST_CONFIG=' . $value);
    $thrown = false;
    try {
        Config::required('EVENTIFY_TEST_CONFIG');
    } catch (RuntimeException) {
        $thrown = true;
    }
    $expect($thrown, 'Configuración incompleta aceptada.');
}
putenv('EVENTIFY_TEST_CONFIG=valid-value');
$expect(Config::required('EVENTIFY_TEST_CONFIG') === 'valid-value', 'Configuración válida rechazada.');
putenv('EVENTIFY_TEST_CONFIG');
echo "Configuración: $checks comprobaciones correctas.\n";
