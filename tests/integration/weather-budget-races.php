<?php
declare(strict_types=1);

// Solo modifica la fila meteorológica de la base efímera de integración.
if (Eventify\Support\Config::get('APP_ENV') !== 'test' || Eventify\Support\Config::get('DB_DATABASE') !== 'eventify_test') {
    throw new RuntimeException('Carreras requieren base aislada.');
}
$waitBudgetFile = static function (string $path): void {
    $deadline = microtime(true) + 8;
    while (!is_file($path)) {
        if (microtime(true) > $deadline) { throw new RuntimeException('Barrera de presupuesto vencida.'); }
        usleep(10000);
    }
};
$savedBudget = $pdo->query("SELECT hour_window, minute_window, hour_requests, minute_requests FROM weather_budget WHERE scope = 'open_meteo'")->fetch();
$budgetUpdate = $pdo->prepare("UPDATE weather_budget SET hour_window = ?, minute_window = ?, hour_requests = ?, minute_requests = ? WHERE scope = 'open_meteo'");
try {
    foreach (['minuto' => [12, 9], 'hora' => [29, 2]] as $case => [$hourRequests, $minuteRequests]) {
        $label = 'Concurrencia de presupuesto por ' . $case;
        $time = 72000;
        $budgetUpdate->execute([20, 1200, $hourRequests, $minuteRequests]);
        $directory = sys_get_temp_dir() . '/eventify-budget-race-' . bin2hex(random_bytes(8));
        if (!mkdir($directory, 0700)) { throw new RuntimeException('Directorio de carrera no disponible.'); }
        $processes = [];
        $pipesList = [];
        try {
            // Retener la fila hace que ambas conexiones intenten consumir antes de liberarla.
            $pdo->beginTransaction();
            $pdo->query("SELECT scope FROM weather_budget WHERE scope = 'open_meteo' FOR UPDATE")->fetch();
            foreach ([0, 1] as $index) {
                $process = proc_open([PHP_BINARY, __DIR__ . '/weather-budget-worker.php', $directory, (string) $index, (string) $time], [['file', '/dev/null', 'r'], ['pipe', 'w'], ['pipe', 'w']], $pipes, PROJECT_ROOT);
                if (!is_resource($process)) { throw new RuntimeException('Proceso de presupuesto no disponible.'); }
                $processes[$index] = $process;
                $pipesList[$index] = $pipes;
                $waitBudgetFile($directory . '/ready-' . $index);
            }
            touch($directory . '/go');
            foreach ([0, 1] as $index) { $waitBudgetFile($directory . '/attempt-' . $index); }
            usleep(150000);
            $assert(proc_get_status($processes[0])['running'] && proc_get_status($processes[1])['running'], $label . ': conexiones solapadas bajo lock');
            $pdo->commit();
            $results = [];
            foreach ($pipesList as $index => $pipes) {
                stream_set_timeout($pipes[1], 15);
                stream_set_timeout($pipes[2], 15);
                $results[] = stream_get_contents($pipes[1]);
                $error = stream_get_contents($pipes[2]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                $pipesList[$index] = [];
                $exit = proc_close($processes[$index]);
                $processes[$index] = null;
                if ($exit !== 0 || $error !== '') { throw new RuntimeException('Proceso de presupuesto falló.'); }
            }
            sort($results);
            $assert($results === ['ALLOW', 'DENY'], $label . ': exactamente un consumo del último permiso');
            $actual = $pdo->query("SELECT hour_window, minute_window, hour_requests, minute_requests FROM weather_budget WHERE scope = 'open_meteo'")->fetch();
            $actual = array_map('intval', $actual);
            $assert(array_values($actual) === [20, 1200, $hourRequests + 1, $minuteRequests + 1], $label . ': contadores persistidos sin sobreconsumo');
            $assert(!(new Eventify\Infrastructure\WeatherBudget(Eventify\Infrastructure\Database::connect()))->allowRequest($time), $label . ': otra conexión conserva el límite');
        } finally {
            if ($pdo->inTransaction()) { $pdo->rollBack(); }
            foreach ($pipesList as $pipes) { foreach ($pipes as $pipe) { if (is_resource($pipe)) { fclose($pipe); } } }
            foreach ($processes as $process) { if (is_resource($process)) { proc_terminate($process); proc_close($process); } }
            foreach (glob($directory . '/*') ?: [] as $file) { unlink($file); }
            rmdir($directory);
        }
    }
} finally {
    $budgetUpdate->execute(array_values($savedBudget));
}
