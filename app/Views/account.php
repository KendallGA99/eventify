<?php
use Eventify\Security\Session;
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi cuenta · Eventify</title>
    <link rel="stylesheet" href="/assets/css/app.css"><link rel="icon" href="/assets/icon.svg">
</head>
<body>
<?php require PROJECT_ROOT . '/app/Views/navigation.php'; ?>
<main class="account-main">
    <div class="account-heading"><p class="eyebrow">Su espacio en Eventify</p><h1 class="page-title">Mi cuenta</h1><p>Actualice su nombre y tenga sus próximos planes a mano.</p></div>
    <div class="account-grid">
        <section class="auth-card profile-card" aria-labelledby="profile-title">
            <h2 id="profile-title">Datos del perfil</h2>
            <?php if (($_GET['guardado'] ?? null) === 'ok' && $errors === []): ?><p class="notice" role="status">Su nombre se actualizó correctamente.</p><?php endif; ?>
            <?php if ($errors !== []): ?><div class="form-errors" role="alert"><ul><?php foreach ($errors as $error): ?><li><?= $escape($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
            <form method="post" action="/cuenta">
                <input type="hidden" name="csrf" value="<?= $escape(Session::token()) ?>">
                <label for="full_name">Nombre completo</label>
                <input id="full_name" name="full_name" autocomplete="name" required minlength="2" maxlength="150" value="<?= $escape($values['full_name']) ?>">
                <label for="account-email">Correo electrónico</label>
                <input id="account-email" type="email" readonly value="<?= $escape($user['email']) ?>" aria-describedby="account-email-help">
                <p id="account-email-help" class="field-help">Este es el correo con el que ingresa a Eventify.</p>
                <button class="button" type="submit">Guardar cambios</button>
            </form>
        </section>
        <aside class="account-shortcuts" aria-label="Opciones de su cuenta">
            <section class="event-card"><h2>Mis experiencias</h2><p>Consulte sus reservas, revise sus tickets o gestione una cancelación.</p><a class="return-link" href="/experiencias">Ver mis reservas</a></section>
            <?php if ($user['role'] === 'ADMINISTRADOR'): ?><section class="event-card"><h2>Administración</h2><p>Gestione eventos, categorías y reservas desde el panel.</p><a href="/admin">Abrir administración</a></section><?php endif; ?>
            <section class="event-card"><h2>Sesión</h2><p>Al terminar en un equipo compartido, cierre su sesión.</p><form method="post" action="/salir"><input type="hidden" name="csrf" value="<?= $escape(Session::token()) ?>"><button class="button button-secondary" type="submit">Cerrar sesión</button></form></section>
        </aside>
    </div>
</main>
<footer>Eventify · Experiencias y eventos</footer>
</body>
</html>
