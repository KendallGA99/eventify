<?php
use Eventify\Security\Session;
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<!doctype html><html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= $escape($title) ?> · Eventify</title><link rel="stylesheet" href="/assets/css/app.css"><link rel="icon" href="/assets/icon.svg"></head>
<body><header class="site-header"><a class="brand" href="/">eventify<span>.</span></a><nav aria-label="Cuenta"><a href="/cuenta">Mi cuenta</a><?php if ($user['role'] === 'ADMINISTRADOR'): ?><a href="/admin">Administración</a><?php endif; ?></nav></header>
<main class="auth-main"><section class="auth-card"><p class="eyebrow">Hola, <?= $escape($user['full_name']) ?></p><h1><?= $escape($title) ?></h1><p><?= $escape($content) ?></p><p class="field-help"><?= $escape($user['email']) ?></p><p><a href="/experiencias">Consultar mis experiencias</a></p><form method="post" action="/salir"><input type="hidden" name="csrf" value="<?= $escape(Session::token()) ?>"><button class="button" type="submit">Cerrar sesión</button></form></section></main><footer>Eventify · Experiencias y eventos</footer></body></html>
