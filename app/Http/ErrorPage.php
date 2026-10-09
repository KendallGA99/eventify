<?php
declare(strict_types=1);

namespace Eventify\Http;

final class ErrorPage
{
    public static function respond(int $status, string $title, string $message, string $reference = ''): void
    {
        http_response_code($status);
        header('Content-Type: text/html; charset=utf-8');
        header('Cache-Control: no-store');
        require PROJECT_ROOT . '/app/Views/error.php';
    }
}
