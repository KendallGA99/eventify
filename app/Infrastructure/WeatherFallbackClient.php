<?php

declare(strict_types=1);

namespace Eventify\Infrastructure;

use Closure;
use Throwable;

final class WeatherFallbackClient
{
    public function __construct(private readonly Closure $primary, private readonly Closure $backup) {}

    public function fetch(float $latitude, float $longitude): array
    {
        try {
            return ($this->primary)($latitude, $longitude);
        } catch (Throwable) {
            // El respaldo es otro proveedor; no repetimos la llamada rechazada ni cambiamos de IP.
            return ($this->backup)($latitude, $longitude);
        }
    }
}
