<?php

declare(strict_types=1);

namespace Eventify\Infrastructure;

use DateTimeImmutable;
use DateTimeZone;
use Eventify\Support\Config;
use PDO;
use RuntimeException;
use Throwable;

final class ExampleInstaller
{
    private const SEED_KEY = 'catalogo_entrega_v1';

    public function __construct(private PDO $pdo, private string $credentialsPath)
    {
    }

    public function run(bool $publicEvaluation = false): bool
    {
        $environment = Config::get('APP_ENV');
        if (($publicEvaluation && $environment !== 'production')
            || (!$publicEvaluation && !in_array($environment, ['local', 'test'], true))) {
            throw new RuntimeException('La preparación pública requiere una operación explícita de evaluación.');
        }
        if ($this->pdo->inTransaction()) {
            throw new RuntimeException('La preparación necesita su propia transacción.');
        }
        $lockName = 'eventify:examples:' . substr(hash('sha256', (string) $this->pdo->query('SELECT DATABASE()')->fetchColumn()), 0, 32);
        $lock = $this->pdo->prepare('SELECT GET_LOCK(?, 10)');
        $lock->execute([$lockName]);
        if ((int) $lock->fetchColumn() !== 1) {
            throw new RuntimeException('Otra preparación está en ejecución.');
        }
        $temporaryPath = null;
        $createdCredentials = false;
        try {
            $this->pdo->beginTransaction();
            $mark = $this->pdo->prepare('SELECT seed_key FROM demo_seed_runs WHERE seed_key = ?');
            $mark->execute([self::SEED_KEY]);
            if ($mark->fetchColumn() !== false) {
                $this->pdo->rollBack();
                return false;
            }
            // Una instalación ya utilizada se conserva: el arranque no modifica su catálogo.
            if (!$publicEvaluation) {
                foreach (['users', 'events', 'categories', 'reservations'] as $table) {
                    if ((int) $this->pdo->query('SELECT COUNT(*) FROM ' . $table)->fetchColumn() !== 0) {
                        $this->pdo->rollBack();
                        return false;
                    }
                }
            }
            if (file_exists($this->credentialsPath)) {
                throw new RuntimeException('Ya existe un archivo de acceso sin marca de preparación; revise la instalación.');
            }
            $fixtures = json_decode((string) file_get_contents(PROJECT_ROOT . '/database/example-events.json'), true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($fixtures) || count($fixtures) !== 8) {
                throw new RuntimeException('El catálogo de ejemplo está incompleto.');
            }
            $roles = $this->pdo->query('SELECT code, id FROM roles')->fetchAll(PDO::FETCH_KEY_PAIR);
            $credentials = [];
            foreach ([['admin.entrega@eventify.test', 'Administrador Eventify', 'ADMINISTRADOR'],
                ['visitante.entrega@eventify.test', 'Visitante Eventify', 'USUARIO']] as [$email, $name, $role]) {
                if (!isset($roles[$role])) {
                    throw new RuntimeException('Faltan las migraciones de roles.');
                }
                $password = bin2hex(random_bytes(16));
                $insert = $this->pdo->prepare('INSERT INTO users (role_id, full_name, email, password_hash) VALUES (?, ?, ?, ?)');
                $insert->execute([$roles[$role], $name, $email, password_hash($password, PASSWORD_DEFAULT)]);
                $credentials[] = ['email' => $email, 'password' => $password, 'role' => $role];
            }
            if ($publicEvaluation) {
                // Retirar del catálogo conserva reservas, tickets y precios históricos.
                $this->pdo->query('SELECT id FROM events ORDER BY id FOR UPDATE')->fetchAll();
                $this->pdo->exec("UPDATE events e SET status = CASE WHEN EXISTS (SELECT 1 FROM reservations r WHERE r.event_id = e.id) THEN 'CANCELADO' ELSE 'BORRADOR' END WHERE e.status IN ('PUBLICADO', 'BORRADOR')");
            }
            $categories = [];
            $today = new DateTimeImmutable('today', new DateTimeZone('America/Costa_Rica'));
            foreach ($fixtures as $event) {
                $slug = $event['category_slug'];
                if (!isset($categories[$slug])) {
                    $find = $this->pdo->prepare('SELECT id, is_active FROM categories WHERE slug = ? FOR UPDATE');
                    $find->execute([$slug]);
                    $category = $find->fetch();
                    if ($category !== false) {
                        if (!(bool) $category['is_active']) {
                            throw new RuntimeException('Una categoría de ejemplo está inactiva; no se sobrescribió.');
                        }
                        $categories[$slug] = (int) $category['id'];
                    } else {
                        $insert = $this->pdo->prepare('INSERT INTO categories (name, slug) VALUES (?, ?)');
                        $insert->execute([$event['category'], $slug]);
                        $categories[$slug] = (int) $this->pdo->lastInsertId();
                    }
                }
                $startsAt = $today->modify('+' . (int) $event['days'] . ' days')
                    ->modify($event['time'])->setTimezone(new DateTimeZone('UTC'));
                $insert = $this->pdo->prepare("INSERT INTO events (category_id, slug, name, description, image_path, starts_at, location, latitude, longitude, capacity, price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'PUBLICADO')");
                $insert->execute([$categories[$slug], $event['slug'], $event['name'], $event['description'],
                    $event['image_path'], $startsAt->format('Y-m-d H:i:s'), $event['location'],
                    $event['latitude'], $event['longitude'], $event['capacity'], $event['price']]);
            }
            $insert = $this->pdo->prepare('INSERT INTO demo_seed_runs (seed_key) VALUES (?)');
            $insert->execute([self::SEED_KEY]);
            $directory = dirname($this->credentialsPath);
            if (!is_dir($directory) || !is_writable($directory)) {
                throw new RuntimeException('No está disponible el directorio privado para guardar el acceso.');
            }
            $temporaryPath = tempnam($directory, '.examples-');
            if ($temporaryPath === false || !chmod($temporaryPath, 0600)
                || file_put_contents($temporaryPath, json_encode($credentials, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)) === false
                || !rename($temporaryPath, $this->credentialsPath)) {
                throw new RuntimeException('No se pudo guardar el acceso privado; se revierte la preparación.');
            }
            $temporaryPath = null;
            $createdCredentials = true;
            $this->pdo->commit();
            return true;
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            if ($temporaryPath !== null && is_file($temporaryPath)) {
                unlink($temporaryPath);
            }
            if ($createdCredentials && is_file($this->credentialsPath)) {
                unlink($this->credentialsPath);
            }
            throw $exception;
        } finally {
            $release = $this->pdo->prepare('SELECT RELEASE_LOCK(?)');
            $release->execute([$lockName]);
        }
    }
}
