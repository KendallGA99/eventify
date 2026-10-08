<?php
declare(strict_types=1);
namespace Eventify\Domain;

use Closure;
use DateTimeImmutable;
use DateTimeZone;
use Eventify\Infrastructure\WeatherClient;
use Throwable;

final class EventWeather
{
    private readonly Closure $fetch;
    public function __construct(private readonly string $directory, ?Closure $fetch = null, private readonly ?DateTimeImmutable $clock = null, private readonly bool $enabled = true, private readonly ?Closure $budget = null)
    {
        $this->fetch = $fetch ?? (new WeatherClient())->fetch(...);
    }

    public function forEvent(array $event): array
    {
        $none = static fn (string $status, string $message): array => ['status' => $status, 'message' => $message];
        $unavailable = $none('unavailable', 'No pudimos consultar el pronóstico. Puede continuar con su reserva.');
        if (!$this->enabled) { return $none('disabled', 'El pronóstico está desactivado en este entorno. Puede continuar con su reserva.'); }
        $now = ($this->clock ?? new DateTimeImmutable('now'))->setTimezone(new DateTimeZone('America/Costa_Rica'));
        try { $start = new DateTimeImmutable($event['starts_at'], new DateTimeZone('UTC')); $date = $start->setTimezone(new DateTimeZone('America/Costa_Rica'))->format('Y-m-d'); }
        catch (Throwable) { return $unavailable; }
        if (($event['display_status'] ?? $event['status'] ?? '') !== 'PUBLICADO' || $start <= $now) { return $none('not_applicable', 'No hay pronóstico para este evento cancelado o finalizado.'); }
        if ($date > $now->modify('+15 days')->format('Y-m-d')) { return $none('out_of_range', 'El pronóstico estará disponible cuando falten 15 días o menos para el evento.'); }
        foreach (['latitude' => 90, 'longitude' => 180] as $field => $limit) {
            if (!isset($event[$field]) || !is_numeric($event[$field]) || !is_finite((float) $event[$field]) || abs((float) $event[$field]) > $limit) {
                return $none('no_location', 'Este evento todavía no tiene coordenadas para consultar el pronóstico.');
            }
        }
        $latitude = round((float) $event['latitude'], 4);
        $longitude = round((float) $event['longitude'], 4);
        // Sin caché privada no realizar llamadas repetidas sin control.
        if (!is_dir($this->directory) && !@mkdir($this->directory, 0700, true) && !is_dir($this->directory)) { return $unavailable; }
        $key = hash('sha256', "$latitude|$longitude|$date");
        $path = $this->directory . '/' . $key . '.json';
        $time = $now->getTimestamp();
        $cached = $this->read($path);
        if (($cached['data']['date'] ?? $date) !== $date) { $cached = null; }
        if ($this->fresh($cached, $time)) { return $this->result($cached, $time, $unavailable); }
        $lock = @fopen($this->directory . '/' . $key . '.lock', 'c');
        if ($lock === false) { return $unavailable; }
        try {
            if (!flock($lock, LOCK_EX | LOCK_NB)) { return $none('busy', 'El pronóstico se está actualizando. Intente consultarlo nuevamente en unos segundos.'); }
            $cached = $this->read($path);
            if ($this->fresh($cached, $time)) { return $this->result($cached, $time, $unavailable); }
            $record = ['checked_at' => $time, 'success_at' => $cached['success_at'] ?? 0, 'ok' => false, 'data' => $cached['data'] ?? null];
            if ($this->allowRequest($time)) {
                try {
                    $data = self::extract(($this->fetch)($latitude, $longitude), $date);
                    if ($data !== null) { $record = ['checked_at' => $time, 'success_at' => $time, 'ok' => true, 'data' => $data]; }
                } catch (Throwable) { /* No exponer URL/respuesta/trace del proveedor. */ }
            }
            $this->write($path, $record);
            return $this->result($record, $time, $unavailable);
        } finally { flock($lock, LOCK_UN); fclose($lock); }
    }

    public static function extract(array $response, string $date): ?array
    {
        if (($response['timezone'] ?? '') !== 'America/Costa_Rica'
            || ($response['daily_units']['temperature_2m_max'] ?? '') !== '°C'
            || ($response['daily_units']['temperature_2m_min'] ?? '') !== '°C'
            || ($response['daily_units']['precipitation_probability_max'] ?? '') !== '%') { return null; }
        $daily = $response['daily'] ?? null;
        if (!is_array($daily) || !is_array($daily['time'] ?? null)) { return null; }
        $index = array_search($date, $daily['time'], true);
        if ($index === false) { return null; }
        $values = [];
        foreach (['temperature_2m_min' => [-100, 70], 'temperature_2m_max' => [-100, 70], 'precipitation_probability_max' => [0, 100]] as $field => [$minimum, $maximum]) {
            if (!is_array($daily[$field] ?? null)) { return null; }
            $value = $daily[$field][$index] ?? null;
            if ((!is_int($value) && !is_float($value)) || !is_finite((float) $value) || $value < $minimum || $value > $maximum) { return null; }
            $values[$field] = $value;
        }
        if ($values['temperature_2m_min'] > $values['temperature_2m_max']) { return null; }
        return ['date' => $date, 'minimum' => $values['temperature_2m_min'], 'maximum' => $values['temperature_2m_max'], 'rain_probability' => $values['precipitation_probability_max']];
    }

    private function fresh(?array $cache, int $time): bool
    {
        return $cache !== null && $time >= $cache['checked_at'] && $time - $cache['checked_at'] < ($cache['ok'] ? 3600 : 600);
    }

    private function result(?array $cache, int $time, array $fallback): array
    {
        if ($cache === null || $cache['data'] === null || $time < $cache['success_at'] || $time - $cache['success_at'] > 21600) { return $fallback; }
        return ['status' => $cache['ok'] ? 'available' : 'stale', 'message' => $cache['ok'] ? 'Pronóstico diario para la fecha del evento.' : 'No pudimos actualizar el pronóstico. Se muestra la última consulta disponible.', 'checked_at' => gmdate('c', $cache['success_at']), 'forecast' => $cache['data']];
    }

    private function read(string $path): ?array
    {
        $raw = @file_get_contents($path, false, null, 0, 16385);
        if ($raw === false || strlen($raw) > 16384) { return null; }
        $cache = json_decode($raw, true);
        if (!is_array($cache) || !is_int($cache['checked_at'] ?? null) || !is_int($cache['success_at'] ?? null) || !is_bool($cache['ok'] ?? null)) { return null; }
        $data = $cache['data'] ?? null;
        if ($data !== null && (!is_array($data) || !is_string($data['date'] ?? null) || !preg_match('/\A20[0-9]{2}-[0-9]{2}-[0-9]{2}\z/', $data['date']))) { return null; }
        if ($data !== null) {
            foreach (['minimum' => [-100, 70], 'maximum' => [-100, 70], 'rain_probability' => [0, 100]] as $field => [$min, $max]) {
                $value = $data[$field] ?? null;
                if ((!is_int($value) && !is_float($value)) || !is_finite((float) $value) || $value < $min || $value > $max) { return null; }
            }
        }
        return $cache;
    }

    private function write(string $path, array $record): void
    {
        $temporary = $path . '.tmp-' . bin2hex(random_bytes(6));
        if (@file_put_contents($temporary, json_encode($record, JSON_THROW_ON_ERROR), LOCK_EX) !== false) {
            @chmod($temporary, 0600);
            if (!@rename($temporary, $path)) { @unlink($temporary); }
        }
    }

    private function allowRequest(int $time): bool
    {
        if ($this->budget !== null) {
            try { return ($this->budget)($time) === true; }
            catch (Throwable) { return false; }
        }
        // Presupuesto compartido entre eventos: máximo 30/h y 10/min (muy inferior a la cuota externa).
        $path = $this->directory . '/budget.json';
        $lock = @fopen($this->directory . '/budget.lock', 'c');
        if ($lock === false) { return false; }
        try {
            if (!flock($lock, LOCK_EX | LOCK_NB)) { return false; }
            $budget = json_decode((string) @file_get_contents($path), true);
            $hour = intdiv($time, 3600);
            $minute = intdiv($time, 60);
            $hours = is_array($budget) && ($budget['hour'] ?? null) === $hour ? (int) ($budget['hours'] ?? 0) : 0;
            $minutes = is_array($budget) && ($budget['minute'] ?? null) === $minute ? (int) ($budget['minutes'] ?? 0) : 0;
            if ($hours >= 30 || $minutes >= 10) { return false; }
            $record = json_encode(['hour' => $hour, 'minute' => $minute, 'hours' => $hours + 1, 'minutes' => $minutes + 1], JSON_THROW_ON_ERROR);
            return @file_put_contents($path, $record, LOCK_EX) !== false;
        } finally { flock($lock, LOCK_UN); fclose($lock); }
    }
}
