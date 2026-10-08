<?php

declare(strict_types=1);

namespace Eventify\Infrastructure;

use DateTimeImmutable;
use Eventify\Support\Config;
use PDO;
use RuntimeException;
use Throwable;

final class DemoSeeder
{
    public function __construct(private PDO $pdo, private string $credentialsPath)
    {
    }

    public function run(): bool
    {
        if (!in_array(Config::get('APP_ENV'), ['local', 'test'], true)) {
            throw new RuntimeException('La demostración solo se permite explícitamente en local o en pruebas aisladas.');
        }
        if ($this->pdo->inTransaction()) {
            throw new RuntimeException('Ejecute la demostración fuera de otra transacción.');
        }
        $lockName = 'eventify:demo:' . substr(hash('sha256', (string) $this->pdo->query('SELECT DATABASE()')->fetchColumn()), 0, 32);
        $lock = $this->pdo->prepare('SELECT GET_LOCK(?, 10)');
        $lock->execute([$lockName]);
        if ((int) $lock->fetchColumn() !== 1) {
            throw new RuntimeException('Otra inicialización de demostración está en ejecución.');
        }
        $temporaryPath = null;
        $publishedCredentials = false;
        try {
            if ($this->pdo->query("SELECT seed_key FROM demo_seed_runs WHERE seed_key = 'demo_v1'")->fetchColumn() !== false) {
                return false;
            }
            if (is_file($this->credentialsPath)) {
                throw new RuntimeException('Ya existe un archivo de credenciales sin marca de demostración. Revisarlo antes de continuar; no se sobrescribirá.');
            }
            $this->pdo->beginTransaction();
            $credentials = $this->populate();
            $mark = $this->pdo->prepare('INSERT INTO demo_seed_runs (seed_key) VALUES (?)');
            $mark->execute(['demo_v1']);
            $directory = dirname($this->credentialsPath);
            if (!is_dir($directory) || !is_writable($directory)) {
                throw new RuntimeException('El almacenamiento privado no está disponible; no se guardaron los datos demo.');
            }
            $temporaryPath = tempnam($directory, '.demo-');
            if ($temporaryPath === false || !chmod($temporaryPath, 0600)
                || file_put_contents($temporaryPath, json_encode($credentials, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)) === false
                || !rename($temporaryPath, $this->credentialsPath)) {
                throw new RuntimeException('No fue posible conservar las credenciales locales de demostración.');
            }
            $publishedCredentials = true;
            $temporaryPath = null;
            $this->pdo->commit();
            return true;
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            // Solo limpiar archivos nuevos de esta operación, nunca archivos preexistentes.
            if ($temporaryPath !== null && is_file($temporaryPath)) {
                unlink($temporaryPath);
            }
            if ($publishedCredentials && is_file($this->credentialsPath)) {
                unlink($this->credentialsPath);
            }
            throw $exception;
        } finally {
            $release = $this->pdo->prepare('SELECT RELEASE_LOCK(?)');
            $release->execute([$lockName]);
        }
    }

    /** @return list<array{email: string, password: string, role: string}> */
    private function populate(): array
    {
        $roles = $this->pdo->query('SELECT code, id FROM roles')->fetchAll(PDO::FETCH_KEY_PAIR);
        if (!isset($roles['ADMINISTRADOR'], $roles['USUARIO'])) {
            throw new RuntimeException('Faltan roles del sistema. Ejecute las migraciones.');
        }
        $users = [];
        $credentials = [];
        foreach ([['admin@eventify.test', 'Administrador de prueba', 'ADMINISTRADOR'],
            ['usuario@eventify.test', 'Usuario de prueba', 'USUARIO'],
            ['otro@eventify.test', 'Otro usuario de prueba', 'USUARIO']] as [$email, $name, $role]) {
            $password = bin2hex(random_bytes(16));
            $insert = $this->pdo->prepare('INSERT INTO users (role_id, full_name, email, password_hash) VALUES (?, ?, ?, ?)');
            $insert->execute([$roles[$role], $name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            $users[$email] = (int) $this->pdo->lastInsertId();
            $credentials[] = ['email' => $email, 'password' => $password, 'role' => $role];
        }
        $categories = [];
        foreach (['tecnologia' => 'Tecnología', 'cultura' => 'Cultura', 'gastronomia' => 'Gastronomía', 'deporte' => 'Deporte'] as $slug => $name) {
            $insert = $this->pdo->prepare('INSERT INTO categories (name, slug) VALUES (?, ?)');
            $insert->execute([$name, $slug]);
            $categories[$slug] = (int) $this->pdo->lastInsertId();
        }
        $now = new DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $events = [];
        $fixtures = [
            ['taller-sql', 'Taller de SQL', 'tecnologia', '+7 days', 30, '15000.00', 'PUBLICADO'],
            ['encuentro-tecnologia', 'Encuentro de tecnología', 'tecnologia', '+10 days', 40, '0.00', 'PUBLICADO'],
            ['musica-ultimos-cupos', 'Música: últimos espacios', 'cultura', '+8 days', 10, '8000.00', 'PUBLICADO'],
            ['cocina-agotado', 'Cocina: entradas agotadas', 'gastronomia', '+9 days', 3, '12000.00', 'PUBLICADO'],
            ['carrera-borrador', 'Carrera en preparación', 'deporte', '+14 days', 20, '5000.00', 'BORRADOR'],
            ['cultura-cancelado', 'Encuentro cultural cancelado', 'cultura', '+12 days', 20, '7000.00', 'CANCELADO'],
            ['deporte-finalizado', 'Actividad deportiva finalizada', 'deporte', '-2 days', 40, '4000.00', 'FINALIZADO'],
            ['taller-proximo', 'Taller próximo a iniciar', 'tecnologia', '+12 hours', 10, '3000.00', 'PUBLICADO'],
        ];
        foreach ($fixtures as [$slug, $name, $category, $relativeDate, $capacity, $price, $status]) {
            $insert = $this->pdo->prepare('INSERT INTO events (category_id, slug, name, description, image_path, starts_at, location, latitude, longitude, capacity, price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $insert->execute([$categories[$category], $slug, $name, 'Experiencia ficticia para evaluación y pruebas de Eventify.', '/assets/icon.svg', $now->modify($relativeDate)->format('Y-m-d H:i:s'), 'San José, Costa Rica (ubicación de demostración)', '9.928100', '-84.090700', $capacity, $price, $status]);
            $events[$slug] = (int) $this->pdo->lastInsertId();
        }
        foreach ([['taller-sql', 'usuario@eventify.test', 2, 'CONFIRMADA'],
            ['taller-sql', 'otro@eventify.test', 1, 'CANCELADA'],
            ['musica-ultimos-cupos', 'usuario@eventify.test', 8, 'CONFIRMADA'],
            ['cocina-agotado', 'otro@eventify.test', 3, 'CONFIRMADA'],
            ['cultura-cancelado', 'usuario@eventify.test', 2, 'CONFIRMADA'],
            ['deporte-finalizado', 'otro@eventify.test', 5, 'CONFIRMADA'],
            ['taller-proximo', 'usuario@eventify.test', 1, 'CONFIRMADA']] as [$slug, $email, $quantity, $status]) {
            $insert = $this->pdo->prepare('INSERT INTO reservations (user_id, event_id, code, quantity, unit_price, total, status, cancelled_at) SELECT ?, id, ?, ?, price, price * ?, ?, ? FROM events WHERE id = ?');
            $insert->execute([$users[$email], bin2hex(random_bytes(16)), $quantity, $quantity, $status, $status === 'CANCELADA' ? $now->format('Y-m-d H:i:s') : null, $events[$slug]]);
            $reservationId = (int) $this->pdo->lastInsertId();
            $ticket = $this->pdo->prepare('INSERT INTO tickets (reservation_id, code) VALUES (?, ?)');
            $ticket->execute([$reservationId, bin2hex(random_bytes(32))]);
        }
        return $credentials;
    }
}
