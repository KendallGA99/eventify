<?php
declare(strict_types=1);
namespace Eventify\Support;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use InvalidArgumentException;

final class TicketQr
{
    public static function payload(string $ticketCode): string
    {
        if (!preg_match('/\A[a-f0-9]{64}\z/', $ticketCode)) { throw new InvalidArgumentException('Identificador de ticket inválido.'); }
        return 'EVENTIFY:TICKET:' . $ticketCode;
    }

    public static function svg(string $ticketCode): string
    {
        $payload = self::payload($ticketCode);
        require_once PROJECT_ROOT . '/vendor/autoload.php';
        $matrix = Encoder::encode($payload, ErrorCorrectionLevel::M())->getMatrix();
        $width = $matrix->getWidth();
        $size = $width + 8; // Zona silenciosa de cuatro módulos por lado.
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . $size . ' ' . $size . '" width="392" height="392" shape-rendering="crispEdges"><rect width="' . $size . '" height="' . $size . '" fill="#fff"/>';
        for ($y = 0; $y < $width; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                if ($matrix->get($x, $y) === 1) {
                    $svg .= '<rect x="' . ($x + 4) . '" y="' . ($y + 4) . '" width="1" height="1" fill="#000"/>';
                }
            }
        }
        return $svg . '</svg>';
    }
}
