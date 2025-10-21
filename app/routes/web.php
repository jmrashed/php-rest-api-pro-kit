<?php

use App\Core\Router;
use App\Controllers\WelcomeController;

/** @var Router $router */

// Web routes
$router->addRoute('GET', '/', [WelcomeController::class, 'index']);
$router->addRoute('GET', '/welcome', [WelcomeController::class, 'index']);