<?php
declare(strict_types=1);
require dirname(__DIR__, 2) . '/app/bootstrap.php';
use Eventify\Domain\AdminReservationFilter;
use Eventify\Domain\DashboardMetrics;
use Eventify\Domain\BusinessError;
$checks = 0;
$assert = static function (bool $ok) use (&$checks): void { if (!$ok) { throw new RuntimeException('Indicadores/filtros incorrectos.'); } ++$checks; };
foreach ([['0','0','0,00'], ['0','100','0,00'], ['11','102','10,78'], ['21','202','10,40'], ['100','100','100,00'], ['101','100','101,00'], ['4294967295','8589934590','50,00']] as [$entries,$capacity,$expected]) { $assert(DashboardMetrics::occupation($entries,$capacity) === $expected); }
foreach (['-1','1.5','1e3','',str_repeat('9',400)] as $invalid) { $rejected=false; try { DashboardMetrics::occupation($invalid,'100'); } catch (InvalidArgumentException) { $rejected=true; } $assert($rejected); }
$f = AdminReservationFilter::fromQuery([]); $assert($f->event === '' && $f->user === '' && $f->state === '' && $f->page === 1);
$f = AdminReservationFilter::fromQuery(['event'=>' SQL 100%_! ', 'user'=>' persona@eventify.test ', 'state'=>'CANCELADA','page'=>'2']);
$assert($f->event === 'SQL 100%_!' && $f->user === 'persona@eventify.test' && $f->state === 'CANCELADA' && $f->page === 2);
foreach ([['event'=>[]],['user'=>[]],['state'=>'PUBLICADO'],['state'=>[]],['event'=>str_repeat('á',81)],['user'=>"x\n"],['event'=>"\xff"],['page'=>'0'],['page'=>'10001'],['page'=>[]],['page'=>'1.5']] as $input) { $rejected=false; try { AdminReservationFilter::fromQuery($input); } catch (BusinessError) { $rejected=true; } $assert($rejected); }
echo "Indicadores y filtros administrativos: $checks comprobaciones correctas.\n";
