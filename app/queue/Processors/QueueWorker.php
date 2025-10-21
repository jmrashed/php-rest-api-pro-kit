<?php

namespace App\Queue\Processors;

use App\Queue\QueueManager;
use App\Queue\JobInterface;

class QueueWorker
{
    private $queueManager;
    private $running = false;

    public function __construct()
    {
        $this->queueManager = QueueManager::getInstance();
    }

    public function work(string $queue = 'default', int $maxJobs = 0): void
    {
        $this->running = true;
        $processedJobs = 0;

        echo "Queue worker started for queue: {$queue}\n";

        while ($this->running) {
            $jobData = $this->queueManager->pop($queue);
            
            if ($jobData === null) {
                sleep(1); // Wait before checking again
                continue;
            }

            $this->processJob($jobData, $queue);
            $processedJobs++;

            if ($maxJobs > 0 && $processedJobs >= $maxJobs) {
                break;
            }
        }

        echo "Queue worker stopped. Processed {$processedJobs} jobs.\n";
    }

    private function processJob(array $jobData, string $queue): void
    {
        $job = $jobData['job'];
        $jobId = $jobData['id'];
        $attempts = $jobData['attempts'];

        echo "Processing job {$jobId} (attempt {$attempts})\n";

        try {
            $result = $job->handle();
            
            if ($result) {
                echo "Job {$jobId} completed successfully\n";
                $this->deleteJob($jobId);
            } else {
                throw new \Exception("Job returned false");
            }
        } catch (\Exception $e) {
            echo "Job {$jobId} failed: " . $e->getMessage() . "\n";
            
            if ($attempts >= $job->getMaxRetries()) {
                echo "Job {$jobId} exceeded max retries, moving to failed queue\n";
                $this->queueManager->failed($jobId, $job, $e);
                $job->failed($e);
            } else {
                echo "Job {$jobId} will be retried\n";
                $this->queueManager->retry($jobId, $job, $queue);
            }
        }
    }

    private function deleteJob(string $jobId): void
    {
        // Job is automatically removed from queue when popped in database driver
        // For Redis, job is removed when popped
    }

    public function stop(): void
    {
        $this->running = false;
    }
}