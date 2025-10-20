<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Autoloader;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Config\Env;
use App\Exceptions\Handler;
use App\Helpers\JwtHelper;
use App\Helpers\Logger;

// Load environment variables
Env::load(__DIR__ . '/../.env');

// Set up error and exception handling
$handler = new Handler();
set_exception_handler([$handler, 'handle']);
set_error_handler(function ($severity, $message, $file, $line) use ($handler) {
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting
        return;
    }
    $handler->handle(new \ErrorException($message, 0, $severity, $file, $line));
});

// Initialize Logger
Logger::init();

// Initialize JWT Helper
JwtHelper::init();

// Create Request and Response objects
$request = new Request();
$response = new Response();

// Include API routes
$router = new Router();
require_once __DIR__ . '/../app/routes/api.php';

// Dispatch the request
$router->dispatch($request, $response);