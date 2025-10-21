<?php

namespace App\Queue\Drivers;

use App\Queue\JobInterface;

class RedisDriver implements QueueDriverInterface
{
    private $redis;
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->initializeRedis();
    }

    private function initializeRedis(): void
    {
        if (!extension_loaded('redis')) {
            throw new \Exception('Redis extension not loaded');
        }
        
        $this->redis = new \Redis();
        $this->redis->connect($this->config['host'], $this->config['port']);
    }

    public function push(JobInterface $job, string $queue): string
    {
        $jobId = uniqid('job_', true);
        $payload = json_encode([
            'id' => $jobId,
            'job' => serialize($job),
            'attempts' => 0,
            'created_at' => time()
        ]);
        
        $this->redis->lpush("queue:{$queue}", $payload);
        return $jobId;
    }

    public function pop(string $queue): ?array
    {
        $payload = $this->redis->brpop("queue:{$queue}", 1);
        
        if ($payload) {
            $data = json_decode($payload[1], true);
            return [
                'id' => $data['id'],
                'job' => unserialize($data['job']),
                'attempts' => $data['attempts'] + 1
            ];
        }
        
        return null;
    }

    public function retry(string $jobId, JobInterface $job, string $queue): void
    {
        $delay = $job->getDelay();
        $payload = json_encode([
            'id' => $jobId,
            'job' => serialize($job),
            'attempts' => 0,
            'created_at' => time()
        ]);
        
        if ($delay > 0) {
            $this->redis->zadd("queue:{$queue}:delayed", time() + $delay, $payload);
        } else {
            $this->redis->lpush("queue:{$queue}", $payload);
        }
    }

    public function failed(string $jobId, JobInterface $job, \Exception $exception): void
    {
        $payload = json_encode([
            'id' => $jobId,
            'job' => serialize($job),
            'exception' => $exception->getMessage(),
            'failed_at' => time()
        ]);
        
        $this->redis->lpush("queue:failed", $payload);
    }

    public function size(string $queue): int
    {
        return $this->redis->llen("queue:{$queue}");
    }
}