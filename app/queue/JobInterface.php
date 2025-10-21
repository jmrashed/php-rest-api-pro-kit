<?php

namespace App\Queue;

interface JobInterface
{
    public function handle(): bool;
    public function failed(\Exception $exception): void;
    public function getMaxRetries(): int;
    public function getDelay(): int;
}