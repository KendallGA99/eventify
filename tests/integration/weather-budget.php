<?php
declare(strict_types=1);
// Incluido por database.php después de migrar la base efímera eventify_test.
use Eventify\Infrastructure\Database;
use Eventify\Infrastructure\WeatherBudget;
$time = 36000;
for ($minute = 0; $minute < 3; ++$minute) {
    for ($attempt = 0; $attempt < 10; ++$attempt) {
        // Conexión nueva: simula otra petición/recreación del proceso PHP.
        $budget = new WeatherBudget(Database::connect());
        $assert($budget->allowRequest($time + $minute * 60), 'Presupuesto persistente permite intento');
    }
    $assert(!(new WeatherBudget(Database::connect()))->allowRequest($time + $minute * 60), 'Presupuesto persistente aplica máximo por minuto');
}
$assert(!(new WeatherBudget(Database::connect()))->allowRequest($time + 180), 'Minuto nuevo conserva máximo horario');
$assert((new WeatherBudget(Database::connect()))->allowRequest($time + 3600), 'Ventana nueva renueva presupuesto');
$assert(!(new WeatherBudget(Database::connect()))->allowRequest($time), 'Reloj atrasado no renueva presupuesto');
$assert((int) $pdo->query("SELECT COUNT(*) FROM weather_budget WHERE scope = 'open_meteo'")->fetchColumn() === 1, 'Presupuesto global usa una sola fila');
$pdo->beginTransaction();
$assert(!(new WeatherBudget($pdo))->allowRequest($time + 3601) && $pdo->inTransaction(), 'Presupuesto no altera transacción ajena');
$pdo->rollBack();
$assert(!(new WeatherBudget($pdo))->allowRequest(-1), 'Presupuesto rechaza fecha negativa');
// Fallo de almacenamiento provocado únicamente en la base efímera de la suite.
$pdo->exec('RENAME TABLE weather_budget TO weather_budget_unavailable_test');
try {
    $assert(!(new WeatherBudget($pdo))->allowRequest($time + 3601), 'Base de presupuesto indisponible deniega llamada');
    $assert(!$pdo->inTransaction(), 'Fallo de presupuesto no deja transacción abierta');
} finally {
    $pdo->exec('RENAME TABLE weather_budget_unavailable_test TO weather_budget');
}
