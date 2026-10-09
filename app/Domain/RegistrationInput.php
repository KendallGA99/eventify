<?php
declare(strict_types=1);
namespace Eventify\Domain;

final class RegistrationInput
{
    public static function text(array $input, string $key): string
    {
        return isset($input[$key]) && is_string($input[$key]) ? $input[$key] : '';
    }

    public static function email(string $email): string
    {
        return strtolower(trim($email));
    }

    /** @return list<string> */
    public static function nameErrors(array $input): array
    {
        $errors = [];
        $rawName = self::text($input, 'full_name');
        $name = trim($rawName);
        $length = preg_match_all('/./us', $name);
        if ($length === false || $length < 2 || $length > 150 || preg_match('/[\x00-\x1F\x7F]/', $rawName)) {
            $errors[] = 'Escriba un nombre de 2 a 150 caracteres.';
        }
        return $errors;
    }

    /** @return list<string> */
    public static function errors(array $input): array
    {
        $errors = self::nameErrors($input);
        $email = self::email(self::text($input, 'email'));
        if (strlen($email) > 254 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors[] = 'Escriba un correo electrónico válido.';
        }
        $password = self::text($input, 'password');
        if (strlen($password) < 8 || strlen($password) > 72 || str_contains($password, "\0")) {
            $errors[] = 'La contraseña debe tener entre 8 y 72 bytes y no incluir caracteres nulos.';
        }
        if ($password !== self::text($input, 'password_confirmation')) {
            $errors[] = 'Las contraseñas no coinciden.';
        }
        return $errors;
    }
}
