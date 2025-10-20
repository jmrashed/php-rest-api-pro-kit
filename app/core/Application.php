<?php

namespace App\Core;

use App\Core\Router;
use App\Core\Request;
use App\Core\Response;
use App\Core\Config;

class Application
{
    private $router;
    private $config;

    public function __construct()
    {
        $this->config = new Config();
        $this->router = new Router();
        $this->loadRoutes();
    }

    public function run()
    {
        $request = new Request();
        $response = $this->router->dispatch($request);
        $response->send();
    }

    private function loadRoutes()
    {
        require_once APP_PATH . '/routes/api.php';
    }
}