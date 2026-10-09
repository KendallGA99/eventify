<?php
declare(strict_types=1);
namespace Eventify\Http;

use Eventify\Domain\BusinessError;
use Eventify\Domain\CatalogFilter;
use Eventify\Infrastructure\CatalogRepository;
use Eventify\Infrastructure\Database;
use Eventify\Security\Auth;
use Eventify\Security\Session;

final class CatalogController
{
    public function respond(bool $json): void
    {
        header('Cache-Control: no-store');
        $error = '';
        try { $filter = CatalogFilter::fromQuery($_GET); }
        catch (BusinessError $exception) {
            http_response_code($exception->httpStatus);
            if ($json) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['message' => $exception->getMessage()], JSON_THROW_ON_ERROR);
                return;
            }
            $error = $exception->getMessage();
            $filter = CatalogFilter::fromQuery([]);
        }
        $pdo = Database::connect();
        $repository = new CatalogRepository($pdo);
        $result = $error === '' ? $repository->search($filter) : ['rows' => [], 'hasMore' => false];
        $rows = $result['rows'];
        $hasMore = $result['hasMore'];
        if ($json) {
            require PROJECT_ROOT . '/app/Views/catalog-helpers.php';
            ob_start();
            require PROJECT_ROOT . '/app/Views/catalog-results.php';
            $html = ob_get_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['html' => $html, 'page' => $filter->page, 'message' => count($rows) . (count($rows) === 1 ? ' evento en esta página.' : ' eventos en esta página.')], JSON_THROW_ON_ERROR);
            return;
        }
        Session::start();
        $user = (new Auth($pdo))->user();
        $categories = $repository->categories();
        $page = 'catalog';
        require PROJECT_ROOT . '/app/Views/experiences.php';
    }
}
