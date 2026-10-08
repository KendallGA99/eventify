<?php
declare(strict_types=1);
// Servicio ejecutado exclusivamente en eventify_test; fixtures nuevas sin modificar el negocio local.
$admin = new Eventify\Domain\AdminService($pdo);
$category = $admin->category(null, ['name' => 'Categoría administrativa de prueba', 'slug' => 'admin-probe', 'is_active' => '1']);
$input = ['name' => 'Evento administrativo de prueba', 'slug' => 'admin-probe-event', 'description' => 'Fixture independiente', 'category_id' => (string) $category, 'image_path' => '/assets/images/cultura.svg', 'starts_at' => '2090-06-20T12:00:45', 'location' => 'Lugar ficticio', 'capacity' => '10', 'price' => '100.25', 'status' => 'PUBLICADO', 'latitude' => '', 'longitude' => ''];
$id = $admin->event(null, $input);
$assert((int) $pdo->query('SELECT capacity FROM events WHERE id = ' . $id)->fetchColumn() === 10, 'Crear evento validado');
$userId = (int) $pdo->query("SELECT id FROM users WHERE email = 'usuario@eventify.test'")->fetchColumn();
$reserve = (new Eventify\Domain\ReservationService($pdo))->create($userId, $id, '3');
$expectAdminConflict = static function (Closure $action, string $label) use ($assert): void {
    $rejected = false; try { $action(); } catch (Eventify\Domain\BusinessError $e) { $rejected = $e->httpStatus === 409; }
    $assert($rejected, $label);
};
$expectAdminConflict(static fn () => $admin->event($id, [...$input, 'capacity' => '2']), 'Capacidad inferior a confirmadas rechazada');
$expectAdminConflict(static fn () => $admin->event($id, [...$input, 'starts_at' => '2090-06-21T12:00:45']), 'Fecha con historial protegida');
$expectAdminConflict(static fn () => $admin->event($id, [...$input, 'status' => 'BORRADOR']), 'Evento reservado no vuelve a borrador');
$expectAdminConflict(static fn () => $admin->event($id, [...$input, 'slug' => 'nuevo-enlace']), 'Slug existente se conserva');
$admin->event($id, [...$input, 'capacity' => '3', 'price' => '200.00']);
$assert($pdo->query('SELECT unit_price FROM reservations WHERE id = ' . $reserve['id'])->fetchColumn() === '100.25', 'Editar precio no altera reserva histórica');
$admin->category($category, ['name' => 'Categoría administrativa de prueba', 'slug' => 'admin-probe', 'is_active' => '0']);
$assert((int) $pdo->query('SELECT category_id FROM events WHERE id = ' . $id)->fetchColumn() === $category, 'Desactivar categoría conserva eventos');
$rejected = false; try { $admin->event(null, [...$input, 'slug' => 'inactive-category-probe']); } catch (Eventify\Domain\BusinessError) { $rejected = true; }
$assert($rejected, 'Crear evento con categoría inactiva rechazado');
$admin->event($id, [...$input, 'price' => '200.00', 'capacity' => '3']);
$assert(true, 'Evento conserva categoría inactiva existente');
$expectAdminConflict(static fn () => $admin->state($id, 'FINALIZADO'), 'No finalizar antes del inicio');
$admin->state($id, 'CANCELADO');
$admin->state($id, 'CANCELADO');
$assert($pdo->query('SELECT status FROM events WHERE id = ' . $id)->fetchColumn() === 'CANCELADO', 'Cancelación idempotente');
$assert($pdo->query('SELECT status FROM reservations WHERE id = ' . $reserve['id'])->fetchColumn() === 'CONFIRMADA', 'Cancelar evento conserva reserva histórica');
$assert((int) $pdo->query('SELECT COUNT(*) FROM tickets WHERE reservation_id = ' . $reserve['id'])->fetchColumn() === 1, 'Cancelar evento conserva ticket');
$expectAdminConflict(static fn () => (new Eventify\Domain\ReservationService($pdo))->create($userId, $id, '1'), 'Reserva nueva después de cancelar rechazada');
$expectAdminConflict(static fn () => $admin->event($id, $input), 'Evento cancelado no se reactiva por edición');
// Fecha pasada solo en una fixture nueva para verificar transición FINALIZADO.
$pdo->exec("INSERT INTO events (category_id, slug, name, description, image_path, starts_at, location, capacity, price, status) VALUES ($category, 'past-admin-probe', 'Pasado de prueba', 'Fixture', '/assets/icon.svg', UTC_TIMESTAMP() - INTERVAL 1 DAY, 'Ficticio', 1, 0, 'PUBLICADO')");
$pastId = (int) $pdo->lastInsertId();
$admin->state($pastId, 'FINALIZADO');
$assert($pdo->query('SELECT status FROM events WHERE id = ' . $pastId)->fetchColumn() === 'FINALIZADO', 'Finalizar evento pasado');
