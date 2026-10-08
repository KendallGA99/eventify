<?php
declare(strict_types=1);
require dirname(__DIR__, 2) . '/app/bootstrap.php';
use Eventify\Domain\CatalogFilter;
use Eventify\Domain\BusinessError;
$checks = 0;
$assert = static function (bool $ok) use (&$checks): void {
    if (!$ok) { fwrite(STDERR, "Falló la validación de filtros.\n"); exit(1); }
    ++$checks;
};
$filter = CatalogFilter::fromQuery([]);
$assert($filter->state === 'upcoming' && $filter->page === 1 && $filter->category === null);
$filter = CatalogFilter::fromQuery(['q' => '  Música  ', 'category' => '12', 'from' => '2028-02-29', 'to' => '2028-02-29', 'availability' => 'soldout', 'state' => 'all', 'page' => '2']);
$assert($filter->search === 'Música' && $filter->category === 12 && $filter->page === 2);
$assert(CatalogFilter::utcBoundary('2028-02-29') === '2028-02-29 06:00:00');
$assert(CatalogFilter::utcBoundary('2028-02-29', true) === '2028-03-01 06:00:00');
$assert(CatalogFilter::fromQuery(['q' => str_repeat('á', 80)])->search === str_repeat('á', 80));
$assert(CatalogFilter::fromQuery(['q' => "%' OR 1=1 --"])->search === "%' OR 1=1 --");
foreach ([['q' => []], ['q' => str_repeat('á', 81)], ['q' => "x\0"], ['q' => "\xff"], ['category' => '-1'], ['category' => '1 OR 1=1'], ['page' => '0'], ['page' => '10001'], ['page' => []], ['from' => '2027-02-29'], ['to' => '2026-13-01'], ['from' => '2026-2-01'], ['from' => '1999-01-01'], ['from' => '2026-02-02', 'to' => '2026-02-01'], ['state' => 'BORRADOR'], ['availability' => 'unknown'], ['to' => []]] as $input) {
    $rejected = false;
    try { CatalogFilter::fromQuery($input); } catch (BusinessError $e) { $rejected = $e->httpStatus === 422; }
    $assert($rejected);
}
echo "Filtros de catálogo: $checks comprobaciones correctas.\n";
