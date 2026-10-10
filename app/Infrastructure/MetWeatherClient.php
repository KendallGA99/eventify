<?php

declare(strict_types=1);

namespace Eventify\Infrastructure;

use RuntimeException;

final class MetWeatherClient
{
    public function __construct(private readonly string $directory) {}

    public function fetch(float $latitude, float $longitude): array
    {
        $query = http_build_query(['lat' => round($latitude, 4), 'lon' => round($longitude, 4)]);
        $url = 'https://api.met.no/weatherapi/locationforecast/2.0/compact?' . $query;
        if (!is_dir($this->directory) && !@mkdir($this->directory, 0700, true) && !is_dir($this->directory)) {
            throw new RuntimeException('No se pudo preparar la caché meteorológica.');
        }
        // Compartir la respuesta entre eventos de la misma ubicación evita consultas repetidas.
        $path = $this->directory . '/' . hash('sha256', $query) . '.json';
        $lock = @fopen($path . '.lock', 'c');
        if ($lock === false) {
            throw new RuntimeException('Caché meteorológica no disponible.');
        }
        try {
            if (!flock($lock, LOCK_EX | LOCK_NB)) {
                throw new RuntimeException('Consulta meteorológica en curso.');
            }
            $raw = @file_get_contents($path, false, null, 0, 262145);
            $cache = is_string($raw) && strlen($raw) <= 262144 ? json_decode($raw, true) : null;
            if (is_array($cache) && is_array($cache['data'] ?? null) && ($cache['expires'] ?? 0) > time()) {
                return $cache['data'];
            }
            $headers = "Accept: application/json\r\nAccept-Encoding: gzip\r\nUser-Agent: Eventify-Academic/0.1 https://eventify-academico.onrender.com\r\n";
            $modified = $cache['modified'] ?? '';
            if (is_string($modified) && preg_match('/\A[A-Za-z]{3}, [0-9]{2} [A-Za-z]{3} [0-9]{4} [0-9:]{8} GMT\z/', $modified)) {
                $headers .= 'If-Modified-Since: ' . $modified . "\r\n";
            }
            // Solo se admiten redirecciones HTTPS al mismo recurso del proveedor.
            for ($redirect = 0; $redirect <= 1; ++$redirect) {
                $context = stream_context_create([
                    'http' => ['timeout' => 3, 'follow_location' => 0, 'ignore_errors' => true, 'header' => $headers],
                    'ssl' => ['verify_peer' => true, 'verify_peer_name' => true],
                ]);
                $stream = @fopen($url, 'rb', false, $context);
                if ($stream === false) {
                    error_log('eventify_weather provider=met_norway reason=request_open');
                    throw new RuntimeException('Proveedor de respaldo no disponible.');
                }
                try {
                    $meta = stream_get_meta_data($stream);
                    $responseHeaders = $meta['wrapper_data'] ?? [];
                    preg_match('#\AHTTP/\S+ ([0-9]{3})(?:\s|$)#', $responseHeaders[0] ?? '', $statusMatch);
                    $status = (int) ($statusMatch[1] ?? 0);
                    $fields = [];
                    foreach ($responseHeaders as $header) {
                        if (str_contains($header, ':')) {
                            [$name, $value] = explode(':', $header, 2);
                            $fields[strtolower(trim($name))] = trim($value);
                        }
                    }
                    if (in_array($status, [301, 302, 307, 308], true) && $redirect === 0) {
                        $location = $fields['location'] ?? '';
                        if (!preg_match('#\Ahttps://api\.met\.no/weatherapi/locationforecast/2\.0/compact\?[^\s\r\n]+\z#', $location)) {
                            throw new RuntimeException('Redirección meteorológica no permitida.');
                        }
                        $url = $location;
                        continue;
                    }
                    if ($status === 304 && is_array($cache['data'] ?? null)) {
                        $data = $cache['data'];
                    } elseif ($status === 200) {
                        $body = stream_get_contents($stream, 131073);
                        $meta = stream_get_meta_data($stream);
                        if ($body === false || strlen($body) > 131072 || ($meta['timed_out'] ?? false)) {
                            throw new RuntimeException('Respuesta meteorológica fuera de límites.');
                        }
                        if (strtolower($fields['content-encoding'] ?? '') === 'gzip') {
                            $body = @gzdecode($body, 131073);
                        }
                        if (!is_string($body) || strlen($body) > 131072) {
                            throw new RuntimeException('Respuesta meteorológica fuera de límites.');
                        }
                        $data = json_decode($body, true, 32, JSON_THROW_ON_ERROR);
                        if (!is_array($data) || ($data['type'] ?? '') !== 'Feature') {
                            throw new RuntimeException('Respuesta meteorológica no válida.');
                        }
                    } else {
                        error_log('eventify_weather provider=met_norway reason=http_status status=' . $status);
                        throw new RuntimeException('Proveedor de respaldo no disponible.');
                    }
                    $expires = strtotime($fields['expires'] ?? '') ?: time() + 3600;
                    $record = ['expires' => max(time() + 3600, $expires), 'modified' => $fields['last-modified'] ?? $modified, 'data' => $data];
                    $temporary = $path . '.tmp-' . bin2hex(random_bytes(6));
                    if (@file_put_contents($temporary, json_encode($record, JSON_THROW_ON_ERROR), LOCK_EX) !== false) {
                        @chmod($temporary, 0600);
                        if (!@rename($temporary, $path)) { @unlink($temporary); }
                    }
                    return $data;
                } finally {
                    fclose($stream);
                }
            }
            throw new RuntimeException('Demasiadas redirecciones meteorológicas.');
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }
}
