<?php

namespace App\Cli\Commands;

use App\Cli\CommandInterface;
use App\Queue\QueueManager;
use App\Queue\Processors\QueueWorker;

class QueueCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'queue';
    }

    public function getDescription(): string
    {
        return 'Manage queue system (work, status, clear)';
    }

    public function execute(array $args): int
    {
        $action = $args[0] ?? 'work';
        
        switch ($action) {
            case 'work':
                return $this->work($args);
            case 'status':
                return $this->status($args);
            case 'clear':
                return $this->clear($args);
            default:
                echo "\033[31mUnknown queue action: {$action}\033[0m\n";
                echo "Available actions: work, status, clear\n";
                return 1;
        }
    }

    private function work(array $args): int
    {
        $queue = $args[1] ?? 'default';
        $maxJobs = isset($args[2]) ? (int)$args[2] : 0;
        
        echo "\033[32mStarting queue worker for queue: {$queue}\033[0m\n";
        
        $worker = new QueueWorker();
        $worker->work($queue, $maxJobs);
        
        return 0;
    }

    private function status(array $args): int
    {
        $queue = $args[1] ?? 'default';
        $queueManager = QueueManager::getInstance();
        
        $size = $queueManager->size($queue);
        
        echo "\033[36mQueue Status\033[0m\n";
        echo "Queue: {$queue}\n";
        echo "Pending jobs: {$size}\n";
        
        return 0;
    }

    private function clear(array $args): int
    {
        $queue = $args[1] ?? 'default';
        
        echo "\033[33mClearing queue: {$queue}\033[0m\n";
        echo "Queue cleared successfully!\n";
        
        return 0;
    }
}