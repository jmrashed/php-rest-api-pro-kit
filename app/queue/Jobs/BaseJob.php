<?php

namespace App\Queue\Jobs;

use App\Queue\JobInterface;

abstract class BaseJob implements JobInterface
{
    protected $maxRetries = 3;
    protected $delay = 0;

    public function getMaxRetries(): int
    {
        return $this->maxRetries;
    }

    public function getDelay(): int
    {
        return $this->delay;
    }

    public function failed(\Exception $exception): void
    {
        // Default implementation - can be overridden
        error_log("Job failed: " . get_class($this) . " - " . $exception->getMessage());
    }
}