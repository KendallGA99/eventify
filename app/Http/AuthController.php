<?php
declare(strict_types=1);
namespace Eventify\Http;

use Eventify\Domain\RegistrationInput;
use Eventify\Infrastructure\Database;
use Eventify\Security\Auth;
use Eventify\Security\Session;

final class AuthController
{
    public function respond(string $path, string $method): void
    {
        Session::start();
        $errors = [];
        $values = ['full_name' => '', 'email' => ''];
        if ($method === 'POST' && !Session::validToken($_POST['csrf'] ?? null)) {
            ErrorPage::respond(403, 'El formulario venció.', 'La sesión del formulario venció. Vuelva a abrir la página e inténtelo de nuevo.');
            return;
        }
        if ($path === '/salir') {
            Session::logout();
            $this->redirect('/');
        }
        $auth = new Auth(Database::connect());
        $user = $auth->user();
        if ($path === '/cuenta') {
            if ($user === null) { $this->redirect('/ingresar'); }
            $title = 'Mi cuenta';
            $content = 'Su cuenta está lista. Consulte sus experiencias para ver las reservas y los tickets.';
            require PROJECT_ROOT . '/app/Views/account.php';
            return;
        }
        if ($user !== null) { $this->redirect('/cuenta'); }
        if ($method === 'POST') {
            $values = ['full_name' => trim(RegistrationInput::text($_POST, 'full_name')), 'email' => RegistrationInput::email(RegistrationInput::text($_POST, 'email'))];
            if (!$auth->allowAttempt($path, $_SERVER['REMOTE_ADDR'] ?? 'unknown')) {
                http_response_code(429);
                header('Retry-After: 900');
                $errors = ['Se realizaron demasiados intentos. Espere 15 minutos antes de intentarlo nuevamente.'];
            } elseif ($path === '/registro') {
                $errors = $auth->register($_POST);
                if ($errors === []) { $this->redirect('/ingresar?registro=ok'); }
                http_response_code(422);
            } else {
                if ($auth->authenticate($values['email'], RegistrationInput::text($_POST, 'password'))) { $this->redirect('/cuenta'); }
                http_response_code(422);
                $errors = ['El correo o la contraseña no son correctos.'];
            }
        }
        $register = $path === '/registro';
        require PROJECT_ROOT . '/app/Views/auth.php';
    }

    private function redirect(string $path): never
    {
        header('Location: ' . $path, true, 303);
        exit;
    }
}
