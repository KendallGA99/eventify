<?php
use Eventify\Domain\ReservationPolicy;
use Eventify\Security\Session;
?>
    <?php if ($rows === []): ?><p class="notice">Aún no tiene reservas. <a href="/eventos">Explore los próximos eventos.</a></p><?php endif; ?>
    <div class="event-grid"><?php foreach ($rows as $item): ?><article class="event-card"><h2><?= $escape($item['name']) ?></h2><p><?= $date($item['starts_at']) ?></p><p><?= (int) $item['quantity'] ?> entradas · <?= $money($item['total']) ?></p><p>Reserva: <?= $escape($item['status']) ?> · Evento: <?= $escape($item['event_status']) ?></p><a href="/tickets/<?= $escape($item['ticket_code']) ?>">Consultar ticket</a>
    <?php if ($item['status'] === 'CONFIRMADA' && ReservationPolicy::canCancel(new DateTimeImmutable($item['starts_at'], new DateTimeZone('UTC')), new DateTimeImmutable('now'))): ?><form method="post" action="/api/reservas/<?= (int) $item['id'] ?>/cancelar" data-cancel-reservation><input type="hidden" name="csrf" value="<?= Session::token() ?>"><button class="button" type="submit">Cancelar reserva</button><p data-feedback role="status" aria-live="polite"></p></form><?php endif; ?></article><?php endforeach; ?></div>
