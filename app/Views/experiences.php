<?php
use Eventify\Domain\ReservationPolicy;
use Eventify\Security\Session;
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$date = static fn (string $value): string => (new DateTimeImmutable($value, new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/Costa_Rica'))->format('d/m/Y · H:i');
$money = static fn (string $value): string => Eventify\Support\Format::money($value);
$title = match ($page) { 'event' => $event['name'], 'ticket' => 'Ticket de reserva', 'reservations' => 'Mis experiencias', default => 'Explora eventos' };
?>
<!doctype html><html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= $escape($title) ?> · Eventify</title><link rel="stylesheet" href="/assets/css/app.css"><link rel="icon" href="/assets/icon.svg"><script src="/assets/lib/jquery-4.0.0.min.js" defer></script><script src="/assets/js/catalog.js" defer></script><script src="/assets/js/reservations.js" defer></script><script src="/assets/js/experiences.js" defer></script><script src="/assets/js/weather.js" defer></script></head>
<body><?php require PROJECT_ROOT . '/app/Views/navigation.php'; ?>
<main class="events-main"><p class="eyebrow">Experiencias para compartir</p><h1 class="page-title"><?= $escape($title) ?></h1>
<?php if ($page === 'catalog'): ?>
    <form class="catalog-filters" action="/eventos" method="get" data-catalog-filters>
        <div><label for="catalog-search">Buscar por nombre</label><input id="catalog-search" name="q" type="search" maxlength="80" value="<?= $escape($filter->search) ?>" placeholder="¿Qué le gustaría hacer?"></div>
        <div><label for="catalog-category">Categoría</label><select id="catalog-category" name="category"><option value="">Todas las categorías</option><?php foreach ($categories as $category): ?><option value="<?= (int) $category['id'] ?>" <?= $filter->category === (int) $category['id'] ? 'selected' : '' ?>><?= $escape($category['name']) ?></option><?php endforeach; ?></select></div>
        <div><label for="catalog-from">Desde</label><input id="catalog-from" name="from" type="date" min="2000-01-01" max="2099-12-31" value="<?= $escape($filter->from) ?>"></div>
        <div><label for="catalog-to">Hasta</label><input id="catalog-to" name="to" type="date" min="2000-01-01" max="2099-12-31" value="<?= $escape($filter->to) ?>"></div>
        <div><label for="catalog-availability">Disponibilidad</label><select id="catalog-availability" name="availability"><?php foreach (['all' => 'Cualquier disponibilidad', 'available' => 'Con espacios', 'soldout' => 'Agotados'] as $value => $label): ?><option value="<?= $value ?>" <?= $filter->availability === $value ? 'selected' : '' ?>><?= $label ?></option><?php endforeach; ?></select></div>
        <div><label for="catalog-state">Estado</label><select id="catalog-state" name="state"><?php foreach (['upcoming' => 'Próximos eventos', 'all' => 'Todos los públicos', 'cancelled' => 'Cancelados', 'finished' => 'Finalizados'] as $value => $label): ?><option value="<?= $value ?>" <?= $filter->state === $value ? 'selected' : '' ?>><?= $label ?></option><?php endforeach; ?></select></div>
        <button class="button" type="submit">Buscar eventos</button><a href="/eventos" data-catalog-clear>Limpiar filtros</a>
    </form>
    <p data-catalog-feedback role="status" aria-live="polite"><?= $escape($error) ?></p>
    <div data-catalog-results><?php require PROJECT_ROOT . '/app/Views/catalog-helpers.php'; require PROJECT_ROOT . '/app/Views/catalog-results.php'; ?></div>
<?php elseif ($page === 'event'): ?>
    <div class="event-detail"><section><img class="event-image" src="<?= $escape($event['image_path']) ?>" alt="<?= $escape($event['name']) ?>"><p class="eyebrow"><?= $escape($event['category']) ?></p><p class="event-description"><?= nl2br($escape($event['description'])) ?></p><dl><dt>Fecha y hora</dt><dd><?= $date($event['starts_at']) ?> (Costa Rica)</dd><dt>Ubicación</dt><dd><?= $escape($event['location']) ?></dd><dt>Capacidad</dt><dd><?= (int) $event['capacity'] ?> personas</dd><dt>Espacios disponibles</dt><dd><?= (int) $event['available'] ?></dd></dl>
    <section class="weather-panel" data-event-weather="/api/eventos/<?= $escape($event['slug']) ?>/clima" aria-labelledby="weather-title"><h2 id="weather-title">Clima para tu experiencia</h2><p data-weather-message role="status" aria-live="polite">Active JavaScript para consultar el pronóstico. Su reserva está disponible sin esta información.</p><div data-weather-values></div><button class="button" type="button" data-weather-retry hidden>Consultar de nuevo</button><p class="field-help">Datos de <a href="https://open-meteo.com/" target="_blank" rel="noopener noreferrer">Open-Meteo</a>, <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank" rel="noopener noreferrer">CC BY 4.0</a>. Resumen diario adaptado para Eventify.</p></section>
    </section>
    <aside class="auth-card reserve-card"><h2>Tu próxima experiencia</h2><p><?= $money($event['price']) ?> por entrada</p>
    <?php if ($event['display_status'] !== 'PUBLICADO'): ?><p class="notice"><?= $event['display_status'] === 'CANCELADO' ? 'Este evento fue cancelado.' : 'Este evento ya finalizó.' ?> Su información permanece disponible.</p>
    <?php elseif ((int) $event['available'] === 0): ?><p class="notice">Entradas agotadas.</p>
    <?php else: ?>
        <?php if (ReservationPolicy::isLastSpaces((int) $event['capacity'], (int) $event['available'])): ?><p class="notice">¡Últimos espacios disponibles!</p><?php endif; ?>
        <?php if ($user === null): ?><a class="return-link" href="/ingresar">Ingresar para reservar</a><?php else: ?>
        <form method="post" action="/api/reservas" data-async data-price="<?= $escape($event['price']) ?>"><input type="hidden" name="csrf" value="<?= Session::token() ?>"><input type="hidden" name="event_id" value="<?= (int) $event['id'] ?>"><label for="quantity">Cantidad de entradas</label><input id="quantity" name="quantity" type="number" min="1" max="<?= (int) $event['available'] ?>" step="1" value="1" required><p>Total: <output data-total><?= $money($event['price']) ?></output></p><button class="button" type="submit">Confirmar reserva</button><p data-feedback role="status" aria-live="polite"></p></form>
        <?php endif; ?>
    <?php endif; ?></aside></div>
<?php elseif ($page === 'reservations'): ?>
    <button class="button" type="button" data-experiences-refresh>Actualizar mis experiencias</button>
    <p data-experiences-feedback role="status" aria-live="polite"></p>
    <div data-experiences-results data-csrf="<?= Session::token() ?>"><?php require PROJECT_ROOT . '/app/Views/reservation-results.php'; ?></div>
<?php else: ?>
    <article class="auth-card ticket-card"><h2><?= $escape($ticket['name']) ?></h2><p><?= $escape($user['full_name']) ?></p><p><?= $date($ticket['starts_at']) ?> · <?= $escape($ticket['location']) ?></p><p><?= (int) $ticket['quantity'] ?> <?= (int) $ticket['quantity'] === 1 ? 'entrada' : 'entradas' ?> · <?= $money($ticket['total']) ?></p><p>Reserva <?= $escape($ticket['reservation_code']) ?></p><p class="field-help">Identificador: <?= $escape($ticket['ticket_code']) ?></p>
    <img class="ticket-qr" src="/tickets/<?= $escape($ticket['ticket_code']) ?>/qr.svg" width="392" height="392" alt="Código QR del identificador de este ticket"><p class="field-help">El QR identifica la reserva. La vigencia se consulta en Eventify.</p>
    <?php if ($ticket['status'] !== 'CONFIRMADA' || $ticket['event_status'] !== 'PUBLICADO' || new DateTimeImmutable($ticket['starts_at'], new DateTimeZone('UTC')) <= new DateTimeImmutable('now')): ?><p class="notice">Este ticket ya no está vigente. Su historial permanece disponible.</p><?php else: ?><p class="notice">Reserva confirmada.</p><?php endif; ?>
    <a href="/experiencias">Volver a mis experiencias</a></article>
<?php endif; ?>
</main><footer>Eventify · Experiencias y eventos</footer></body></html>
