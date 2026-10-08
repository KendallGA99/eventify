<?php
use Eventify\Domain\ReservationPolicy;
use Eventify\Security\Session;
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$date = static fn (string $value): string => (new DateTimeImmutable($value, new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/Costa_Rica'))->format('d/m/Y · H:i');
$money = static fn (string $value): string => Eventify\Support\Format::money($value);
$title = match ($page) { 'event' => $event['name'], 'ticket' => 'Ticket de reserva', 'reservations' => 'Mis experiencias', default => 'Explora eventos' };
?>
<!doctype html><html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= $escape($title) ?> · Eventify</title><link rel="stylesheet" href="/assets/css/app.css"><link rel="icon" href="/assets/icon.svg"><script src="/assets/js/reservations.js" defer></script></head>
<body><header class="site-header"><a class="brand" href="/">eventify<span>.</span></a><nav aria-label="Principal"><a href="/eventos">Eventos</a><a href="/experiencias">Mis experiencias</a><a href="/cuenta">Mi cuenta</a></nav></header>
<main class="events-main"><p class="eyebrow">Experiencias para compartir</p><h1 class="page-title"><?= $escape($title) ?></h1>
<?php if ($page === 'catalog'): ?>
    <?php if ($rows === []): ?><p class="notice">Todavía no hay eventos publicados. Vuelva pronto para descubrir nuevos planes.</p><?php endif; ?>
    <div class="event-grid"><?php foreach ($rows as $item): ?><article class="event-card"><p class="eyebrow"><?= $escape($item['category']) ?></p><h2><a href="/eventos/<?= $escape($item['slug']) ?>"><?= $escape($item['name']) ?></a></h2><p><?= $date($item['starts_at']) ?></p><p><?= $escape($item['location']) ?></p><p><?= $money($item['price']) ?> · <?= (int) $item['available'] ?> espacios</p><a class="return-link" href="/eventos/<?= $escape($item['slug']) ?>">Ver experiencia</a></article><?php endforeach; ?></div>
<?php elseif ($page === 'event'): ?>
    <div class="event-detail"><section><img class="event-image" src="<?= $escape($event['image_path']) ?>" alt="<?= $escape($event['name']) ?>"><p class="eyebrow"><?= $escape($event['category']) ?></p><p class="event-description"><?= nl2br($escape($event['description'])) ?></p><dl><dt>Fecha y hora</dt><dd><?= $date($event['starts_at']) ?> (Costa Rica)</dd><dt>Ubicación</dt><dd><?= $escape($event['location']) ?></dd><dt>Capacidad</dt><dd><?= (int) $event['capacity'] ?> personas</dd><dt>Espacios disponibles</dt><dd><?= (int) $event['available'] ?></dd></dl></section>
    <aside class="auth-card reserve-card"><h2>Tu próxima experiencia</h2><p><?= $money($event['price']) ?> por entrada</p>
    <?php if ((int) $event['available'] === 0): ?><p class="notice">Entradas agotadas.</p>
    <?php else: ?>
        <?php if (ReservationPolicy::isLastSpaces((int) $event['capacity'], (int) $event['available'])): ?><p class="notice">¡Últimos espacios disponibles!</p><?php endif; ?>
        <?php if ($user === null): ?><a class="return-link" href="/ingresar">Ingresar para reservar</a><?php else: ?>
        <form method="post" action="/api/reservas" data-async data-price="<?= $escape($event['price']) ?>"><input type="hidden" name="csrf" value="<?= Session::token() ?>"><input type="hidden" name="event_id" value="<?= (int) $event['id'] ?>"><label for="quantity">Cantidad de entradas</label><input id="quantity" name="quantity" type="number" min="1" max="<?= (int) $event['available'] ?>" step="1" value="1" required><p>Total: <output data-total><?= $money($event['price']) ?></output></p><button class="button" type="submit">Confirmar reserva</button><p data-feedback role="status" aria-live="polite"></p></form>
        <?php endif; ?>
    <?php endif; ?></aside></div>
<?php elseif ($page === 'reservations'): ?>
    <?php if ($rows === []): ?><p class="notice">Aún no tiene reservas. <a href="/eventos">Explore los próximos eventos.</a></p><?php endif; ?>
    <div class="event-grid"><?php foreach ($rows as $item): ?><article class="event-card"><h2><?= $escape($item['name']) ?></h2><p><?= $date($item['starts_at']) ?></p><p><?= (int) $item['quantity'] ?> entradas · <?= $money($item['total']) ?></p><p>Reserva: <?= $escape($item['status']) ?> · Evento: <?= $escape($item['event_status']) ?></p><a href="/tickets/<?= $escape($item['ticket_code']) ?>">Consultar ticket</a>
    <?php if ($item['status'] === 'CONFIRMADA' && ReservationPolicy::canCancel(new DateTimeImmutable($item['starts_at'], new DateTimeZone('UTC')), new DateTimeImmutable('now'))): ?><form method="post" action="/api/reservas/<?= (int) $item['id'] ?>/cancelar" data-async><input type="hidden" name="csrf" value="<?= Session::token() ?>"><button class="button" type="submit">Cancelar reserva</button><p data-feedback role="status" aria-live="polite"></p></form><?php endif; ?></article><?php endforeach; ?></div>
<?php else: ?>
    <article class="auth-card ticket-card"><h2><?= $escape($ticket['name']) ?></h2><p><?= $escape($user['full_name']) ?></p><p><?= $date($ticket['starts_at']) ?> · <?= $escape($ticket['location']) ?></p><p><?= (int) $ticket['quantity'] ?> entradas · <?= $money($ticket['total']) ?></p><p>Reserva <?= $escape($ticket['reservation_code']) ?></p><p class="field-help">Identificador: <?= $escape($ticket['ticket_code']) ?></p>
    <img class="ticket-qr" src="/tickets/<?= $escape($ticket['ticket_code']) ?>/qr.svg" width="392" height="392" alt="Código QR del identificador de este ticket"><p class="field-help">El QR identifica la reserva. La vigencia se consulta en Eventify.</p>
    <?php if ($ticket['status'] !== 'CONFIRMADA' || $ticket['event_status'] !== 'PUBLICADO' || new DateTimeImmutable($ticket['starts_at'], new DateTimeZone('UTC')) <= new DateTimeImmutable('now')): ?><p class="notice">Este ticket ya no está vigente. Su historial permanece disponible.</p><?php else: ?><p class="notice">Reserva confirmada.</p><?php endif; ?>
    <a href="/experiencias">Volver a mis experiencias</a></article>
<?php endif; ?>
</main><footer>Eventify · Experiencias y eventos</footer></body></html>
