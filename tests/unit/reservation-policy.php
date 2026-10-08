<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/bootstrap.php';

use Eventify\Domain\ReservationPolicy;

$checks = 0;
$expect = static function (bool $condition, string $message) use (&$checks): void {
    if (!$condition) {
        fwrite(STDERR, 'Fallo: ' . $message . "\n");
        exit(1);
    }
    $checks++;
};
$now = new DateTimeImmutable('2026-10-08T12:00:00+00:00');
foreach ([86401 => true, 86400 => false, 86399 => false, 0 => false] as $seconds => $allowed) {
    $expect(ReservationPolicy::canCancel($now->modify('+' . $seconds . ' seconds'), $now) === $allowed, 'Límite de cancelación incorrecto.');
}
$sameInstant = new DateTimeImmutable('2026-10-08T06:00:00-06:00');
$expect(!ReservationPolicy::canCancel($now->modify('+24 hours'), $sameInstant), 'Zona horaria cambia la regla.');
foreach ([[10, 2, true], [10, 3, false], [3, 1, false], [3, 0, true], [10, 1, true]] as [$capacity, $available, $expected]) {
    $expect(ReservationPolicy::isLastSpaces($capacity, $available) === $expected, 'Umbral o redondeo de cupos incorrecto.');
}
$expect(ReservationPolicy::available(10, 8) === 2, 'Cupos restantes incorrectos.');
foreach ([[10, 11], [0, 0], [10, -1]] as [$capacity, $reserved]) {
    $failed = false;
    try { ReservationPolicy::available($capacity, $reserved); } catch (InvalidArgumentException) { $failed = true; }
    $expect($failed, 'Capacidad inconsistente aceptada.');
}
foreach (['BORRADOR', 'CANCELADO', 'FINALIZADO'] as $status) {
    $expect(!ReservationPolicy::canReserve($status, $now->modify('+1 day'), $now), 'Estado no reservable aceptado.');
}
$expect(!ReservationPolicy::canReserve('PUBLICADO', $now, $now), 'Evento iniciado permite reservar.');
$expect(ReservationPolicy::canReserve('PUBLICADO', $now->modify('+1 second'), $now), 'Evento publicado futuro rechazado.');
echo "Reglas de reservación: $checks comprobaciones correctas.\n";
