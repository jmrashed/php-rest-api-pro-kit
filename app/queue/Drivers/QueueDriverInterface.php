<?php

namespace App\Queue\Drivers;

use App\Queue\JobInterface;

interface QueueDriverInterface
{
    public function push(JobInterface $job, string $queue): string;
    public function pop(string $queue): ?array;
    public function retry(string $jobId, JobInterface $job, string $queue): void;
    public function failed(string $jobId, JobInterface $job, \Exception $exception): void;
    public function size(string $queue): int;
}