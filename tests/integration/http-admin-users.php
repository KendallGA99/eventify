<?php
declare(strict_types=1);
// Datos exclusivamente en eventify_test, con la sesión administrativa del ensayo HTTP.
$promotionEmail = 'promotion-' . bin2hex(random_bytes(4)) . '@eventify.test';
$promotionPassword = bin2hex(random_bytes(16));
$insert = $pdo->prepare("INSERT INTO users (role_id, full_name, email, password_hash) VALUES ((SELECT id FROM roles WHERE code = 'USUARIO'), ?, ?, ?)");
$insert->execute(['Cuenta <prueba> de permisos', $promotionEmail, password_hash($promotionPassword, PASSWORD_DEFAULT)]);
$promotionId = (int) $pdo->lastInsertId();
$promotionPath = '/admin/usuarios/' . $promotionId . '/promover';
$administratorCookie = $cookie;
$cookie = '';
[$status, $body] = $request('/ingresar');
[$status] = $request('/ingresar', ['csrf' => $csrf($body), 'email' => $promotionEmail, 'password' => $promotionPassword]);
$assert($status === 303, 'Cuenta candidata inicia sesión antes de promoción');
$candidateCookie = $cookie;
[$status] = $request('/admin/usuarios');
$assert($status === 403, 'Candidata no tiene administración antes de promoción');
$cookie = $administratorCookie;
[$status, $body] = $request('/admin/usuarios?q=' . urlencode($promotionEmail));
$assert($status === 200 && str_contains($body, '&lt;prueba&gt;') && !str_contains($body, '<prueba>'), 'Búsqueda administrativa y escape de nombres');
$assert(!str_contains($body, $promotionPassword) && !str_contains($body, 'password_hash'), 'Listado no contiene contraseñas o hashes');
[$status] = $request('/admin/usuarios?q%5B%5D=x');
$assert($status === 422, 'Búsqueda malformada de usuarios rechazada');
[$status, $body] = $request('/admin/usuarios?q=%25');
$assert($status === 200 && str_contains($body, 'No encontramos'), 'Búsqueda de usuarios trata comodín como texto');
[$status] = $request($promotionPath);
$assert($status === 405, 'Promoción no se permite por GET');
[$status, $body] = $request('/admin/usuarios/' . $promotionId . '/administrador');
$promotionToken = $csrf($body);
$assert($status === 200 && str_contains($body, 'Confirmar administrador'), 'Confirmación identifica cuenta y privilegios');
[$status] = $request($promotionPath, ['password' => $credentials[0]['password'], 'confirm' => '1']);
$assert($status === 403, 'Promoción exige CSRF');
[$status, $body] = $request($promotionPath, ['csrf' => $promotionToken, 'password' => 'incorrecta', 'confirm' => '1']);
$assert($status === 403 && !str_contains($body, 'value="incorrecta"'), 'Contraseña incorrecta no autoriza ni se refleja');
[$status] = $request($promotionPath, ['csrf' => $promotionToken, 'password' => $credentials[0]['password']]);
$assert($status === 422, 'Promoción exige confirmación explícita');
$pdo->exec('UPDATE users SET is_active = 0 WHERE id = ' . $promotionId);
[$status] = $request($promotionPath, ['csrf' => $promotionToken, 'password' => $credentials[0]['password'], 'confirm' => '1']);
$assert($status === 409, 'Cuenta inactiva no recibe administración');
$pdo->exec('UPDATE users SET is_active = 1 WHERE id = ' . $promotionId);
$businessSnapshot = [];
foreach (['events', 'reservations', 'tickets'] as $table) {
    $businessSnapshot[$table] = $pdo->query('SELECT * FROM ' . $table . ' ORDER BY id')->fetchAll();
}
[$status] = $request($promotionPath, ['csrf' => $promotionToken, 'password' => $credentials[0]['password'], 'confirm' => '1', 'role' => 'inventado']);
$assert($status === 303, 'Administrador autoriza usuario activo');
$cookie = $candidateCookie;
[$status] = $request('/admin/usuarios');
$assert($status === 200, 'Sesión existente consulta nuevo rol desde MySQL');
$cookie = $administratorCookie;
[$status] = $request($promotionPath, ['csrf' => $promotionToken, 'password' => $credentials[0]['password'], 'confirm' => '1']);
$assert($status === 409, 'Repetición de promoción rechazada sin cambiar cuenta');
[$status] = $request('/admin/usuarios/999999999/administrador');
$assert($status === 404, 'Confirmación de usuario inexistente devuelve404');
foreach ($businessSnapshot as $table => $rowsBefore) {
    $assert($rowsBefore === $pdo->query('SELECT * FROM ' . $table . ' ORDER BY id')->fetchAll(), 'Promoción conserva ' . $table);
}
for ($attempt = 0; $attempt < 5; ++$attempt) {
    $request($promotionPath, ['csrf' => $promotionToken, 'password' => 'incorrecta', 'confirm' => '1']);
}
[$status] = $request($promotionPath, ['csrf' => $promotionToken, 'password' => $credentials[0]['password'], 'confirm' => '1']);
$assert($status === 429, 'Reautenticación administrativa tiene límite de intentos');
// Retirar solo la cuenta creada por este ensayo; la base entera ya es efímera.
$delete = $pdo->prepare('DELETE FROM users WHERE id = ?');
$delete->execute([$promotionId]);
