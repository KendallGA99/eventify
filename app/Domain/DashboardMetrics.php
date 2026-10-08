<?php
declare(strict_types=1);
namespace Eventify\Domain;

use InvalidArgumentException;

final class DashboardMetrics
{
    public static function occupation(string $entries, string $capacity): string
    {
        foreach ([$entries, $capacity] as $value) {
            if (!preg_match('/\A[0-9]+\z/', $value) || !is_finite((float) $value)) { throw new InvalidArgumentException('Indicador numérico inválido.'); }
        }
        // Solo el porcentaje se redondea; los importes monetarios permanecen DECIMAL/string.
        return number_format((float) $capacity === 0.0 ? 0 : (float) $entries / (float) $capacity * 100, 2, ',', '');
    }
}
