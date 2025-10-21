<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/core/Autoloader.php';
require_once __DIR__ . '/constants.php';

use App\Core\Autoloader;
use App\Core\Application;
use App\Config\Env;
use App\DebugBar\DebugBar;
use App\DebugBar\Collectors\{TimerCollector, MemoryCollector, MessageCollector, QueryCollector, RequestCollector};

$autoloader = new Autoloader();
$autoloader->register();

// Load debug helpers
require_once __DIR__ . '/../app/helpers/debug.php';

Env::load(dirname(__DIR__) . '/.env');

// Initialize DebugBar
if (Env::isDebugBarEnabled()) {
    $debugBar = DebugBar::getInstance();
    $allowedIps = Env::getDebugBarAllowedIps();
    
    if (empty($allowedIps) || in_array($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', $allowedIps)) {
        $debugBar->enable();
        $debugBar->addCollector(new TimerCollector());
        $debugBar->addCollector(new MemoryCollector());
        $debugBar->addCollector(new MessageCollector());
        $debugBar->addCollector(new QueryCollector());
        $debugBar->addCollector(new RequestCollector());
        
        ob_start();
    }
}

$app = new Application();
return $app;