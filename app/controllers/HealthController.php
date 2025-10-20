<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Database;

class HealthController extends Controller
{
    public function check()
    {
        $status = [
            'api' => 'healthy',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0.0'
        ];

        try {
            $db = Database::getInstance();
            $db->query('SELECT 1');
            $status['database'] = 'connected';
        } catch (\Exception $e) {
            $status['database'] = 'disconnected';
        }

        $status['memory_usage'] = memory_get_usage(true);
        $status['disk_space'] = disk_free_space('.');

        return Response::json($status);
    }

    public function info()
    {
        return Response::json([
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize')
        ]);
    }
}