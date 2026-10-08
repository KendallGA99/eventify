<?php
declare(strict_types=1);
require dirname(__DIR__, 2) . '/app/bootstrap.php';
use Eventify\Support\TicketQr;
$code = str_repeat('a1', 32);
$svg = TicketQr::svg($code);
if (TicketQr::payload($code) !== 'EVENTIFY:TICKET:' . $code || !str_contains($svg, '<svg ') || substr_count($svg, '<rect ') < 100 || str_contains($svg, '<script')) { exit(1); }
foreach (['', str_repeat('a', 63), str_repeat('G', 64), '<script>'] as $invalid) {
    try { TicketQr::payload($invalid); exit(1); } catch (InvalidArgumentException $exception) {}
}
echo "QR: generación SVG y cuatro identificadores inválidos comprobados.\n";
