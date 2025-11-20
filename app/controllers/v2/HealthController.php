<?php

namespace App\Controllers\V2;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Database;
use App\Core\Cache;
use App\Helpers\Logger;
use App\Queue\QueueManager;

class HealthController extends Controller
{
    public function check()
    {
        $startTime = microtime(true);

        $healthData = [
            'success' => true,
            'data' => [
                'status' => 'healthy',
                'timestamp' => date('c'), // ISO 8601 format
                'version' => '2.0.0',
                'uptime' => $this->getUptime(),
                'checks' => []
            ],
            'meta' => [
                'version' => '2.0.0',
                'timestamp' => date('c'),
                'api_route_path' => '/v2/health/check',
                'description' => 'Enhanced health check endpoint with comprehensive system monitoring'
            ]
        ];

        // Core system checks
        $healthData['data']['checks']['database'] = $this->checkDatabase();
        $healthData['data']['checks']['cache'] = $this->checkCache();
        $healthData['data']['checks']['filesystem'] = $this->checkFilesystem();
        $healthData['data']['checks']['memory'] = $this->checkMemory();
        $healthData['data']['checks']['queue'] = $this->checkQueue();

        // Optional external service checks
        $healthData['data']['checks']['external_services'] = $this->checkExternalServices();

        // Performance metrics
        $healthData['data']['performance'] = [
            'response_time' => round((microtime(true) - $startTime) * 1000, 2) . 'ms',
            'cpu_usage' => $this->getCpuUsage(),
            'load_average' => $this->getLoadAverage()
        ];

        // Overall status determination
        $healthData['data']['status'] = $this->determineOverallStatus($healthData['data']['checks']);

        // Update success flag based on status
        $healthData['success'] = $healthData['data']['status'] === 'healthy';

        $httpStatus = $healthData['success'] ? 200 : 503;

        return Response::json($healthData, $httpStatus);
    }

    public function info()
    {
        $info = [
            'success' => true,
            'data' => [
                'system' => [
                    'php_version' => PHP_VERSION,
                    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                    'operating_system' => PHP_OS,
                    'architecture' => php_uname('m'),
                    'hostname' => php_uname('n')
                ],
                'php_configuration' => [
                    'memory_limit' => ini_get('memory_limit'),
                    'max_execution_time' => ini_get('max_execution_time'),
                    'upload_max_filesize' => ini_get('upload_max_filesize'),
                    'post_max_size' => ini_get('post_max_size'),
                    'max_file_uploads' => ini_get('max_file_uploads'),
                    'timezone' => date_default_timezone_get(),
                    'error_reporting' => error_reporting(),
                    'display_errors' => ini_get('display_errors')
                ],
                'extensions' => [
                    'pdo' => extension_loaded('pdo'),
                    'pdo_mysql' => extension_loaded('pdo_mysql'),
                    'pdo_pgsql' => extension_loaded('pdo_pgsql'),
                    'redis' => extension_loaded('redis'),
                    'json' => extension_loaded('json'),
                    'mbstring' => extension_loaded('mbstring'),
                    'openssl' => extension_loaded('openssl'),
                    'curl' => extension_loaded('curl'),
                    'gd' => extension_loaded('gd'),
                    'zip' => extension_loaded('zip')
                ],
                'environment' => [
                    'app_env' => getenv('APP_ENV') ?: 'production',
                    'debug_mode' => getenv('DEBUG') === 'true',
                    'queue_driver' => getenv('QUEUE_DRIVER') ?: 'database',
                    'cache_driver' => getenv('CACHE_DRIVER') ?: 'file'
                ],
                'paths' => [
                    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
                    'script_path' => __FILE__,
                    'storage_path' => realpath(APP_PATH . '/../storage') ?: 'Not found',
                    'logs_path' => realpath(APP_PATH . '/../storage/logs') ?: 'Not found'
                ]
            ],
            'meta' => [
                'version' => '2.0.0',
                'timestamp' => date('c'),
                'api_route_path' => '/v2/health/info',
                'description' => 'Comprehensive system information and configuration details'
            ]
        ];

        return Response::json($info);
    }

    private function checkDatabase()
    {
        try {
            $db = Database::getInstance();
            $startTime = microtime(true);

            $stmt = $db->query('SELECT 1 as test, VERSION() as version, DATABASE() as database_name');
            $result = $stmt->fetch();
            $queryTime = round((microtime(true) - $startTime) * 1000, 2);

            // Additional database metrics
            $stmt = $db->query('SHOW PROCESSLIST');
            $connections = $stmt->rowCount();

            return [
                'status' => 'healthy',
                'message' => 'Database connection successful',
                'details' => [
                    'database_name' => $result['database_name'],
                    'version' => $result['version'],
                    'query_time' => $queryTime . 'ms',
                    'active_connections' => $connections
                ]
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
            $testKey = 'health_check_v2_' . time() . '_' . uniqid();
            $testValue = ['test' => 'data', 'timestamp' => time()];

            $startTime = microtime(true);
            $cache->set($testKey, $testValue, 300);
            $writeTime = round((microtime(true) - $startTime) * 1000, 2);

            $startTime = microtime(true);
            $retrieved = $cache->get($testKey);
            $readTime = round((microtime(true) - $startTime) * 1000, 2);

            $cache->delete($testKey);

            if ($retrieved === $testValue) {
                return [
                    'status' => 'healthy',
                    'message' => 'Cache system operational',
                    'details' => [
                        'write_time' => $writeTime . 'ms',
                        'read_time' => $readTime . 'ms',
                        'cache_driver' => getenv('CACHE_DRIVER') ?: 'file'
                    ]
                ];
            } else {
                return [
                    'status' => 'unhealthy',
                    'message' => 'Cache read/write verification failed'
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
        $checks = [];

        // Storage directory check
        $storagePath = APP_PATH . '/../storage';
        if (is_writable($storagePath)) {
            $checks['storage_writable'] = true;
        } else {
            $checks['storage_writable'] = false;
        }

        // Logs directory check
        $logsPath = $storagePath . '/logs';
        if (is_writable($logsPath)) {
            $checks['logs_writable'] = true;
        } else {
            $checks['logs_writable'] = false;
        }

        // Uploads directory check
        $uploadsPath = APP_PATH . '/../public/uploads';
        if (is_writable($uploadsPath)) {
            $checks['uploads_writable'] = true;
        } else {
            $checks['uploads_writable'] = false;
        }

        $allWritable = !in_array(false, $checks, true);

        return [
            'status' => $allWritable ? 'healthy' : 'warning',
            'message' => $allWritable ? 'All filesystem checks passed' : 'Some directories are not writable',
            'details' => array_merge($checks, [
                'disk_free_space' => $this->formatBytes(disk_free_space('.')),
                'disk_total_space' => $this->formatBytes(disk_total_space('.')),
                'disk_usage_percent' => round((1 - (disk_free_space('.') / disk_total_space('.'))) * 100, 1)
            ])
        ];
    }

    private function checkMemory()
    {
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);
        $memoryLimit = $this->parseMemoryLimit(ini_get('memory_limit'));

        $usagePercent = $memoryLimit > 0 ? ($memoryUsage / $memoryLimit) * 100 : 0;
        $peakPercent = $memoryLimit > 0 ? ($memoryPeak / $memoryLimit) * 100 : 0;

        $status = 'healthy';
        if ($usagePercent > 90 || $peakPercent > 95) {
            $status = 'warning';
        }
        if ($usagePercent > 95) {
            $status = 'critical';
        }

        return [
            'status' => $status,
            'message' => "Memory usage: {$usagePercent}% of limit",
            'details' => [
                'current_usage' => $this->formatBytes($memoryUsage),
                'peak_usage' => $this->formatBytes($memoryPeak),
                'memory_limit' => $this->formatBytes($memoryLimit),
                'usage_percent' => round($usagePercent, 1),
                'peak_percent' => round($peakPercent, 1)
            ]
        ];
    }

    private function checkQueue()
    {
        try {
            $queueManager = QueueManager::getInstance();
            $queueStats = [];

            // Check default queue
            $queueStats['default'] = $queueManager->size('default');

            // Check other queues if they exist
            $otherQueues = ['emails', 'files', 'notifications'];
            foreach ($otherQueues as $queue) {
                try {
                    $queueStats[$queue] = $queueManager->size($queue);
                } catch (\Exception $e) {
                    $queueStats[$queue] = 'error';
                }
            }

            return [
                'status' => 'healthy',
                'message' => 'Queue system operational',
                'details' => [
                    'driver' => getenv('QUEUE_DRIVER') ?: 'database',
                    'queue_sizes' => $queueStats
                ]
            ];
        } catch (\Exception $e) {
            Logger::error('Queue health check failed: ' . $e->getMessage());
            return [
                'status' => 'warning',
                'message' => 'Queue system check failed',
                'error' => $e->getMessage()
            ];
        }
    }

    private function checkExternalServices()
    {
        $services = [];

        // Check if Redis is configured and available
        if (getenv('REDIS_HOST')) {
            $services['redis'] = $this->checkRedisConnection();
        }

        // Add more external service checks here as needed
        // e.g., SMTP, external APIs, etc.

        return [
            'status' => empty($services) ? 'not_configured' : (in_array('unhealthy', array_column($services, 'status')) ? 'warning' : 'healthy'),
            'message' => empty($services) ? 'No external services configured' : 'External services checked',
            'services' => $services
        ];
    }

    private function checkRedisConnection()
    {
        try {
            if (!extension_loaded('redis')) {
                return [
                    'status' => 'not_available',
                    'message' => 'Redis extension not loaded'
                ];
            }

            $redis = new \Redis();
            $redis->connect(getenv('REDIS_HOST'), getenv('REDIS_PORT') ?: 6379);

            if (getenv('REDIS_PASSWORD')) {
                $redis->auth(getenv('REDIS_PASSWORD'));
            }

            $redis->ping();
            $info = $redis->info();

            return [
                'status' => 'healthy',
                'message' => 'Redis connection successful',
                'details' => [
                    'version' => $info['redis_version'] ?? 'Unknown',
                    'connected_clients' => $info['connected_clients'] ?? 'Unknown',
                    'used_memory' => $info['used_memory_human'] ?? 'Unknown'
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Redis connection failed',
                'error' => $e->getMessage()
            ];
        }
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

    private function getCpuUsage()
    {
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            return round($load[0] * 100, 1) . '%';
        }
        return 'Unknown';
    }

    private function getLoadAverage()
    {
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            return array_map(function($value) {
                return round($value, 2);
            }, $load);
        }
        return 'Unknown';
    }

    private function determineOverallStatus($checks)
    {
        $statuses = [];
        foreach ($checks as $check) {
            if (is_array($check) && isset($check['status'])) {
                $statuses[] = $check['status'];
            }
        }

        if (in_array('unhealthy', $statuses)) {
            return 'unhealthy';
        }
        if (in_array('critical', $statuses) || in_array('warning', $statuses)) {
            return 'warning';
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