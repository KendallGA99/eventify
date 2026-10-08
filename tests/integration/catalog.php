<?php
declare(strict_types=1);
// Incluido en la suite MySQL aislada: nunca ejecutar sobre la aplicación local.
$repository = new Eventify\Infrastructure\CatalogRepository($pdo);
$pdo->beginTransaction();
try {
    $categoryId = (int) $pdo->query("SELECT id FROM categories WHERE slug = 'tecnologia'")->fetchColumn();
    $insert = $pdo->prepare("INSERT INTO events (category_id, slug, name, description, image_path, starts_at, location, capacity, price, status) VALUES (?, ?, ?, 'Fixture de paginación', '/assets/icon.svg', '2090-06-20 06:00:00', 'Lugar ficticio', 20, 1000.00, 'PUBLICADO')");
    for ($index = 0; $index < 14; ++$index) { $insert->execute([$categoryId, 'pagination-probe-' . $index, 'PaginationProbe ' . $index]); }
    $base = ['q' => 'PaginationProbe', 'category' => (string) $categoryId, 'from' => '2090-06-20', 'to' => '2090-06-20', 'availability' => 'available'];
    $first = $repository->search(Eventify\Domain\CatalogFilter::fromQuery($base));
    $second = $repository->search(Eventify\Domain\CatalogFilter::fromQuery([...$base, 'page' => '2']));
    $assert(count($first['rows']) === 12 && $first['hasMore'], 'Catálogo acota primera página y detecta siguiente');
    $assert(count($second['rows']) === 2 && !$second['hasMore'], 'Catálogo última página sin duplicación');
    $assert(array_intersect(array_column($first['rows'], 'id'), array_column($second['rows'], 'id')) === [], 'Orden estable entre páginas');
    $empty = $repository->search(Eventify\Domain\CatalogFilter::fromQuery([...$base, 'from' => '2090-06-21', 'to' => '2090-06-21']));
    $assert($empty['rows'] === [], 'Límite temporal excluye día siguiente');
    $sqlInjection = $repository->search(Eventify\Domain\CatalogFilter::fromQuery(['q' => "%' OR 1=1 --"]));
    $assert($sqlInjection['rows'] === [], 'Consulta preparada no amplía resultados por SQL enviado');
    $insert->execute([$categoryId, 'literal-percent-probe', 'Literal%_Probe']);
    $literal = $repository->search(Eventify\Domain\CatalogFilter::fromQuery(['q' => '%_']));
    $assert(count($literal['rows']) === 1 && $literal['rows'][0]['slug'] === 'literal-percent-probe', 'Porcentaje y guion bajo se buscan literalmente');
} finally {
    $pdo->rollBack();
}
