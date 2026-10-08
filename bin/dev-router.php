<?php

declare(strict_types=1);

// Solo para comprobaciones locales con php -S; producción usa Apache/public.
$public = realpath(dirname(__DIR__) . '/public');
$path = rawurldecode((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
$file = realpath($public . '/' . ltrim($path, '/'));
if ($file !== false && str_starts_with($file, $public . DIRECTORY_SEPARATOR) && is_file($file)) {
    return false;
}
require $public . '/index.php';
