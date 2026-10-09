<?php

declare(strict_types=1);

define('PROJECT_ROOT', dirname(__DIR__));
date_default_timezone_set('UTC');
ini_set('display_errors', '0');

spl_autoload_register(static function (string $class): void {
    $prefix = 'Eventify\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $path = __DIR__ . '/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

set_exception_handler(static function (Throwable $exception): void {
    $reference = bin2hex(random_bytes(8));
    // No registrar mensajes/DSN/trace que puedan contener credenciales.
    error_log('eventify_error reference=' . $reference . ' type=' . get_class($exception));
    $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    // Las páginas HTML usan la misma presentación; API y consola conservan su respuesta técnica.
    if (PHP_SAPI !== 'cli' && !str_starts_with((string) $requestPath, '/api/') && $requestPath !== '/salud') {
        \Eventify\Http\ErrorPage::respond(500, 'No pudimos completar la solicitud.', 'Inténtelo de nuevo en unos minutos. Si el problema continúa, comparta esta referencia con quien administra el sitio.', $reference);
        return;
    }
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    header('Cache-Control: no-store');
    echo 'No pudimos completar la solicitud. Referencia: ' . $reference;
});
