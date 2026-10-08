<?php
declare(strict_types=1);
namespace Eventify\Infrastructure;

use RuntimeException;

final class WeatherClient
{
    public function fetch(float $latitude, float $longitude): array
    {
        // El destino es fijo. No aceptar host, ruta ni URL enviados por el visitante.
        $url = 'https://api.open-meteo.com/v1/forecast?' . http_build_query([
            'latitude' => $latitude, 'longitude' => $longitude,
            'daily' => 'temperature_2m_max,temperature_2m_min,precipitation_probability_max',
            'timezone' => 'America/Costa_Rica', 'forecast_days' => 16,
            'temperature_unit' => 'celsius',
        ]);
        $context = stream_context_create([
            'http' => ['timeout' => 3, 'follow_location' => 0, 'ignore_errors' => true, 'header' => "Accept: application/json\r\nUser-Agent: Eventify-Academic/0.1\r\n"],
            'ssl' => ['verify_peer' => true, 'verify_peer_name' => true],
        ]);
        $stream = @fopen($url, 'rb', false, $context);
        if ($stream === false) { throw new RuntimeException('Servicio meteorológico no disponible.'); }
        try {
            $meta = stream_get_meta_data($stream);
            $headers = $meta['wrapper_data'] ?? [];
            if (!preg_match('#\AHTTP/\S+ 200(?:\s|$)#', $headers[0] ?? '')) { throw new RuntimeException('Respuesta meteorológica no disponible.'); }
            $body = stream_get_contents($stream, 131073);
            $meta = stream_get_meta_data($stream);
            if ($body === false || strlen($body) > 131072 || ($meta['timed_out'] ?? false)) { throw new RuntimeException('Respuesta meteorológica fuera de límites.'); }
            $data = json_decode($body, true, 32, JSON_THROW_ON_ERROR);
            if (!is_array($data)) { throw new RuntimeException('Respuesta meteorológica no válida.'); }
            return $data;
        } finally { fclose($stream); }
    }
}
