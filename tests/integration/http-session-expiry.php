<?php
declare(strict_types=1);

// El servidor y sus archivos de sesión pertenecen al stack efímero de database.php.
if (\Eventify\Support\Config::get('APP_ENV') !== 'test'
    || \Eventify\Support\Config::get('DB_DATABASE') !== 'eventify_test') {
    throw new RuntimeException('La prueba de caducidad requiere el entorno aislado.');
}

[$status, $body] = $request('/cuenta');
$assert($status === 200, 'Cuenta administrativa activa antes de caducar');
$expiredToken = $csrf($body);
$expiredCookie = $cookie;
if (!preg_match('/^eventify_session=([A-Za-z0-9,-]+)$/D', $cookie, $sessionMatch)) {
    throw new RuntimeException('Identificador de sesión de prueba inválido.');
}
$sessionFile = PROJECT_ROOT . '/storage/sessions/sess_' . $sessionMatch[1];
$sessionData = file_get_contents($sessionFile);
if (!is_string($sessionData)) {
    throw new RuntimeException('Sesión de prueba no disponible.');
}

// Envejecer solo la actividad de esta sesión evita dormir o cambiar el reloj del sistema.
$agedData = preg_replace('/last_activity\|i:[0-9]+;/', 'last_activity|i:' . (time() - 1801) . ';', $sessionData, -1, $replacements);
if ($agedData === null || $replacements !== 1
    || file_put_contents($sessionFile, $agedData, LOCK_EX) !== strlen($agedData)) {
    throw new RuntimeException('No se pudo preparar la caducidad aislada.');
}

[$status] = $request('/api/experiencias');
$assert($status === 401, 'Inactividad superior a 30 minutos revoca identidad AJAX');
$assert($cookie !== $expiredCookie, 'Caducidad renueva identificador de sesión');
clearstatcache(true, $sessionFile);
$assert(!is_file($sessionFile), 'Caducidad elimina la sesión autenticada anterior');
[$status] = $request('/admin');
$assert($status === 303, 'Sesión caducada no conserva autorización administrativa');
[$status] = $request('/cuenta', ['csrf' => $expiredToken, 'full_name' => 'Cambio con sesión caducada']);
$assert($status === 403, 'Token previo a caducidad no autoriza escrituras');

// Reenviar la cookie anterior tampoco recupera la identidad ya eliminada.
$cookie = $expiredCookie;
[$status] = $request('/api/experiencias');
$assert($status === 401 && $cookie !== $expiredCookie, 'Cookie autenticada anterior no recupera acceso tras caducidad');
