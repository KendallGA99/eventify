<?php

declare(strict_types=1);

namespace Eventify\Domain;

use DateTimeImmutable;
use DateTimeZone;

final class MetForecast
{
    public static function extract(array $response, string $date): ?array
    {
        if (($response['properties']['meta']['units']['air_temperature'] ?? '') !== 'celsius') {
            return null;
        }
        $series = $response['properties']['timeseries'] ?? null;
        if (!is_array($series) || count($series) > 500) { return null; }
        $samples = [];
        foreach ($series as $entry) {
            $text = $entry['time'] ?? '';
            if (!is_string($text) || !preg_match('/\A20[0-9]{2}-[0-9]{2}-[0-9]{2}T[0-9]{2}:00:00Z\z/', $text)) { return null; }
            $utc = DateTimeImmutable::createFromFormat('!Y-m-d\TH:i:s\Z', $text, new DateTimeZone('UTC'));
            if ($utc === false || $utc->format('Y-m-d\TH:i:s\Z') !== $text) { return null; }
            $local = $utc->setTimezone(new DateTimeZone('America/Costa_Rica'));
            if ($local->format('Y-m-d') !== $date) { continue; }
            $value = $entry['data']['instant']['details']['air_temperature'] ?? null;
            if ((!is_int($value) && !is_float($value)) || !is_finite((float) $value) || $value < -100 || $value > 70) { return null; }
            $hour = (int) $local->format('H');
            if (isset($samples[$hour])) { return null; }
            $samples[$hour] = $value;
        }
        ksort($samples);
        $hours = array_keys($samples);
        // No presentar medio día o una muestra aislada como si cubriera toda la fecha.
        if (count($hours) < 4 || $hours[0] > 6 || end($hours) < 18) { return null; }
        for ($index = 1; $index < count($hours); ++$index) {
            if ($hours[$index] - $hours[$index - 1] > 6) { return null; }
        }
        return ['date' => $date, 'minimum' => min($samples), 'maximum' => max($samples), 'rain_probability' => null, 'source' => 'met_norway'];
    }
}
