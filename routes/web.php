<?php

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ProductController as AdminProductController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\RoleController;
use App\Controllers\Admin\ActivityLogController;
use App\Controllers\Admin\ModuleController;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;

/** @var Router $router */

$router->get('/', [HomeController::class, 'index']);

$router->get('/products', [ProductController::class, 'index']);
$router->get('/products/{slug}', [ProductController::class, 'show']);
$router->get('/categories/{slug}', [CategoryController::class, 'show']);

$router->get('/cart', [CartController::class, 'index']);
$router->post('/cart/add', [CartController::class, 'add']);
$router->post('/cart/update', [CartController::class, 'update']);
$router->post('/cart/remove', [CartController::class, 'remove']);

$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/admin', [DashboardController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/dashboard', [DashboardController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class]);

$router->get('/admin/products', [AdminProductController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/products/create', [AdminProductController::class, 'create'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/admin/products', [AdminProductController::class, 'store'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/products/{id}/edit', [AdminProductController::class, 'edit'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/admin/products/{id}', [AdminProductController::class, 'update'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->delete('/admin/products/{id}', [AdminProductController::class, 'destroy'], [AuthMiddleware::class, AdminMiddleware::class]);

$router->get('/admin/users', [UserController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/users/create', [UserController::class, 'create'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/admin/users', [UserController::class, 'store'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/users/{id}/edit', [UserController::class, 'edit'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/admin/users/{id}', [UserController::class, 'update'], [AuthMiddleware::class, AdminMiddleware::class]);

$router->get('/admin/roles', [RoleController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/roles/create', [RoleController::class, 'create'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/admin/roles', [RoleController::class, 'store'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/roles/{id}/edit', [RoleController::class, 'edit'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/admin/roles/{id}', [RoleController::class, 'update'], [AuthMiddleware::class, AdminMiddleware::class]);

$router->get('/admin/activity-log', [ActivityLogController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class]);

$router->get('/admin/modules', [ModuleController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/admin/modules/update', [ModuleController::class, 'update'], [AuthMiddleware::class, AdminMiddleware::class]);
