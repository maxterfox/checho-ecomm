<?php

use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\RoleController;
use App\Controllers\Admin\FieldController;
use App\Controllers\Admin\ProductController as AdminProductController;
use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\OrderController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\ActivityLogController;
use App\Controllers\Admin\SettingsController;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;
use App\Middleware\PermissionMiddleware;

/** @var \App\Core\Router $router */

$router->get('/', [HomeController::class, 'index']);
$router->get('/products', [ProductController::class, 'index']);
$router->get('/products/{slug}', [ProductController::class, 'show']);

$router->get('/cart', [CartController::class, 'index']);
$router->post('/cart/add', [CartController::class, 'add']);
$router->post('/cart/update', [CartController::class, 'update']);
$router->post('/cart/remove/{id}', [CartController::class, 'remove']);

$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/admin', [DashboardController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class]);

$router->get('/admin/roles', [RoleController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'roles', 'view']]);
$router->get('/admin/roles/create', [RoleController::class, 'create'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'roles', 'modify']]);
$router->post('/admin/roles', [RoleController::class, 'store'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'roles', 'modify']]);
$router->get('/admin/roles/edit/{id}', [RoleController::class, 'edit'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'roles', 'modify']]);
$router->post('/admin/roles/{id}', [RoleController::class, 'update'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'roles', 'modify']]);

$router->get('/admin/fields', [FieldController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'settings', 'view']]);
$router->get('/admin/fields/{module}', [FieldController::class, 'edit'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'settings', 'modify']]);
$router->post('/admin/fields/update', [FieldController::class, 'update'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'settings', 'modify']]);

$router->get('/admin/products', [AdminProductController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'products', 'view']]);
$router->get('/admin/products/create', [AdminProductController::class, 'create'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'products', 'modify']]);
$router->post('/admin/products', [AdminProductController::class, 'store'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'products', 'modify']]);
$router->get('/admin/products/edit/{id}', [AdminProductController::class, 'edit'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'products', 'modify']]);
$router->post('/admin/products/{id}', [AdminProductController::class, 'update'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'products', 'modify']]);
$router->post('/admin/products/delete/{id}', [AdminProductController::class, 'destroy'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'products', 'modify']]);

$router->get('/admin/categories', [CategoryController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'categories', 'view']]);
$router->get('/admin/categories/create', [CategoryController::class, 'create'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'categories', 'modify']]);
$router->post('/admin/categories', [CategoryController::class, 'store'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'categories', 'modify']]);
$router->get('/admin/categories/edit/{id}', [CategoryController::class, 'edit'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'categories', 'modify']]);
$router->post('/admin/categories/{id}', [CategoryController::class, 'update'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'categories', 'modify']]);
$router->post('/admin/categories/delete/{id}', [CategoryController::class, 'destroy'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'categories', 'modify']]);

$router->get('/admin/orders', [OrderController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'orders', 'view']]);
$router->get('/admin/activity-logs', [ActivityLogController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'activity_logs', 'view']]);
$router->get('/admin/settings', [SettingsController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'settings', 'view']]);

$router->get('/admin/users', [UserController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'users', 'view']]);
$router->get('/admin/users/create', [UserController::class, 'create'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'users', 'modify']]);
$router->post('/admin/users', [UserController::class, 'store'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'users', 'modify']]);
$router->get('/admin/users/edit/{id}', [UserController::class, 'edit'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'users', 'modify']]);
$router->post('/admin/users/{id}', [UserController::class, 'update'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'users', 'modify']]);
$router->post('/admin/users/delete/{id}', [UserController::class, 'destroy'], [AuthMiddleware::class, AdminMiddleware::class, [PermissionMiddleware::class, 'users', 'modify']]);
