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

echo $router->dispatch(new Request());
