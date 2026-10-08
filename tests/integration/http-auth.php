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
    [$status, , $headers] = $request('/admin');
    $assert($status === 303, 'Visitante redirigido al ingreso');
    $assert(stripos(implode(' ', $headers), 'HttpOnly') !== false && stripos(implode(' ', $headers), 'SameSite=Lax') !== false, 'Cookie inicial protegida');
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
    [$status] = $request('/ingresar', ['csrf' => $token, 'email' => $input['email'], 'password' => $input['password']]);
    $assert($status === 303 && $cookie !== $oldCookie, 'Login HTTP renueva sesión');
    [$status, $body] = $request('/cuenta');
    $assert($status === 200 && str_contains($body, 'Usuario HTTP'), 'Cuenta autenticada');
    $logoutToken = $csrf($body);
    [$status] = $request('/admin');
    $assert($status === 403, 'Usuario rechaza URL administrativa directa');
    $musicId = (int) $pdo->query("SELECT id FROM events WHERE slug = 'musica-ultimos-cupos'")->fetchColumn();
    [$status, $body] = $request('/api/reservas', ['csrf' => $logoutToken, 'event_id' => $musicId, 'quantity' => '1', 'price' => '0', 'total' => '0']);
    $created = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    $assert($status === 201 && isset($created['ticket_url']), 'Reserva AJAX por HTTP');
    $saved = $pdo->query('SELECT total FROM reservations WHERE id = ' . (int) $created['id'])->fetchColumn();
    $assert($saved === '8000.00', 'Precio manipulado ignorado por API');
    [$status, $body] = $request($created['ticket_url']);
    $assert($status === 200 && str_contains($body, '/qr.svg'), 'Ticket propio con imagen QR');
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
    [$status] = $request($created['ticket_url']);
    $assert($status === 404, 'Ticket de otra cuenta rechazado');
    [$status] = $request($created['ticket_url'] . '/qr.svg');
    $assert($status === 404, 'QR de otra cuenta rechazado');
} finally {
    proc_terminate($server);
    proc_close($server);
}
