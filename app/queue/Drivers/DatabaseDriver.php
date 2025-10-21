<?php

namespace App\Queue\Drivers;

use App\Queue\JobInterface;
use App\Core\Database;

class DatabaseDriver implements QueueDriverInterface
{
    private $db;

    public function __construct(array $config)
    {
        $this->db = Database::getInstance();
        $this->createJobsTable();
    }

    public function push(JobInterface $job, string $queue): string
    {
        $jobId = uniqid('job_', true);
        $payload = serialize($job);
        
        $sql = "INSERT INTO jobs (id, queue, payload, attempts, created_at, available_at) VALUES (?, ?, ?, 0, NOW(), NOW())";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$jobId, $queue, $payload]);
        
        return $jobId;
    }

    public function pop(string $queue): ?array
    {
        $sql = "SELECT * FROM jobs WHERE queue = ? AND available_at <= NOW() ORDER BY created_at ASC LIMIT 1";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$queue]);
        $job = $stmt->fetch();
        
        if ($job) {
            // Mark as processing
            $updateSql = "UPDATE jobs SET attempts = attempts + 1, reserved_at = NOW() WHERE id = ?";
            $updateStmt = $this->db->getConnection()->prepare($updateSql);
            $updateStmt->execute([$job['id']]);
            
            return [
                'id' => $job['id'],
                'job' => unserialize($job['payload']),
                'attempts' => $job['attempts'] + 1
            ];
        }
        
        return null;
    }

    public function retry(string $jobId, JobInterface $job, string $queue): void
    {
        $delay = $job->getDelay();
        $availableAt = date('Y-m-d H:i:s', time() + $delay);
        
        $sql = "UPDATE jobs SET available_at = ?, reserved_at = NULL WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$availableAt, $jobId]);
    }

    public function failed(string $jobId, JobInterface $job, \Exception $exception): void
    {
        $sql = "INSERT INTO failed_jobs (id, queue, payload, exception, failed_at) 
                SELECT id, queue, payload, ?, NOW() FROM jobs WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$exception->getMessage(), $jobId]);
        
        $deleteSql = "DELETE FROM jobs WHERE id = ?";
        $deleteStmt = $this->db->getConnection()->prepare($deleteSql);
        $deleteStmt->execute([$jobId]);
    }

    public function size(string $queue): int
    {
        $sql = "SELECT COUNT(*) FROM jobs WHERE queue = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$queue]);
        return (int)$stmt->fetchColumn();
    }

    private function createJobsTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS jobs (
            id VARCHAR(255) PRIMARY KEY,
            queue VARCHAR(255) NOT NULL,
            payload TEXT NOT NULL,
            attempts INT DEFAULT 0,
            reserved_at TIMESTAMP NULL,
            available_at TIMESTAMP NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_queue_available (queue, available_at)
        )";
        $this->db->exec($sql);

        $failedSql = "CREATE TABLE IF NOT EXISTS failed_jobs (
            id VARCHAR(255) PRIMARY KEY,
            queue VARCHAR(255) NOT NULL,
            payload TEXT NOT NULL,
            exception TEXT NOT NULL,
            failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->db->exec($failedSql);
    }
}