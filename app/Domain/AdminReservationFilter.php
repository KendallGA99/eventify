<?php
declare(strict_types=1);
namespace Eventify\Domain;

final class AdminReservationFilter
{
    public function __construct(public readonly string $event, public readonly string $user, public readonly string $state, public readonly int $page) {}
    public static function fromQuery(array $input): self
    {
        $text = static function (string $key) use ($input): string {
            $value = $input[$key] ?? '';
            if (!is_string($value) || !preg_match('//u', $value) || preg_match('/[\x00-\x1f\x7f]/u', $value) || preg_match_all('/./us', $value) > 80) { throw new BusinessError('Revise los filtros; cada búsqueda admite hasta 80 caracteres.'); }
            return trim($value);
        };
        $state = $text('state');
        if (!in_array($state, ['', 'CONFIRMADA', 'CANCELADA'], true)) { throw new BusinessError('Seleccione un estado de reserva válido.'); }
        $page = ReservationService::quantity($input['page'] ?? '1');
        if ($page > 10000) { throw new BusinessError('La página solicitada está fuera de rango.'); }
        return new self($text('event'), $text('user'), $state, $page);
    }
}
