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
        $this->router->dispatch($request);
    }

    private function loadRoutes()
    {
        $router = $this->router;
        require_once APP_PATH . '/routes/web.php';
        require_once APP_PATH . '/routes/api.php';
        require_once APP_PATH . '/routes/api_v1.php';
        require_once APP_PATH . '/routes/api_v2.php';
    }
}