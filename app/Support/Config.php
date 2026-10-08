<?php

declare(strict_types=1);

namespace Eventify\Support;

use RuntimeException;

final class Config
{
    public static function get(string $key, string $default = ''): string
    {
        $value = getenv($key);
        return $value === false ? $default : $value;
    }

    public static function required(string $key): string
    {
        $value = self::get($key);
        if (trim($value) === '' || str_starts_with($value, '__GENERATE_')) {
            throw new RuntimeException('La configuración requerida no está disponible.');
        }
        return $value;
    }
}
