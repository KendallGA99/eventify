<?php
declare(strict_types=1);
namespace Eventify\Domain;

use DateTimeImmutable;
use DateTimeZone;

final class AdminInput
{
    public const IMAGES = ['/assets/icon.svg' => 'Eventify', '/assets/images/tecnologia.svg' => 'Tecnología', '/assets/images/cultura.svg' => 'Cultura', '/assets/images/experiencias.svg' => 'Experiencias'];

    public static function text(array $input, string $key, int $maximum, bool $multiline = false): string
    {
        $value = $input[$key] ?? '';
        if (!is_string($value) || !preg_match('//u', $value) || preg_match('/[\x00-\x08\x0b\x0c\x0e-\x1f\x7f]/u', $value)) { throw new BusinessError('Revise los campos del formulario.'); }
        if (!$multiline && preg_match('/[\r\n\t]/', $value)) { throw new BusinessError('Este campo debe ocupar una sola línea.'); }
        $value = trim($value);
        if ($value === '' || preg_match_all('/./us', $value) > $maximum) { throw new BusinessError('Complete los campos respetando sus longitudes máximas.'); }
        return $value;
    }

    public static function category(array $input): array
    {
        $name = self::text($input, 'name', 120);
        $slug = self::slug($input, 120);
        $active = $input['is_active'] ?? '1';
        if (!in_array($active, ['0', '1'], true)) { throw new BusinessError('Seleccione un estado de categoría válido.'); }
        return ['name' => $name, 'slug' => $slug, 'is_active' => (int) $active];
    }

    private static function slug(array $input, int $maximum): string
    {
        $slug = self::text($input, 'slug', $maximum);
        if (!preg_match('/\A[a-z0-9]+(?:-[a-z0-9]+)*\z/', $slug)) { throw new BusinessError('El identificador admite minúsculas, números y guiones entre palabras.'); }
        return $slug;
    }

    public static function event(array $input): array
    {
        $price = $input['price'] ?? null;
        if (!is_string($price) || !preg_match('/\A(?:0|[1-9][0-9]{0,9})(?:[.,][0-9]{1,2})?\z/', trim($price))) { throw new BusinessError('El precio debe ser positivo o cero, sin separadores de miles y con hasta dos decimales.'); }
        $price = explode('.', str_replace(',', '.', trim($price)));
        $price = $price[0] . '.' . str_pad($price[1] ?? '', 2, '0');
        $start = self::text($input, 'starts_at', 19);
        $format = strlen($start) === 19 ? 'Y-m-d\TH:i:s' : 'Y-m-d\TH:i';
        $date = DateTimeImmutable::createFromFormat('!' . $format, $start, new DateTimeZone('America/Costa_Rica'));
        if (!$date || !preg_match('/\A20[0-9]{2}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}(?::[0-9]{2})?\z/', $start) || $date->format($format) !== $start) { throw new BusinessError('Seleccione fecha y hora válidas entre 2000 y 2099.'); }
        $image = $input['image_path'] ?? null;
        if (!is_string($image) || !isset(self::IMAGES[$image])) { throw new BusinessError('Seleccione una imagen de la galería.'); }
        $status = $input['status'] ?? null;
        if (!in_array($status, ['BORRADOR', 'PUBLICADO'], true)) { throw new BusinessError('Edite como borrador o publicado; cancelar y finalizar tienen acciones separadas.'); }
        $coordinates = [];
        foreach (['latitude' => 90, 'longitude' => 180] as $key => $limit) {
            $value = $input[$key] ?? '';
            if (!is_string($value)) { throw new BusinessError('Revise las coordenadas.'); }
            $value = trim($value);
            if ($value !== '' && (!preg_match('/\A-?(?:0|[1-9][0-9]{0,2})(?:\.[0-9]{1,6})?\z/', $value) || abs((float) $value) > $limit)) { throw new BusinessError('Las coordenadas deben ser números decimales dentro del rango válido.'); }
            $coordinates[$key] = $value === '' ? null : $value;
        }
        if (($coordinates['latitude'] === null) !== ($coordinates['longitude'] === null)) { throw new BusinessError('Complete ambas coordenadas o deje ambas vacías.'); }
        return ['name' => self::text($input, 'name', 180), 'slug' => self::slug($input, 160), 'description' => self::text($input, 'description', 10000, true), 'category_id' => ReservationService::quantity($input['category_id'] ?? null), 'image_path' => $image, 'starts_at' => $date->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s'), 'location' => self::text($input, 'location', 255), 'capacity' => ReservationService::quantity($input['capacity'] ?? null), 'price' => $price, 'status' => $status, ...$coordinates];
    }
}
