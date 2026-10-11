<?php
use Eventify\Security\Session;
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$title = $register ? 'Crear cuenta' : 'Iniciar sesión';
?>
<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= $title ?> · Eventify</title><link rel="stylesheet" href="<?= Eventify\Support\Assets::url('/assets/css/app.css') ?>"><link rel="icon" href="/assets/icon.svg"><script src="<?= Eventify\Support\Assets::url('/assets/js/ui.js') ?>" defer></script></head>
<body>
<?php require PROJECT_ROOT . '/app/Views/navigation.php'; ?>
<main id="contenido" class="auth-main">
    <section class="auth-card" aria-labelledby="form-title">
        <p class="eyebrow">Tu próxima experiencia</p><h1 id="form-title"><?= $title ?></h1>
        <p><?= $register ? 'Guarda tus próximos planes en un solo lugar.' : 'Qué bueno tenerte de vuelta.' ?></p>
        <?php if (!$register && ($_GET['registro'] ?? null) === 'ok'): ?><p class="notice" role="status">Cuenta creada. Ahora puede iniciar sesión.</p><?php endif; ?>
        <?php if ($errors !== []): ?><div class="form-errors" role="alert"><ul><?php foreach ($errors as $error): ?><li><?= $escape($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
        <form method="post" action="<?= $register ? '/registro' : '/ingresar' ?>">
            <input type="hidden" name="csrf" value="<?= $escape(Session::token()) ?>">
            <?php if ($register): ?><label for="full_name">Nombre completo</label><input id="full_name" name="full_name" autocomplete="name" required minlength="2" maxlength="150" value="<?= $escape($values['full_name']) ?>"><?php endif; ?>
            <label for="email">Correo electrónico</label><input id="email" name="email" type="email" autocomplete="email" required maxlength="254" value="<?= $escape($values['email']) ?>">
            <label for="password">Contraseña</label><div class="password-field"><input id="password" name="password" type="password" autocomplete="<?= $register ? 'new-password' : 'current-password' ?>" required <?= $register ? 'minlength="8"' : '' ?> aria-describedby="password-help"><?php $fieldId = 'password'; $fieldLabel = 'contraseña'; require PROJECT_ROOT . '/app/Views/password-toggle.php'; ?></div>
            <p id="password-help" class="field-help"><?= $register ? 'Use al menos 8 caracteres.' : 'Ingrese la contraseña de su cuenta.' ?></p>
            <?php if ($register): ?><label for="password_confirmation">Confirmar contraseña</label><div class="password-field"><input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required><?php $fieldId = 'password_confirmation'; $fieldLabel = 'confirmación de contraseña'; require PROJECT_ROOT . '/app/Views/password-toggle.php'; ?></div><?php endif; ?>
            <button class="button" type="submit"><?= $title ?></button>
        </form>
        <p class="auth-alternative"><?= $register ? '¿Ya tiene cuenta?' : '¿Primera vez por aquí?' ?> <a href="<?= $register ? '/ingresar' : '/registro' ?>"><?= $register ? 'Iniciar sesión' : 'Crear cuenta' ?></a></p>
    </section>
</main>
<footer>Eventify · Experiencias y eventos</footer>
</body></html>
