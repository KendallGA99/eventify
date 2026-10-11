<?php
declare(strict_types=1);
namespace Eventify\Http;

use Eventify\Domain\AdminService;
use Eventify\Domain\AdminReservationFilter;
use Eventify\Infrastructure\AdminReadRepository;
use Eventify\Domain\BusinessError;
use Eventify\Domain\ReservationService;
use Eventify\Infrastructure\Database;
use Eventify\Security\Auth;
use Eventify\Security\Session;

final class AdminController
{
    public function respond(string $path, string $method): void
    {
        header('Cache-Control: no-store');
        Session::start();
        $pdo = Database::connect();
        $user = (new Auth($pdo))->user();
        if ($user === null) { header('Location: /ingresar', true, 303); return; }
        if ($user['role'] !== 'ADMINISTRADOR') {
            http_response_code(403);
            require PROJECT_ROOT . '/app/Views/access-denied.php';
            return;
        }
        if ($method === 'POST' && !Session::validToken($_POST['csrf'] ?? null)) {
            ErrorPage::respond(403, 'El formulario venció.', 'Actualice el formulario antes de intentarlo de nuevo.');
            return;
        }
        $errors = [];
        $record = null;
        $rows = [];
        $categories = [];
        $hasMore = false;
        $number = 1;
        $view = 'home';
        $id = null;
        $dashboard = null;
        $filter = null;
        $search = '';
        $users = new \Eventify\Infrastructure\AdminUserRepository($pdo);
        if ($path === '/admin/usuarios') { $view = 'users'; }
        elseif (preg_match('#\A/admin/usuarios/([1-9][0-9]{0,9})/(administrador|promover)\z#', $path, $match)) {
            $view = 'user-promotion';
            $id = (int) $match[1];
            $record = $users->find($id);
            if ($record === null) { $this->missing(); return; }
        }
        elseif ($path === '/admin/categorias') { $view = 'categories'; }
        elseif (preg_match('#\A/admin/categorias/([1-9][0-9]{0,9})/editar\z#', $path, $match)) { $view = 'category'; $id = (int) $match[1]; }
        elseif ($path === '/admin/eventos') { $view = 'events'; }
        elseif ($path === '/admin/eventos/nuevo') { $view = 'event'; }
        elseif (preg_match('#\A/admin/eventos/([1-9][0-9]{0,9})/(editar|estado)\z#', $path, $match)) { $view = 'event'; $id = (int) $match[1]; }
        elseif ($path === '/admin/reservas') { $view = 'reservations'; }
        elseif (preg_match('#\A/admin/reservas/([1-9][0-9]{0,9})\z#', $path, $match)) { $view = 'reservation'; $id = (int) $match[1]; }
        elseif ($path !== '/admin') { $this->missing(); return; }
        if ($id !== null && in_array($view, ['event', 'category'], true)) {
            $table = $view === 'category' ? 'categories' : 'events';
            $query = $pdo->prepare('SELECT * FROM ' . $table . ' WHERE id = ?'); $query->execute([$id]); $record = $query->fetch();
            if (!$record) { $this->missing(); return; }
        }
        if ($method === 'POST') {
            try {
                $service = new AdminService($pdo);
                if ($view === 'user-promotion') {
                    if (!(new Auth($pdo))->allowAttempt('admin-promotion:' . $user['id'], $_SERVER['REMOTE_ADDR'] ?? 'unknown')) {
                        throw new BusinessError('Demasiados intentos. Espere 15 minutos antes de intentarlo de nuevo.', 429);
                    }
                    (new \Eventify\Domain\AdminUserService($pdo))->promote((int) $user['id'], $id, $_POST['password'] ?? null, $_POST['confirm'] ?? null);
                    $destination = '/admin/usuarios';
                }
                elseif (in_array($view, ['category', 'categories'], true)) { $service->category($id, $_POST); $destination = '/admin/categorias'; }
                elseif ($view === 'event') {
                    if (str_ends_with($path, '/estado')) { $service->state($id, $_POST['status'] ?? null); }
                    else { $service->event($id, $_POST); }
                    $destination = '/admin/eventos';
                } else {
                    ErrorPage::respond(405, 'Esta acción no está disponible.', 'Abra de nuevo la página y utilice sus enlaces o botones para continuar.');
                    return;
                }
                header('Location: ' . $destination . '?guardado=ok', true, 303); return;
            } catch (BusinessError $exception) { http_response_code($exception->httpStatus); $errors[] = $exception->getMessage(); }
        }
        // Una transición fallida vuelve a consultar el registro; no copiar sus campos incompletos al formulario.
        $values = $method === 'POST' && !str_ends_with($path, '/estado') ? $_POST : ($record ?? []);
        if (in_array($view, ['categories', 'events'], true)) {
            try { $number = ReservationService::quantity($_GET['page'] ?? '1'); if ($number > 10000) { throw new BusinessError('Página fuera de rango.'); } }
            catch (BusinessError $e) { http_response_code(422); $errors[] = $e->getMessage(); $number = 1; }
            $sql = $view === 'categories' ? 'SELECT * FROM categories ORDER BY name, id' : 'SELECT e.*, c.name AS category FROM events e JOIN categories c ON c.id = e.category_id ORDER BY e.starts_at DESC, e.id DESC';
            $query = $pdo->prepare($sql . ' LIMIT 51 OFFSET ?'); $query->bindValue(1, ($number - 1) * 50, \PDO::PARAM_INT); $query->execute();
            $rows = $query->fetchAll(); $hasMore = count($rows) > 50; $rows = array_slice($rows, 0, 50);
        }
        if (in_array($view, ['home', 'reservations', 'reservation'], true)) {
            $read = new AdminReadRepository($pdo);
            if ($view === 'home') { $dashboard = $read->dashboard(); }
            elseif ($view === 'reservation') { $record = $read->reservation($id); if ($record === null) { $this->missing(); return; } }
            else {
                try { $filter = AdminReservationFilter::fromQuery($_GET); }
                catch (BusinessError $e) { http_response_code($e->httpStatus); $errors[] = $e->getMessage(); $filter = AdminReservationFilter::fromQuery([]); }
                $result = $errors === [] ? $read->reservations($filter) : ['rows' => [], 'hasMore' => false];
                $rows = $result['rows']; $hasMore = $result['hasMore']; $number = $filter->page;
            }
        }
        if ($view === 'users') {
            try {
                $result = $users->search($_GET['q'] ?? '', $_GET['page'] ?? '1');
                $rows = $result['rows'];
                $hasMore = $result['hasMore'];
                $number = $result['page'];
                $search = $result['search'];
            } catch (BusinessError $error) {
                http_response_code($error->httpStatus);
                $errors[] = $error->getMessage();
            }
        }
        if ($view === 'event') { $categories = $pdo->query('SELECT id, name, is_active FROM categories ORDER BY name, id')->fetchAll(); }
        require PROJECT_ROOT . '/app/Views/admin.php';
    }

    private function missing(): void { http_response_code(404); require PROJECT_ROOT . '/app/Views/not-found.php'; }
}
