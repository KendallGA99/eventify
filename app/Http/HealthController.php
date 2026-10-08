<?php

declare(strict_types=1);

namespace Eventify\Http;

use Eventify\Infrastructure\Database;
use Throwable;

final class HealthController
{
    public function ready(): bool
    {
        try {
            return (int) Database::connect()->query('SELECT 1')->fetchColumn() === 1;
        } catch (Throwable) {
            return false;
        }
    }

    public function respond(): void
    {
        $ready = $this->ready();
        http_response_code($ready ? 200 : 503);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode(['status' => $ready ? 'ok' : 'unavailable'], JSON_THROW_ON_ERROR);
    }
}
