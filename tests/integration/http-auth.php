<?php
declare(strict_types=1);
// Se incluye desde database.php exclusivamente dentro del contenedor de pruebas.
$server = proc_open([PHP_BINARY, '-S', '127.0.0.1:8099', '-t', PROJECT_ROOT . '/public', PROJECT_ROOT . '/bin/dev-router.php'], [['file', '/dev/null', 'r'], ['file', '/dev/null', 'a'], ['file', '/dev/null', 'a']], $pipes, PROJECT_ROOT);
if (!is_resource($server)) { throw new RuntimeException('Servidor de prueba no disponible.'); }
try {
    $ready = false;
    for ($wait = 0; $wait < 50; ++$wait) {
        $socket = @fsockopen('127.0.0.1', 8099, $errorNumber, $errorMessage, 0.1);
        if ($socket !== false) { fclose($socket); $ready = true; break; }
        usleep(100000);
    }
    $assert($ready, 'Servidor HTTP aislado listo');
    $cookie = '';
    $request = static function (string $path, ?array $data = null) use (&$cookie): array {
        $headers = ['Connection: close'];
        if ($cookie !== '') { $headers[] = 'Cookie: ' . $cookie; }
        if ($data !== null) { $headers[] = 'Content-Type: application/x-www-form-urlencoded'; }
        $context = stream_context_create(['http' => ['method' => $data === null ? 'GET' : 'POST', 'header' => implode("\r\n", $headers), 'content' => $data === null ? '' : http_build_query($data), 'ignore_errors' => true, 'follow_location' => 0, 'timeout' => 5]]);
        $body = file_get_contents('http://127.0.0.1:8099' . $path, false, $context);
        $responseHeaders = $http_response_header ?? [];
        preg_match('/\s(\d{3})\s/', $responseHeaders[0] ?? '', $match);
        foreach ($responseHeaders as $header) {
            if (preg_match('/^Set-Cookie: (eventify_session=[^;]*)/i', $header, $matchCookie)) { $cookie = $matchCookie[1]; }
        }
        return [(int) ($match[1] ?? 0), (string) $body, $responseHeaders];
    };
    $csrf = static function (string $html): string {
        if (!preg_match('/name="csrf" value="([a-f0-9]{64})"/', $html, $match)) { throw new RuntimeException('Formulario sin CSRF.'); }
        return $match[1];
    };
    [$status, $body] = $request('/api/eventos?q=Taller&availability=available');
    $catalog = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    $assert($status === 200 && str_contains($catalog['html'], 'taller-sql') && !str_contains($catalog['html'], 'cocina-agotado'), 'Búsqueda y disponibilidad AJAX');
    $categoryId = (int) $pdo->query("SELECT id FROM categories WHERE slug = 'tecnologia'")->fetchColumn();
    $eventDate = (new DateTimeImmutable((string) $pdo->query("SELECT starts_at FROM events WHERE slug = 'taller-sql'")->fetchColumn(), new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/Costa_Rica'))->format('Y-m-d');
    [$status, $body] = $request('/api/eventos?' . http_build_query(['q' => 'SQL', 'category' => $categoryId, 'from' => $eventDate, 'to' => $eventDate, 'availability' => 'available']));
    $catalog = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    $assert($status === 200 && str_contains($catalog['html'], 'taller-sql'), 'Categoría, fechas Costa Rica y cupos combinados');
    [$status, $body] = $request('/api/eventos?q=%25');
    $catalog = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    $assert($status === 200 && str_contains($catalog['html'], 'No encontramos'), 'Comodín del usuario tratado literalmente');
    [$status, $body] = $request('/api/eventos/taller-sql/clima');
    $weather = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    $assert($status === 200 && $weather['status'] === 'disabled', 'API clima aislada sin llamadas externas en tests');
    [$status] = $request('/api/eventos/carrera-borrador/clima');
    $assert($status === 404, 'Clima de borrador privado rechazado');
    [$status] = $request('/api/eventos?from=2026-02-29');
    $assert($status === 422, 'Fecha inválida rechazada por API');
    [$status] = $request('/api/eventos?q%5B%5D=x');
    $assert($status === 422, 'Parámetros array rechazados por API');
    [$status, $body] = $request('/api/eventos?state=all');
    $catalog = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    $assert($status === 200 && str_contains($catalog['html'], 'Evento cancelado') && str_contains($catalog['html'], 'Evento finalizado') && !str_contains($catalog['html'], 'borrador'), 'Estados públicos visibles y borrador privado');
    [$status, , $headers] = $request('/admin');
    $assert($status === 303, 'Visitante redirigido al ingreso');
    $assert(stripos(implode(' ', $headers), 'HttpOnly') !== false && stripos(implode(' ', $headers), 'SameSite=Lax') !== false, 'Cookie inicial protegida');
    [$status] = $request('/api/experiencias');
    $assert($status === 401, 'Historial AJAX requiere sesión');
    [$status, $body, $headers] = $request('/registro');
    $assert($status === 200, 'Formulario con sesión existente');
    $token = $csrf($body);
    [$status] = $request('/registro', ['csrf' => 'invalido']);
    $assert($status === 403, 'POST sin CSRF válido rechazado');
    $input = ['csrf' => $token, 'full_name' => '<script>alert(1)</script>', 'email' => 'http@eventify.test', 'password' => 'Prueba-http-segura', 'password_confirmation' => 'distinta', 'role' => 'ADMINISTRADOR'];
    [$status, $body] = $request('/registro', $input);
    $assert($status === 422 && str_contains($body, '&lt;script&gt;') && !str_contains($body, '<script>alert(1)</script>'), 'Validación y escape de valores');
    $input['full_name'] = 'Usuario HTTP';
    $input['password_confirmation'] = $input['password'];
    [$status] = $request('/registro', $input);
    $assert($status === 303, 'Alta mediante HTTP');
    [$status, $body] = $request('/ingresar');
    $token = $csrf($body);
    $oldCookie = $cookie;
    [$status, , $loginHeaders] = $request('/ingresar', ['csrf' => $token, 'email' => $input['email'], 'password' => $input['password']]);
    $assert($status === 303 && $cookie !== $oldCookie && in_array('Location: /', $loginHeaders, true), 'Login HTTP renueva sesión y vuelve al inicio');
    [$status, $body] = $request('/cuenta');
    $assert($status === 200 && str_contains($body, 'Usuario HTTP'), 'Cuenta autenticada');
    $logoutToken = $csrf($body);
    [$status, $body] = $request('/');
    $assert($status === 200 && str_contains($body, 'Mis experiencias') && !str_contains($body, 'Estamos preparando'), 'Inicio autenticado con navegación y texto final');
    [$status] = $request('/cuenta', ['csrf' => 'invalido', 'full_name' => 'Cambio indebido']);
    $assert($status === 403, 'Perfil exige CSRF');
    [$status, $body] = $request('/cuenta', ['csrf' => $logoutToken, 'full_name' => ['invalido']]);
    $assert($status === 422 && str_contains($body, 'Escriba un nombre'), 'Perfil rechaza nombre malformado');
    [$status] = $request('/cuenta', ['csrf' => $logoutToken, 'full_name' => 'Usuario Actualizado', 'role' => 'ADMINISTRADOR', 'email' => 'intruso@example.test', 'id' => '1']);
    $assert($status === 303, 'Perfil guarda nombre válido');
    [$status, $body] = $request('/cuenta');
    $assert($status === 200 && str_contains($body, 'Usuario Actualizado') && str_contains($body, $input['email']) && !str_contains($body, 'intruso@example.test'), 'Perfil conserva correo y muestra nombre guardado');
    $profileQuery = $pdo->prepare('SELECT u.full_name, r.code FROM users u JOIN roles r ON r.id = u.role_id WHERE u.email = ?');
    $profileQuery->execute([$input['email']]);
    $profileRow = $profileQuery->fetch();
    $assert($profileRow['full_name'] === 'Usuario Actualizado' && $profileRow['code'] === 'USUARIO', 'Perfil no permite elevar rol o cambiar propietario');
    [$status, $body] = $request('/api/experiencias');
    $history = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    $assert($status === 200 && str_contains($history['html'], 'Aún no tiene reservas'), 'Historial AJAX vacío pertenece al usuario autenticado');
    [$status] = $request('/admin');
    $assert($status === 403, 'Usuario rechaza URL administrativa directa');
    [$status] = $request('/admin/eventos/nuevo');
    $assert($status === 403, 'Formulario de eventos exige administrador');
    [$status] = $request('/admin/categorias', ['csrf' => $logoutToken, 'name' => 'Intento', 'slug' => 'intento']);
    $assert($status === 403, 'Escritura administrativa rechaza rol usuario');
    foreach (['/admin/reservas', '/admin/reservas/1'] as $privatePath) { [$status] = $request($privatePath); $assert($status === 403, 'Consulta de reservas exige rol administrador'); }
    $musicId = (int) $pdo->query("SELECT id FROM events WHERE slug = 'musica-ultimos-cupos'")->fetchColumn();
    [$status, $body] = $request('/api/reservas', ['csrf' => $logoutToken, 'event_id' => $musicId, 'quantity' => '1', 'price' => '0', 'total' => '0']);
    $created = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    $assert($status === 201 && isset($created['ticket_url']), 'Reserva AJAX por HTTP');
    $saved = $pdo->query('SELECT total FROM reservations WHERE id = ' . (int) $created['id'])->fetchColumn();
    $assert($saved === '8000.00', 'Precio manipulado ignorado por API');
    [$status, $body] = $request($created['ticket_url']);
    $assert($status === 200 && str_contains($body, '/qr.svg'), 'Ticket propio con imagen QR');
    [$status, $body] = $request('/api/experiencias');
    $history = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    $assert($status === 200 && str_contains($history['html'], $created['ticket_url']), 'Historial AJAX refleja reserva propia');
    [$status, $body, $headers] = $request($created['ticket_url'] . '/qr.svg');
    $assert($status === 200 && str_contains($body, '<svg ') && stripos(implode(' ', $headers), 'image/svg+xml') !== false, 'QR privado servido por HTTP');
    [$status] = $request('/salir');
    $assert($status === 405, 'Logout exige POST');
    [$status] = $request('/salir', ['csrf' => $token]);
    $assert($status === 403, 'Token anterior no cierra sesión');
    [$status] = $request('/salir', ['csrf' => $logoutToken]);
    $assert($status === 303, 'Logout HTTP válido');
    [$status] = $request('/cuenta');
    $assert($status === 303, 'Cuenta inaccesible tras salir');
    [$status, $body] = $request('/ingresar');
    [$status] = $request('/ingresar', ['csrf' => $csrf($body), 'email' => $credentials[0]['email'], 'password' => $credentials[0]['password']]);
    $assert($status === 303, 'Administrador ingresa por HTTP');
    [$status] = $request('/admin');
    $assert($status === 200, 'Administrador autorizado por HTTP');
    [$status, $body] = $request('/admin/reservas?user=http%40eventify.test&state=CONFIRMADA');
    $assert($status === 200 && str_contains($body, 'Usuario HTTP'), 'Reservas administrativas filtradas por correo y estado');
    [$status, $body] = $request('/admin/reservas/' . (int) $created['id']);
    $assert($status === 200 && str_contains($body, 'Precio histórico por entrada'), 'Administrador consulta detalle ajeno conservado');
    [$status] = $request('/admin/reservas?state=PUBLICADO'); $assert($status === 422, 'Filtro administrativo inválido rechazado');
    [$status] = $request('/admin/reservas/999999999'); $assert($status === 404, 'Detalle administrativo inexistente');
    [$status] = $request('/admin/reservas', ['csrf' => $logoutToken]); $assert($status === 405, 'Consulta administrativa solo lectura');
    [$status, $body] = $request('/admin/categorias');
    $adminToken = $csrf($body);
    [$status] = $request('/admin/categorias', ['name' => 'Categoría HTTP de prueba', 'slug' => 'http-admin-probe']);
    $assert($status === 403, 'Escritura admin requiere CSRF');
    [$status] = $request('/admin/categorias', ['csrf' => $adminToken, 'name' => 'Categoría HTTP de prueba', 'slug' => 'http-admin-probe', 'is_active' => '1']);
    $assert($status === 303 && (int) $pdo->query("SELECT COUNT(*) FROM categories WHERE slug = 'http-admin-probe'")->fetchColumn() === 1, 'Alta administrativa HTTP persistida');
    [$status, $body] = $request('/admin/eventos/nuevo', ['csrf' => $adminToken, 'name' => '<script>prueba</script>', 'slug' => '../invalido']);
    $assert($status === 422 && str_contains($body, '&lt;script&gt;') && !str_contains($body, '<script>prueba</script>'), 'Evento manipulado rechazado y valores escapados');

    [$status] = $request($created['ticket_url']);
    $assert($status === 404, 'Ticket de otra cuenta rechazado');
    [$status] = $request($created['ticket_url'] . '/qr.svg');
    $assert($status === 404, 'QR de otra cuenta rechazado');
} finally {
    proc_terminate($server);
    proc_close($server);
}
