<?php
declare(strict_types=1);
require dirname(__DIR__, 2) . '/app/bootstrap.php';

ob_start();
\Eventify\Http\ErrorPage::respond(403, '<script>título</script>', '<img src=x onerror=alert(1)>', '<referencia>');
$html = ob_get_clean();
$checks = [
    http_response_code() === 403,
    str_contains($html, '&lt;script&gt;título&lt;/script&gt;'),
    !str_contains($html, '<script>título</script>'),
    str_contains($html, '&lt;img src=x onerror=alert(1)&gt;'),
    str_contains($html, '&lt;referencia&gt;'),
    str_contains($html, 'href="/assets/css/app.css"'),
    str_contains($html, 'href="/eventos"') && str_contains($html, 'href="/cuenta"'),
];
if (in_array(false, $checks, true)) {
    throw new RuntimeException('Falló la presentación segura de errores.');
}
echo 'Página de error: ' . count($checks) . " comprobaciones correctas.\n";
