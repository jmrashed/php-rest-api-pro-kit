<?php

namespace App\Controllers;

use App\Core\Database;
use App\DebugBar\DebugBar;

class WelcomeController
{
    public function index()
    {
        // Test debug messages
        debug('Welcome page loaded', 'info');
        debug('Testing debug functionality', 'warning');

        // Test timer
        timer_start('page_generation');

        // Test database query
        try {
            $db = Database::getInstance();
            $stmt = $db->getConnection()->prepare("SELECT 1 as test");
            $stmt->execute();
            $result = $stmt->fetch();
            debug('Database query executed successfully', 'info');
        } catch (\Exception $e) {
            debug('Database error: ' . $e->getMessage(), 'error');
        }

        // Simulate some work
        usleep(50000); // 50ms delay

        timer_stop('page_generation');
        debug('Page generation completed', 'info');

        // Get debug data and inject toolbar
        $html = $this->renderWelcomePage();
        
        $debugBar = DebugBar::getInstance();
        if ($debugBar->isEnabled()) {
            $debugData = $debugBar->collect();
            
            // Inject toolbar before </body>
            if (strpos($html, '</body>') !== false) {
                ob_start();
                include __DIR__ . '/../debugbar/Views/toolbar.php';
                $toolbar = ob_get_clean();
                $html = str_replace('</body>', $toolbar . '</body>', $html);
            }
        }
        
        // Set content type header and output
        header('Content-Type: text/html');
        echo $html;
    }

    private function renderWelcomePage()
    {
        return '<!DOCTYPE html>
<html>
<head>
    <title>Welcome - Debug Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 800px; margin: 0 auto; }
        .card { background: #f5f5f5; padding: 20px; border-radius: 8px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to PHP REST API Pro Kit</h1>
        
        <div class="card">
            <h3>Debug Bar Test Page</h3>
            <p>This page tests the debug bar functionality:</p>
            <ul>
                <li>✅ Debug messages logged</li>
                <li>✅ Timer measurements</li>
                <li>✅ Database query tracking</li>
                <li>✅ Memory usage monitoring</li>
                <li>✅ Request information capture</li>
            </ul>
        </div>

        <div class="card">
            <h3>Expected Debug Bar Features</h3>
            <p>Check the debug bar at the bottom for:</p>
            <ul>
                <li><strong>Meta:</strong> Execution time and memory usage</li>
                <li><strong>Queries:</strong> Database queries with timing</li>
                <li><strong>Messages:</strong> Debug log messages</li>
                <li><strong>Memory:</strong> Current and peak memory</li>
                <li><strong>Request:</strong> HTTP request details</li>
            </ul>
        </div>
    </div>
</body>
</html>';
    }
}