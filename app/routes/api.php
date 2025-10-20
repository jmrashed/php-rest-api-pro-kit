<?php

use App\Core\Router;
use App\Controllers\UserController;
use App\Middleware\AuthMiddleware;
use App\Middleware\CorsMiddleware;

/** @var Router $router */

$router->group('/api', [CorsMiddleware::class], function (Router $router) {
    // Public routes
    $router->addRoute('POST', '/register', [UserController::class, 'register']);
    $router->addRoute('POST', '/login', [UserController::class, 'login']);

    // Protected routes (require authentication)
    $router->group('/users', [AuthMiddleware::class], function (Router $router) {
        $router->addRoute('GET', '', [UserController::class, 'index']);
        $router->addRoute('GET', '/{id}', [UserController::class, 'show']);
        $router->addRoute('POST', '', [UserController::class, 'store']);
        $router->addRoute('PUT', '/{id}', [UserController::class, 'update']);
        $router->addRoute('DELETE', '/{id}', [UserController::class, 'destroy']);
    });
});