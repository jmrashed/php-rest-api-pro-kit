<?php

use App\Core\Router;
use App\Controllers\V2\UserController;
use App\Controllers\V2\AuthController;
use App\Controllers\V2\HealthController;
use App\Middleware\AuthMiddleware;
use App\Middleware\CorsMiddleware;
use App\Api\Versioning\ApiVersionMiddleware;

/** @var Router $router */

$router->group('/api/v2', [CorsMiddleware::class, ApiVersionMiddleware::class], function (Router $router) {
    // Public routes
    $router->addRoute('GET', '/health', [HealthController::class, 'check']);
    $router->addRoute('POST', '/auth/register', [AuthController::class, 'register']);
    $router->addRoute('POST', '/auth/login', [AuthController::class, 'login']);

    // Protected routes with enhanced features
    $router->group('/users', [AuthMiddleware::class], function (Router $router) {
        $router->addRoute('GET', '', [UserController::class, 'index']);
        $router->addRoute('GET', '/{id}', [UserController::class, 'show']);
        $router->addRoute('POST', '', [UserController::class, 'store']);
        $router->addRoute('PUT', '/{id}', [UserController::class, 'update']);
        $router->addRoute('DELETE', '/{id}', [UserController::class, 'destroy']);
    });
});