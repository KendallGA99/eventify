<?php if ($view === 'users'): ?>
<p>Autorice a las personas que podrán gestionar eventos, categorías y reservas, y dar acceso a otros administradores.</p>
<form class="catalog-filters" method="get" action="/admin/usuarios">
    <div><label for="user-search">Nombre o correo</label><input id="user-search" name="q" maxlength="160" value="<?= $escape($search) ?>"></div>
    <button class="button" type="submit">Buscar usuarios</button><a href="/admin/usuarios">Limpiar búsqueda</a>
</form>
<div class="event-grid admin-list">
<?php foreach ($rows as $item): ?>
    <article class="event-card">
        <h2><?= $escape($item['full_name']) ?></h2>
        <p class="user-email"><?= $escape($item['email']) ?></p>
        <p><?= $item['role'] === 'ADMINISTRADOR' ? 'Administrador' : 'Usuario' ?> · <?= $item['is_active'] ? 'Cuenta activa' : 'Cuenta inactiva' ?></p>
        <?php if ($item['role'] === 'USUARIO' && $item['is_active']): ?>
        <a class="button" href="/admin/usuarios/<?= (int) $item['id'] ?>/administrador">Hacer administrador</a>
        <?php endif; ?>
    </article>
<?php endforeach; ?>
</div>
<?php if ($rows === []): ?><p class="notice">No encontramos usuarios con esa búsqueda.</p><?php endif; ?>
<nav class="catalog-pagination" aria-label="Páginas de usuarios">
    <?php if ($number > 1): ?><a href="?<?= $escape(http_build_query(['q' => $search, 'page' => $number - 1])) ?>">Anterior</a><?php endif; ?>
    <span>Página <?= $number ?></span>
    <?php if ($hasMore): ?><a href="?<?= $escape(http_build_query(['q' => $search, 'page' => $number + 1])) ?>">Siguiente</a><?php endif; ?>
</nav>
<?php else: ?>
<section class="admin-form-card">
    <h2><?= $escape($record['full_name']) ?></h2>
    <p class="user-email"><?= $escape($record['email']) ?></p>
    <?php if ($record['role'] === 'ADMINISTRADOR'): ?>
    <p class="notice">Esta cuenta ya es administradora.</p>
    <?php elseif (!$record['is_active']): ?>
    <p class="notice">Esta cuenta está inactiva y no puede recibir permisos.</p>
    <?php else: ?>
    <p>Esta persona podrá crear y editar eventos, consultar reservas y autorizar a otros administradores. Su cuenta conservará las reservas y los tickets existentes.</p>
    <form class="admin-form" method="post" action="/admin/usuarios/<?= (int) $record['id'] ?>/promover">
        <input type="hidden" name="csrf" value="<?= $csrf ?>">
        <div class="form-wide"><label for="admin-password">Su contraseña de administrador</label>
            <div class="password-field"><input id="admin-password" name="password" type="password" autocomplete="current-password" required><?php $fieldId = 'admin-password'; $fieldLabel = 'contraseña de administrador'; require PROJECT_ROOT . '/app/Views/password-toggle.php'; ?></div>
        </div>
        <div class="form-wide"><label class="promotion-confirm"><input type="checkbox" name="confirm" value="1" required> Confirmo que quiero dar permisos de administrador a esta cuenta.</label></div>
        <button class="button" type="submit">Confirmar administrador</button>
    </form>
    <?php endif; ?>
    <a class="return-link" href="/admin/usuarios">Volver a usuarios</a>
</section>
<?php endif; ?>
