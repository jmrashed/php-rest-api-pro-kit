<?php

namespace App\Queue;

use App\Queue\Drivers\DatabaseDriver;
use App\Queue\Drivers\RedisDriver;

class QueueManager
{
    private static $instance = null;
    private $driver;
    private $config;

    private function __construct()
    {
        $this->config = [
            'default' => $_ENV['QUEUE_DRIVER'] ?? 'database',
            'connections' => [
                'database' => ['driver' => 'database'],
                'redis' => ['driver' => 'redis', 'host' => '127.0.0.1', 'port' => 6379]
            ]
        ];
        
        $this->initializeDriver();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function initializeDriver(): void
    {
        $driverName = $this->config['default'];
        
        switch ($driverName) {
            case 'redis':
                $this->driver = new RedisDriver($this->config['connections']['redis']);
                break;
            default:
                $this->driver = new DatabaseDriver($this->config['connections']['database']);
        }
    }

    public function push(JobInterface $job, string $queue = 'default'): string
    {
        return $this->driver->push($job, $queue);
    }

    public function pop(string $queue = 'default'): ?array
    {
        return $this->driver->pop($queue);
    }

    public function retry(string $jobId, JobInterface $job, string $queue = 'default'): void
    {
        $this->driver->retry($jobId, $job, $queue);
    }

    public function failed(string $jobId, JobInterface $job, \Exception $exception): void
    {
        $this->driver->failed($jobId, $job, $exception);
    }

    public function size(string $queue = 'default'): int
    {
        return $this->driver->size($queue);
    }
}