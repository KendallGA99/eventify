<?php
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $escape($title) ?> · Eventify</title>
    <link rel="icon" href="/assets/icon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="/">eventify<span>.</span></a>
        <nav aria-label="Navegación principal">
            <a href="/eventos">Eventos</a>
            <a href="/cuenta">Mi cuenta</a>
        </nav>
    </header>
    <main class="auth-main">
        <section class="auth-card" aria-labelledby="error-title">
            <p class="eyebrow">Solicitud no completada · <?= $status ?></p>
            <h1 id="error-title"><?= $escape($title) ?></h1>
            <p><?= $escape($message) ?></p>
            <?php if ($reference !== ''): ?>
                <p class="field-help">Referencia: <?= $escape($reference) ?></p>
            <?php endif; ?>
            <p><a class="return-link" href="/eventos">Explorar eventos</a></p>
            <p><a href="/cuenta">Volver a mi cuenta</a></p>
        </section>
    </main>
    <footer>Eventify · Experiencias y eventos</footer>
</body>
</html>
