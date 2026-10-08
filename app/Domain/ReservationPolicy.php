<?php

declare(strict_types=1);

namespace Eventify\Domain;

use DateTimeImmutable;
use InvalidArgumentException;

final class ReservationPolicy
{
    public static function available(int $capacity, int $reserved): int
    {
        if ($capacity <= 0 || $reserved < 0 || $reserved > $capacity) {
            throw new InvalidArgumentException('Los datos de capacidad no son consistentes.');
        }
        return $capacity - $reserved;
    }

    public static function isLastSpaces(int $capacity, int $available): bool
    {
        if ($capacity <= 0 || $available < 0 || $available > $capacity) {
            throw new InvalidArgumentException('Los datos de disponibilidad no son consistentes.');
        }
        return $available <= $capacity / 5;
    }

    public static function canCancel(DateTimeImmutable $startsAt, DateTimeImmutable $now): bool
    {
        return $startsAt->getTimestamp() - $now->getTimestamp() > 86400;
    }

    public static function canReserve(string $status, DateTimeImmutable $startsAt, DateTimeImmutable $now): bool
    {
        return $status === 'PUBLICADO' && $startsAt > $now;
    }
}
