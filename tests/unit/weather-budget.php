<?php
declare(strict_types=1);
require dirname(__DIR__, 2) . '/app/bootstrap.php';
use Eventify\Domain\WeatherBudgetPolicy;
$checks = 0;
$expect = static function (bool $ok) use (&$checks): void {
    if (!$ok) { throw new RuntimeException('Falló una regla de presupuesto meteorológico.'); }
    ++$checks;
};
$empty = ['hour_window' => 0, 'minute_window' => 0, 'hour_requests' => 0, 'minute_requests' => 0];
$time = 36000;
$record = $empty;
for ($minute = 0; $minute < 3; ++$minute) {
    for ($request = 0; $request < 10; ++$request) {
        $next = WeatherBudgetPolicy::consume($record, $time + $minute * 60);
        $expect($next !== null);
        $record = $next;
    }
    $expect(WeatherBudgetPolicy::consume($record, $time + $minute * 60) === null);
}
$expect(WeatherBudgetPolicy::consume($record, $time + 180) === null); // Nuevo minuto no elimina límite horario.
$next = WeatherBudgetPolicy::consume($record, $time + 3600);
$expect($next !== null && $next['hour_requests'] === 1 && $next['minute_requests'] === 1);
$expect(WeatherBudgetPolicy::consume($next, $time) === null); // Reloj atrasado no reinicia cuota.
$expect(WeatherBudgetPolicy::consume($empty, -1) === null);
$near = WeatherBudgetPolicy::consume($empty, $time + 3599);
$edge = WeatherBudgetPolicy::consume($near, $time + 3600);
$expect($edge['hour_requests'] === 1 && $edge['minute_requests'] === 1);
foreach ([['hour_requests', 31], ['minute_requests', 11], ['hour_window', -1], ['minute_window', '10'], ['hour_requests', null]] as [$field, $value]) {
    $expect(WeatherBudgetPolicy::consume([...$empty, $field => $value], $time) === null);
}
$expect(WeatherBudgetPolicy::consume([], $time) === null);
echo "Presupuesto meteorológico: $checks comprobaciones correctas.\n";
