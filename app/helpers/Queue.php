<?php

use App\Queue\QueueManager;
use App\Queue\Jobs\SendEmailJob;
use App\Queue\Jobs\ProcessFileJob;

if (!function_exists('dispatch')) {
    function dispatch($job, string $queue = 'default'): string {
        return QueueManager::getInstance()->push($job, $queue);
    }
}

if (!function_exists('queue_email')) {
    function queue_email(string $to, string $subject, string $message, array $headers = []): string {
        $job = new SendEmailJob($to, $subject, $message, $headers);
        return dispatch($job, 'emails');
    }
}

if (!function_exists('queue_file_processing')) {
    function queue_file_processing(string $filePath, string $operation = 'resize', array $options = []): string {
        $job = new ProcessFileJob($filePath, $operation, $options);
        return dispatch($job, 'files');
    }
}

if (!function_exists('queue_status')) {
    function queue_status(string $queue = 'default'): int {
        return QueueManager::getInstance()->size($queue);
    }
}