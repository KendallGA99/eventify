<?php
declare(strict_types=1);
namespace Eventify\Domain;

use DateTimeImmutable;
use DateTimeZone;

final class CatalogFilter
{
    public function __construct(
        public readonly string $search,
        public readonly ?int $category,
        public readonly string $from,
        public readonly string $to,
        public readonly string $availability,
        public readonly string $state,
        public readonly int $page,
    ) {}

    public static function fromQuery(array $input): self
    {
        $text = static function (string $key, string $default = '') use ($input): string {
            $value = $input[$key] ?? $default;
            if (!is_string($value) || !preg_match('//u', $value) || preg_match('/[\x00-\x1f\x7f]/u', $value)) {
                throw new BusinessError('Los filtros enviados no son válidos.', 422);
            }
            return trim($value);
        };
        $search = $text('q');
        if (preg_match_all('/./us', $search) > 80) { throw new BusinessError('La búsqueda admite hasta 80 caracteres.', 422); }
        $category = $text('category');
        $page = $text('page', '1');
        foreach ([$category === '' ? '1' : $category, $page] as $number) {
            if (!preg_match('/\A[1-9][0-9]{0,8}\z/', $number)) { throw new BusinessError('Categoría o página no válidas.', 422); }
        }
        if ((int) $page > 10000) { throw new BusinessError('La página solicitada está fuera de rango.', 422); }
        $from = $text('from');
        $to = $text('to');
        foreach ([$from, $to] as $date) {
            if ($date === '') { continue; }
            $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $date, new DateTimeZone('America/Costa_Rica'));
            if (!preg_match('/\A20[0-9]{2}-[0-9]{2}-[0-9]{2}\z/', $date) || !$parsed || $parsed->format('Y-m-d') !== $date) {
                throw new BusinessError('Seleccione fechas válidas entre 2000 y 2099.', 422);
            }
        }
        if ($from !== '' && $to !== '' && $from > $to) { throw new BusinessError('La fecha inicial no puede ser posterior a la final.', 422); }
        $availability = $text('availability', 'all');
        $state = $text('state', 'upcoming');
        if (!in_array($availability, ['all', 'available', 'soldout'], true) || !in_array($state, ['upcoming', 'all', 'cancelled', 'finished'], true)) {
            throw new BusinessError('Seleccione filtros de estado y disponibilidad válidos.', 422);
        }
        return new self($search, $category === '' ? null : (int) $category, $from, $to, $availability, $state, (int) $page);
    }

    public static function utcBoundary(string $date, bool $nextDay = false): string
    {
        $time = new DateTimeImmutable($date . ' 00:00:00', new DateTimeZone('America/Costa_Rica'));
        return ($nextDay ? $time->modify('+1 day') : $time)->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
    }
}
