<?php
declare(strict_types=1);
require dirname(__DIR__, 2) . '/app/bootstrap.php';
use Eventify\Domain\EventWeather;
$checks = 0;
$assert = static function (bool $ok) use (&$checks): void {
    if (!$ok) { fwrite(STDERR, "Falló una regla de clima.\n"); exit(1); }
    ++$checks;
};
$root = sys_get_temp_dir() . '/eventify-weather-test-' . bin2hex(random_bytes(8));
$now = new DateTimeImmutable('2026-10-08 12:00:00', new DateTimeZone('America/Costa_Rica'));
$event = ['starts_at' => '2026-10-10 18:00:00', 'status' => 'PUBLICADO', 'latitude' => '9.9281', 'longitude' => '-84.0907'];
$response = ['timezone' => 'America/Costa_Rica', 'daily_units' => ['temperature_2m_min' => '°C', 'temperature_2m_max' => '°C', 'precipitation_probability_max' => '%'], 'daily' => ['time' => ['2026-10-10'], 'temperature_2m_min' => [18.5], 'temperature_2m_max' => [26.3], 'precipitation_probability_max' => [70]]];
$calls = 0;
$fetch = static function (float $lat, float $lon) use (&$calls, $response): array { ++$calls; return $response; };
$service = new EventWeather($root, $fetch, $now);
try {
    $result = $service->forEvent($event);
    $assert($result['status'] === 'available' && $result['forecast']['minimum'] === 18.5 && $calls === 1);
    $assert($service->forEvent($event) === $result && $calls === 1);
    $offline = static function (): array { throw new RuntimeException('Error privado de prueba'); };
    $stale = (new EventWeather($root, $offline, $now->modify('+61 minutes')))->forEvent($event);
    $assert($stale['status'] === 'stale' && $stale['forecast'] === $result['forecast']);
    $expired = (new EventWeather($root, $offline, $now->modify('+7 hours')))->forEvent($event);
    $assert($expired['status'] === 'unavailable' && !isset($expired['forecast']));
    $assert(!str_contains(json_encode($expired), 'Error privado'));
    $assert((new EventWeather($root, $fetch, $now, false))->forEvent($event)['status'] === 'disabled');
    foreach ([['starts_at', '2026-10-24 18:00:00', 'out_of_range'], ['starts_at', '2026-10-07 18:00:00', 'not_applicable'], ['starts_at', '2026-10-08 18:00:00', 'not_applicable'], ['status', 'CANCELADO', 'not_applicable'], ['status', 'FINALIZADO', 'not_applicable'], ['latitude', null, 'no_location'], ['longitude', 'http://127.0.0.1/', 'no_location'], ['latitude', '91', 'no_location'], ['longitude', '-181', 'no_location'], ['starts_at', 'fecha-inválida', 'unavailable']] as [$field, $value, $status]) {
        $assert($service->forEvent([...$event, $field => $value])['status'] === $status);
    }
    $assert($calls === 1); // Ningún caso inválido consulta el proveedor.
    $assert(EventWeather::extract($response, '2026-10-09') === null);
    foreach ([['daily_units', 'temperature_2m_min', 'F'], ['daily', 'temperature_2m_max', [null]], ['daily', 'precipitation_probability_max', [101]], ['daily', 'temperature_2m_min', ['<script>']], ['daily', 'temperature_2m_min', [50]], ['daily', 'temperature_2m_max', [INF]]] as [$group, $field, $value]) {
        $invalid = $response;
        $invalid[$group][$field] = $value;
        $assert(EventWeather::extract($invalid, '2026-10-10') === null);
    }
    $invalid = $response; $invalid['timezone'] = 'UTC';
    $assert(EventWeather::extract($invalid, '2026-10-10') === null);
    $assert(EventWeather::extract([], '2026-10-10') === null);
    // Backoff negativo: error externo no causa nueva llamada en cada visita.
    $badCalls = 0;
    $bad = static function () use (&$badCalls): array { ++$badCalls; return []; };
    $negative = new EventWeather($root . '/negative', $bad, $now);
    $assert($negative->forEvent($event)['status'] === 'unavailable');
    $assert($negative->forEvent($event)['status'] === 'unavailable' && $badCalls === 1);
    (new EventWeather($root . '/negative', $bad, $now->modify('+11 minutes')))->forEvent($event);
    $assert($badCalls === 2);
    // Límites globales comparten presupuesto entre coordenadas distintas.
    $quotaCalls = 0;
    $quotaFetch = static function () use (&$quotaCalls, $response): array { ++$quotaCalls; return $response; };
    for ($minute = 0; $minute < 4; ++$minute) {
        $quota = new EventWeather($root . '/quota', $quotaFetch, $now->modify("+$minute minutes"));
        for ($i = 0; $i < 11; ++$i) { $quota->forEvent([...$event, 'latitude' => (string) (10 + $minute + $i / 100)]); }
        $assert($quotaCalls === min(30, ($minute + 1) * 10));
    }
    // Un presupuesto persistente inyectado manda; no hay fallback al archivo local.
    $deniedCalls = 0;
    $deniedFetch = static function () use (&$deniedCalls, $response): array { ++$deniedCalls; return $response; };
    $assert((new EventWeather($root . '/denied', $deniedFetch, $now, budget: static fn (int $time): bool => false))->forEvent($event)['status'] === 'unavailable');
    $assert($deniedCalls === 0);
    $brokenBudget = static function (int $time): bool { throw new RuntimeException('Detalle privado de MySQL'); };
    $broken = (new EventWeather($root . '/broken-budget', $deniedFetch, $now, budget: $brokenBudget))->forEvent($event);
    $assert($broken['status'] === 'unavailable' && $deniedCalls === 0 && !str_contains(json_encode($broken), 'MySQL'));
    $budgetAttempts = 0;
    $allowed = static function (int $time) use (&$budgetAttempts): bool { ++$budgetAttempts; return true; };
    $injected = new EventWeather($root . '/injected', $deniedFetch, $now, budget: $allowed);
    $assert($injected->forEvent($event)['status'] === 'available');
    $injected->forEvent($event);
    $assert($budgetAttempts === 1 && $deniedCalls === 1 && !is_file($root . '/injected/budget.json'));
    // Caché corrupta se ignora, sin emitir HTML/datos externos.
    $key = hash('sha256', '9.9281|-84.0907|2026-10-10');
    file_put_contents($root . '/' . $key . '.json', '{incorrecto');
    $assert($service->forEvent($event)['status'] === 'available' && $calls === 2);
    // Respaldo: las muestras UTC se agrupan por la fecha de Costa Rica.
    $met = ['type' => 'Feature', 'properties' => ['meta' => ['units' => ['air_temperature' => 'celsius']], 'timeseries' => []]];
    foreach ([6 => 20, 12 => 23, 18 => 27, 24 => 22] as $hour => $temperature) {
        $utc = (new DateTimeImmutable('2026-10-10 00:00:00', new DateTimeZone('UTC')))->modify("+$hour hours");
        $met['properties']['timeseries'][] = ['time' => $utc->format('Y-m-d\TH:i:s\Z'), 'data' => ['instant' => ['details' => ['air_temperature' => $temperature]]]];
    }
    $metResult = EventWeather::extract($met, '2026-10-10');
    $assert($metResult['minimum'] === 20 && $metResult['maximum'] === 27 && $metResult['source'] === 'met_norway');
    $assert($metResult['rain_probability'] === null);
    $assert(EventWeather::extract($met, '2026-10-11') === null);
    $partial = $met;
    array_pop($partial['properties']['timeseries']);
    $assert(EventWeather::extract($partial, '2026-10-10') === null);
    $invalid = $met;
    $invalid['properties']['meta']['units']['air_temperature'] = 'fahrenheit';
    $assert(EventWeather::extract($invalid, '2026-10-10') === null);
    foreach ([null, '<script>', INF, 71] as $value) {
        $invalid = $met;
        $invalid['properties']['timeseries'][0]['data']['instant']['details']['air_temperature'] = $value;
        $assert(EventWeather::extract($invalid, '2026-10-10') === null);
    }
    $invalid = $met;
    $invalid['properties']['timeseries'][] = $invalid['properties']['timeseries'][0];
    $assert(EventWeather::extract($invalid, '2026-10-10') === null);
    $invalid = $met;
    $invalid['properties']['timeseries'][0]['time'] = '2026-02-30T06:00:00Z';
    $assert(EventWeather::extract($invalid, '2026-10-10') === null);
    $metCache = $root . '/met-client';
    mkdir($metCache, 0700, true);
    $query = http_build_query(['lat' => 9.9281, 'lon' => -84.0907]);
    $metCachePath = $metCache . '/' . hash('sha256', $query) . '.json';
    file_put_contents($metCachePath, json_encode(['expires' => time() + 3600, 'modified' => '', 'data' => $met]));
    $cachedMetClient = new Eventify\Infrastructure\MetWeatherClient($metCache);
    $assert($cachedMetClient->fetch(9.9281, -84.0907) === $met);
    $assert($cachedMetClient->fetch(9.92811, -84.09071) === $met);
    $backupCalls = 0;
    $backup = static function () use (&$backupCalls, $met): array { ++$backupCalls; return $met; };
    $fallback = new Eventify\Infrastructure\WeatherFallbackClient($offline, $backup);
    $metService = new EventWeather($root . '/met', $fallback->fetch(...), $now);
    $assert($metService->forEvent($event)['forecast'] === $metResult && $backupCalls === 1);
    $assert($metService->forEvent($event)['forecast'] === $metResult && $backupCalls === 1);
    $assert((new EventWeather($root . '/met', $offline, $now->modify('+61 minutes')))->forEvent($event)['status'] === 'stale');
    $assert((new EventWeather($root . '/met', $offline, $now->modify('+7 hours')))->forEvent($event)['status'] === 'unavailable');
    $primary = new Eventify\Infrastructure\WeatherFallbackClient($fetch, $backup);
    $assert($primary->fetch(9.9281, -84.0907) === $response && $backupCalls === 1);
    $bothOffline = new EventWeather($root . '/both-offline', (new Eventify\Infrastructure\WeatherFallbackClient($offline, $offline))->fetch(...), $now);
    $assert($bothOffline->forEvent($event)['status'] === 'unavailable');
    echo "Clima: $checks comprobaciones correctas.\n";
} finally {
    if (is_dir($root)) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) { $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname()); }
        rmdir($root);
    }
}
