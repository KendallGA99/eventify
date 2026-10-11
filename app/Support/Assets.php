<?php
declare(strict_types=1);

namespace Eventify\Support;

use InvalidArgumentException;
use RuntimeException;

final class Assets
{
    public static function url(string $path): string
    {
        if (!preg_match('~\A/assets/[a-zA-Z0-9/._-]+\z~', $path) || str_contains($path, '..')) {
            throw new InvalidArgumentException('Ruta de recurso inválida.');
        }
        $hash = hash_file('sha256', PROJECT_ROOT . '/public' . $path);
        if ($hash === false) {
            throw new RuntimeException('Recurso no disponible.');
        }
        // El enlace cambia cuando cambia el archivo; las versiones iguales reutilizan la caché.
        return $path . '?v=' . substr($hash, 0, 12);
    }
}
