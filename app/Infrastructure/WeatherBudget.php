<?php
declare(strict_types=1);
namespace Eventify\Infrastructure;

use Eventify\Domain\WeatherBudgetPolicy;
use PDO;
use Throwable;

final class WeatherBudget
{
    public function __construct(private readonly PDO $pdo) {}

    public function allowRequest(int $time): bool
    {
        if ($this->pdo->inTransaction() || $time < 0) { return false; }
        try {
            // El contador se guarda en MySQL para que un reinicio de la web
            // no reinicie también el límite de llamadas al proveedor.
            $this->pdo->beginTransaction();
            $this->pdo->exec("INSERT IGNORE INTO weather_budget (scope) VALUES ('open_meteo')");
            $record = $this->pdo->query("SELECT hour_window, minute_window, hour_requests, minute_requests FROM weather_budget WHERE scope = 'open_meteo' FOR UPDATE")->fetch();
            if (!is_array($record)) { $this->pdo->rollBack(); return false; }
            // PDO puede devolver enteros como cadenas según el driver.
            foreach ($record as $field => $value) { $record[$field] = (int) $value; }
            $next = WeatherBudgetPolicy::consume($record, $time);
            if ($next === null) { $this->pdo->rollBack(); return false; }
            $update = $this->pdo->prepare("UPDATE weather_budget SET hour_window = ?, minute_window = ?, hour_requests = ?, minute_requests = ? WHERE scope = 'open_meteo'");
            $update->execute(array_values($next));
            $this->pdo->commit();
            // Contamos el intento antes de llamar: una respuesta fallida también usa cuota.
            return true;
        } catch (Throwable) {
            try { if ($this->pdo->inTransaction()) { $this->pdo->rollBack(); } }
            catch (Throwable) { /* Conexión caída: mantener denegada la llamada externa. */ }
            return false;
        }
    }
}
