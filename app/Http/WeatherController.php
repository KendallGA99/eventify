<?php
declare(strict_types=1);
namespace Eventify\Http;

use Eventify\Domain\EventWeather;
use Eventify\Infrastructure\CatalogRepository;
use Eventify\Infrastructure\Database;
use Eventify\Infrastructure\WeatherBudget;
use Eventify\Support\Config;

final class WeatherController
{
    public function respond(string $slug): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        $pdo = Database::connect();
        $event = (new CatalogRepository($pdo))->find($slug);
        if ($event === null) {
            http_response_code(404);
            echo json_encode(['message' => 'No encontramos este evento público.'], JSON_THROW_ON_ERROR);
            return;
        }
        $enabled = Config::get('WEATHER_ENABLED', Config::get('APP_ENV') === 'test' ? 'false' : 'true') === 'true';
        $budget = new WeatherBudget($pdo);
        $weather = new EventWeather(PROJECT_ROOT . '/storage/weather', enabled: $enabled, budget: $budget->allowRequest(...));
        echo json_encode($weather->forEvent($event), JSON_THROW_ON_ERROR);
    }
}
