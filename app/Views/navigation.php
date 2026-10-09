<?php
$navUser = $user ?? null;
?>
<header class="site-header">
    <a class="brand" href="/" aria-label="Eventify, inicio">eventify<span aria-hidden="true">.</span></a>
    <nav aria-label="Principal">
        <a href="/eventos">Eventos</a>
        <?php if ($navUser !== null): ?>
            <a href="/experiencias">Mis experiencias</a>
            <?php if ($navUser['role'] === 'ADMINISTRADOR'): ?><a href="/admin">Administración</a><?php endif; ?>
            <a href="/cuenta">Mi cuenta</a>
        <?php else: ?>
            <a href="/ingresar">Ingresar</a>
            <a href="/registro">Crear cuenta</a>
        <?php endif; ?>
    </nav>
</header>
