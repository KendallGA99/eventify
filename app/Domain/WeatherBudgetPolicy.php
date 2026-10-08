<?php
declare(strict_types=1);
namespace Eventify\Domain;

final class WeatherBudgetPolicy
{
    /** Reserva un intento; null indica límite o reloj/datos inconsistentes. */
    public static function consume(array $record, int $time): ?array
    {
        if ($time < 0) { return null; }
        $hour = intdiv($time, 3600);
        $minute = intdiv($time, 60);
        foreach (['hour_window', 'minute_window', 'hour_requests', 'minute_requests'] as $field) {
            if (!isset($record[$field]) || !is_int($record[$field]) || $record[$field] < 0) { return null; }
        }
        if ($record['hour_requests'] > 30 || $record['minute_requests'] > 10
            || $hour < $record['hour_window'] || $minute < $record['minute_window']) { return null; }
        $hours = $hour === $record['hour_window'] ? $record['hour_requests'] : 0;
        $minutes = $minute === $record['minute_window'] ? $record['minute_requests'] : 0;
        if ($hours >= 30 || $minutes >= 10) { return null; }
        return ['hour_window' => $hour, 'minute_window' => $minute,
            'hour_requests' => $hours + 1, 'minute_requests' => $minutes + 1];
    }
}
