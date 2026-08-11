<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ModuleController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Core\Request;
use App\Core\Router;

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/blog', [HomeController::class, 'blog']);
$router->get('/blog/{slug}', [HomeController::class, 'blogDetails']);
$router->post('/contact', [ContactController::class, 'store']);
$router->get('/resume', [HomeController::class, 'resume']);
$router->get('/sitemap.xml', [HomeController::class, 'sitemap']);
$router->get('/robots.txt', [HomeController::class, 'robots']);

$router->get('/admin/login', [AuthController::class, 'showLogin']);
$router->post('/admin/login', [AuthController::class, 'login']);
$router->post('/admin/logout', [AuthController::class, 'logout']);
$router->get('/admin', [DashboardController::class, 'index']);
$router->get('/admin/{module}', [ModuleController::class, 'index']);
$router->get('/admin/{module}/create', [ModuleController::class, 'create']);
$router->post('/admin/{module}/store', [ModuleController::class, 'store']);
$router->get('/admin/{module}/{id}/edit', [ModuleController::class, 'edit']);
$router->post('/admin/{module}/{id}/update', [ModuleController::class, 'update']);
$router->post('/admin/{module}/{id}/delete', [ModuleController::class, 'delete']);
$router->post('/admin/{module}/reorder', [ModuleController::class, 'reorder']);

try {
    echo $router->dispatch(new Request());
} catch (Throwable $exception) {
    error_log((string) $exception);

    $config = app('config') ?? [];
    $isLocal = ($config['env'] ?? 'production') === 'local';
    http_response_code(500);

    if ($isLocal) {
        echo '<pre style="white-space:pre-wrap;font:14px/1.5 monospace;padding:24px;color:#f8fbf9;background:#071014">';
        echo e($exception->getMessage()) . "\n\n" . e($exception->getTraceAsString());
        echo '</pre>';
        exit;
    }

    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Service unavailable</title></head><body style="margin:0;min-height:100vh;display:grid;place-items:center;background:#071014;color:#effff8;font-family:system-ui,sans-serif"><main style="max-width:560px;padding:24px"><p style="color:#4ee1a0;text-transform:uppercase;font-weight:800;letter-spacing:.12em">Portfolio CMS</p><h1>Service temporarily unavailable.</h1><p style="color:#9fb4bc;line-height:1.7">The site could not connect to its database. Please check the production environment variables and database import.</p></main></body></html>';
}
