<?php
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$date = static fn (string $value): string => (new DateTimeImmutable($value, new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/Costa_Rica'))->format('d/m/Y · H:i');
$money = static fn (string $value): string => Eventify\Support\Format::money($value);
// Solo se utiliza al renderizar páginas del catálogo.
$filter = $filter ?? null;
$pageUrl = static function (int $number) use ($filter): string {
    return '/eventos?' . http_build_query(['q' => $filter->search, 'category' => $filter->category ?? '', 'from' => $filter->from, 'to' => $filter->to, 'availability' => $filter->availability, 'state' => $filter->state, 'page' => $number]);
};
