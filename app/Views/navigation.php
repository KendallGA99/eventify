<?php
$navUser = $user ?? null;
$adminNavigation = $adminNavigation ?? false;
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$links = $adminNavigation
    ? ['/admin' => 'Administración', '/admin/categorias' => 'Categorías', '/admin/eventos' => 'Eventos', '/admin/reservas' => 'Reservas', '/admin/usuarios' => 'Usuarios', '/cuenta' => 'Mi cuenta']
    : ['/eventos' => 'Eventos'];
if (!$adminNavigation) {
    if ($navUser !== null) {
        $links['/experiencias'] = 'Mis experiencias';
        if ($navUser['role'] === 'ADMINISTRADOR') {
            $links['/admin'] = 'Administración';
        }
        $links['/cuenta'] = 'Mi cuenta';
    } else {
        $links['/ingresar'] = 'Ingresar';
        $links['/registro'] = 'Crear cuenta';
    }
}
?>
<a class="skip-link" href="#contenido">Ir al contenido</a>
<header class="site-header">
    <a class="brand" href="/" aria-label="Eventify, inicio">eventify<span aria-hidden="true">.</span></a>
    <nav aria-label="<?= $adminNavigation ? 'Administración' : 'Principal' ?>">
        <?php foreach ($links as $path => $label): ?>
            <?php $active = $currentPath === $path || ($path !== '/admin' && is_string($currentPath) && str_starts_with($currentPath, $path . '/')); ?>
            <a href="<?= $path ?>"<?= $active ? ' aria-current="page"' : '' ?>><?= $label ?></a>
        <?php endforeach; ?>
    </nav>
</header>
