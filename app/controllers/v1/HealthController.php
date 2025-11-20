<?php

namespace App\Controllers\V1;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Database;
use App\Core\Cache;
use App\Helpers\Logger;

class HealthController extends Controller
{
    public function check()
    {
        $startTime = microtime(true);

        $status = [
            'status' => 'healthy',
            'timestamp' => date('c'), // ISO 8601 format
            'version' => '1.0.0',
            'uptime' => $this->getUptime(),
            'checks' => []
        ];

        // Database check
        $status['checks']['database'] = $this->checkDatabase();

        // Cache check
        $status['checks']['cache'] = $this->checkCache();

        // File system check
        $status['checks']['filesystem'] = $this->checkFilesystem();

        // Memory check
        $status['checks']['memory'] = $this->checkMemory();

        // Response time
        $status['response_time'] = round((microtime(true) - $startTime) * 1000, 2) . 'ms';

        // Overall status based on checks
        $status['status'] = $this->determineOverallStatus($status['checks']);

        return Response::json($status, $status['status'] === 'healthy' ? 200 : 503);
    }

    public function info()
    {
        return Response::json([
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_file_uploads' => ini_get('max_file_uploads'),
            'timezone' => date_default_timezone_get(),
            'extensions' => [
                'pdo' => extension_loaded('pdo'),
                'pdo_mysql' => extension_loaded('pdo_mysql'),
                'json' => extension_loaded('json'),
                'mbstring' => extension_loaded('mbstring'),
                'openssl' => extension_loaded('openssl')
            ],
            'environment' => getenv('APP_ENV') ?: 'production'
        ]);
    }

    private function checkDatabase()
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->query('SELECT 1 as test');
            $result = $stmt->fetch();
            return [
                'status' => 'healthy',
                'message' => 'Database connection successful',
                'response_time' => 'OK'
            ];
        } catch (\Exception $e) {
            Logger::error('Database health check failed: ' . $e->getMessage());
            return [
                'status' => 'unhealthy',
                'message' => 'Database connection failed',
                'error' => $e->getMessage()
            ];
        }
    }

    private function checkCache()
    {
        try {
            $cache = new Cache();
            $testKey = 'health_check_' . time();
            $testValue = 'test_value';

            $cache->set($testKey, $testValue, 60);
            $retrieved = $cache->get($testKey);

            if ($retrieved === $testValue) {
                $cache->delete($testKey);
                return [
                    'status' => 'healthy',
                    'message' => 'Cache system operational'
                ];
            } else {
                return [
                    'status' => 'unhealthy',
                    'message' => 'Cache read/write failed'
                ];
            }
        } catch (\Exception $e) {
            Logger::error('Cache health check failed: ' . $e->getMessage());
            return [
                'status' => 'unhealthy',
                'message' => 'Cache system error',
                'error' => $e->getMessage()
            ];
        }
    }

    private function checkFilesystem()
    {
        try {
            $testFile = sys_get_temp_dir() . '/health_check_' . time() . '.tmp';
            $testContent = 'health check test';

            if (file_put_contents($testFile, $testContent) === false) {
                return [
                    'status' => 'unhealthy',
                    'message' => 'Cannot write to filesystem'
                ];
            }

            $readContent = file_get_contents($testFile);
            unlink($testFile);

            if ($readContent === $testContent) {
                return [
                    'status' => 'healthy',
                    'message' => 'Filesystem read/write operational',
                    'disk_free_space' => $this->formatBytes(disk_free_space('.')),
                    'disk_total_space' => $this->formatBytes(disk_total_space('.'))
                ];
            } else {
                return [
                    'status' => 'unhealthy',
                    'message' => 'Filesystem read/write verification failed'
                ];
            }
        } catch (\Exception $e) {
            Logger::error('Filesystem health check failed: ' . $e->getMessage());
            return [
                'status' => 'unhealthy',
                'message' => 'Filesystem error',
                'error' => $e->getMessage()
            ];
        }
    }

    private function checkMemory()
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseMemoryLimit(ini_get('memory_limit'));

        $usagePercent = $memoryLimit > 0 ? ($memoryUsage / $memoryLimit) * 100 : 0;

        return [
            'status' => $usagePercent < 90 ? 'healthy' : 'warning',
            'message' => 'Memory usage: ' . round($usagePercent, 1) . '%',
            'current_usage' => $this->formatBytes($memoryUsage),
            'memory_limit' => $this->formatBytes($memoryLimit),
            'peak_usage' => $this->formatBytes(memory_get_peak_usage(true))
        ];
    }

    private function getUptime()
    {
        if (function_exists('posix_getpid')) {
            $uptime = @file_get_contents('/proc/uptime');
            if ($uptime) {
                $uptime = explode(' ', $uptime)[0];
                return $this->formatUptime((int)$uptime);
            }
        }
        return 'Unknown';
    }

    private function determineOverallStatus($checks)
    {
        foreach ($checks as $check) {
            if ($check['status'] === 'unhealthy') {
                return 'unhealthy';
            }
        }
        return 'healthy';
    }

    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    private function parseMemoryLimit($limit)
    {
        if ($limit === '-1') return -1;
        $unit = strtoupper(substr($limit, -1));
        $value = (int)substr($limit, 0, -1);
        switch ($unit) {
            case 'G': return $value * 1024 * 1024 * 1024;
            case 'M': return $value * 1024 * 1024;
            case 'K': return $value * 1024;
            default: return $value;
        }
    }

    private function formatUptime($seconds)
    {
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        $parts = [];
        if ($days > 0) $parts[] = $days . 'd';
        if ($hours > 0) $parts[] = $hours . 'h';
        if ($minutes > 0) $parts[] = $minutes . 'm';

        return implode(' ', $parts) ?: '0m';
    }
}