<?php
declare(strict_types=1);
namespace Eventify\Support;
use InvalidArgumentException;
final class Format
{
    public static function money(string $decimal): string
    {
        if (!preg_match('/\A([0-9]+)(?:\.([0-9]{1,2}))?\z/', $decimal, $parts)) { throw new InvalidArgumentException('Importe decimal inválido.'); }
        return '₡' . preg_replace('/\B(?=([0-9]{3})+(?![0-9]))/', '.', $parts[1]) . ',' . str_pad($parts[2] ?? '', 2, '0');
    }
}
