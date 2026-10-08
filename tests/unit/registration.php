<?php
declare(strict_types=1);
require dirname(__DIR__, 2) . '/app/bootstrap.php';
use Eventify\Domain\RegistrationInput;
use Eventify\Security\Session;
$checks = 0;
$assert = static function (bool $ok) use (&$checks): void {
    if (!$ok) { fwrite(STDERR, "Validación de registro falló.\n"); exit(1); }
    ++$checks;
};
$base = ['full_name' => 'María de Prueba', 'email' => 'maria+prueba@example.test', 'password' => 'Clave-segura', 'password_confirmation' => 'Clave-segura'];
$assert(RegistrationInput::errors($base) === []);
$assert(RegistrationInput::email(' Persona@Example.test ') === 'persona@example.test');
foreach ([['full_name', ''], ['full_name', str_repeat('á', 151)], ['full_name', "Nombre\0"], ['email', 'incorrecto'], ['email', ['x']], ['password', 'corta'], ['password', str_repeat('a', 73)], ['password', "Clave\0segura"], ['password_confirmation', 'otra']] as [$key, $value]) {
    $input = $base;
    $input[$key] = $value;
    $assert(RegistrationInput::errors($input) !== []);
}
$input = $base;
$input['role'] = 'ADMINISTRADOR';
$assert(RegistrationInput::errors($input) === []);
$_SESSION = [];
$token = Session::token();
$assert(strlen($token) === 64 && Session::validToken($token));
$assert(!Session::validToken('incorrecto'));
$assert(!Session::validToken([$token]));
$assert(!Session::validToken(null));
echo 'Registro y CSRF: ' . $checks . " comprobaciones correctas.\n";
