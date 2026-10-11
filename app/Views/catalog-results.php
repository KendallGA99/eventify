<?php if ($rows === []): ?><p class="notice">No encontramos eventos con estos filtros. Puede limpiar la búsqueda o ampliar las fechas.</p><?php endif; ?>
<div class="event-grid">
<?php foreach ($rows as $item): ?>
<article class="event-card">
    <img class="catalog-image" src="<?= $escape($item['image_path']) ?>" alt="" width="480" height="240" loading="lazy" decoding="async">
    <p class="eyebrow"><?= $escape($item['category']) ?></p>
    <h2 class="card-title"><a href="/eventos/<?= $escape($item['slug']) ?>"><?= $escape($item['name']) ?></a></h2>
    <p><?= $date($item['starts_at']) ?> (Costa Rica)</p><p><?= $escape($item['location']) ?></p><p><?= $money($item['price']) ?> por entrada</p>
    <?php if ($item['display_status'] !== 'PUBLICADO'): ?><p class="event-status"><?= $item['display_status'] === 'CANCELADO' ? 'Evento cancelado' : 'Evento finalizado' ?></p>
    <?php elseif ((int) $item['available'] === 0): ?><p class="event-status">Entradas agotadas</p>
    <?php else: ?><p><?= (int) $item['available'] ?> espacios disponibles</p>
        <?php if (Eventify\Domain\ReservationPolicy::isLastSpaces((int) $item['capacity'], (int) $item['available'])): ?><p class="event-status">¡Últimos espacios!</p><?php endif; ?>
    <?php endif; ?>
    <a class="return-link" href="/eventos/<?= $escape($item['slug']) ?>">Ver experiencia</a>
</article>
<?php endforeach; ?>
</div>
<nav class="catalog-pagination" aria-label="Páginas del catálogo">
    <?php if ($filter->page > 1): ?><a data-catalog-page="<?= $filter->page - 1 ?>" href="<?= $escape($pageUrl($filter->page - 1)) ?>">Anterior</a><?php endif; ?>
    <span>Página <?= $filter->page ?></span>
    <?php if ($hasMore): ?><a data-catalog-page="<?= $filter->page + 1 ?>" href="<?= $escape($pageUrl($filter->page + 1)) ?>">Siguiente</a><?php endif; ?>
</nav>
