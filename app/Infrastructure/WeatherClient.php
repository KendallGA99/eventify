<?php

declare(strict_types=1);

namespace Eventify\Infrastructure;

use JsonException;
use RuntimeException;

final class WeatherClient
{
    public function fetch(float $latitude, float $longitude): array
    {
        // Usamos un destino fijo para que el visitante no pueda elegir otro servidor.
        $url = 'https://api.open-meteo.com/v1/forecast?' . http_build_query([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'daily' => 'temperature_2m_max,temperature_2m_min,precipitation_probability_max',
            'timezone' => 'America/Costa_Rica',
            'forecast_days' => 16,
            'temperature_unit' => 'celsius',
        ]);
        $context = stream_context_create([
            'http' => [
                'timeout' => 3,
                'follow_location' => 0,
                'ignore_errors' => true,
                'header' => "Accept: application/json\r\nUser-Agent: Eventify-Academic/0.1\r\n",
            ],
            'ssl' => ['verify_peer' => true, 'verify_peer_name' => true],
        ]);
        $stream = @fopen($url, 'rb', false, $context);
        if ($stream === false) {
            error_log('eventify_weather reason=request_open');
            throw new RuntimeException('Servicio meteorológico no disponible.');
        }

        try {
            $meta = stream_get_meta_data($stream);
            $headers = $meta['wrapper_data'] ?? [];
            if (!preg_match('#\AHTTP/\S+ 200(?:\s|$)#', $headers[0] ?? '')) {
                // Solo registramos el código HTTP, sin coordenadas ni respuesta del proveedor.
                preg_match('#\AHTTP/\S+ ([0-9]{3})(?:\s|$)#', $headers[0] ?? '', $status);
                error_log('eventify_weather reason=http_status status=' . (int) ($status[1] ?? 0));
                throw new RuntimeException('Respuesta meteorológica no disponible.');
            }

            // Un byte adicional permite detectar respuestas mayores al límite de 128 KiB.
            $body = stream_get_contents($stream, 131073);
            $meta = stream_get_meta_data($stream);
            if ($body === false || strlen($body) > 131072 || ($meta['timed_out'] ?? false)) {
                error_log('eventify_weather reason=response_limits');
                throw new RuntimeException('Respuesta meteorológica fuera de límites.');
            }

            try {
                $data = json_decode($body, true, 32, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                error_log('eventify_weather reason=json_format');
                throw new RuntimeException('Respuesta meteorológica no válida.');
            }
            if (!is_array($data)) {
                error_log('eventify_weather reason=payload_type');
                throw new RuntimeException('Respuesta meteorológica no válida.');
            }
            return $data;
        } finally {
            fclose($stream);
        }
    }
}
