<?php
declare(strict_types=1);
require dirname(__DIR__, 2) . '/app/bootstrap.php';
use Eventify\Domain\ReservationService;
use Eventify\Domain\BusinessError;
use Eventify\Support\Format;
$checks = 0;
foreach ([0, -1, '0', '-1', '1.5', 1.5, '', null, [], true, '1e3', ' 1 ', '4294967296'] as $value) {
    try { ReservationService::quantity($value); fwrite(STDERR, "Cantidad inválida aceptada.\n"); exit(1); }
    catch (BusinessError $exception) { ++$checks; }
}
foreach ([1, '2', '4294967295'] as $value) {
    if (ReservationService::quantity($value) !== (int) $value) { exit(1); }
    ++$checks;
}
foreach (['0.00' => '₡0,00', '15000.00' => '₡15.000,00', '42949672949957050327.05' => '₡42.949.672.949.957.050.327,05'] as $input => $expected) {
    if (Format::money($input) !== $expected) { fwrite(STDERR, "Importe mostrado incorrecto.\n"); exit(1); }
    ++$checks;
}
echo 'Cantidades e importes: ' . $checks . " comprobaciones correctas.\n";
