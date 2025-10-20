<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/core/Autoloader.php';
require_once __DIR__ . '/constants.php';

use App\Core\Autoloader;
use App\Core\Application;

$autoloader = new Autoloader();
$autoloader->register();

$app = new Application();
return $app;