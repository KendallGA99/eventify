<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Frame-Options: DENY');
header("Content-Security-Policy: default-src 'self'; style-src 'self'; script-src 'self'; img-src 'self' data:; frame-ancestors 'none'; base-uri 'self'; form-action 'self'");

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$authRoutes = ['/registro', '/ingresar', '/cuenta', '/admin', '/salir'];
$reserveApi = $path === '/api/reservas' || preg_match('#\A/api/reservas/([1-9][0-9]{0,9})/cancelar\z#', (string) $path, $cancelMatch);
$allowed = in_array($path, ['/registro', '/ingresar'], true) ? ['GET', 'HEAD', 'POST'] : ($path === '/salir' || $reserveApi ? ['POST'] : ['GET', 'HEAD']);
if (!in_array($method, $allowed, true)) {
    http_response_code(405);
    header('Allow: ' . implode(', ', $allowed));
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Método no permitido.';
    exit;
}
if ($path === '/salud') {
    (new Eventify\Http\HealthController())->respond();
    exit;
}
header('Content-Type: text/html; charset=utf-8');
if ($reserveApi) {
    (new Eventify\Http\ReservationController())->api(isset($cancelMatch[1]) ? (int) $cancelMatch[1] : null);
    exit;
}
if ($path === '/api/experiencias') {
    (new Eventify\Http\ReservationController())->experiencesApi();
    exit;
}
if ($path === '/eventos' || $path === '/api/eventos') {
    (new Eventify\Http\CatalogController())->respond($path === '/api/eventos');
    exit;
}
if (in_array($path, ['/experiencias'], true) || preg_match('#\A/eventos/[a-z0-9-]+\z#', (string) $path) || preg_match('#\A/tickets/[a-f0-9]{64}(?:/qr\.svg)?\z#', (string) $path)) {
    (new Eventify\Http\ReservationController())->page($path);
    exit;
}
if (in_array($path, $authRoutes, true)) {
    (new Eventify\Http\AuthController())->respond($path, $method);
    exit;
}
if ($path !== '/') {
    http_response_code(404);
    require PROJECT_ROOT . '/app/Views/not-found.php';
    exit;
}
require PROJECT_ROOT . '/app/Views/home.php';
