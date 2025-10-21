<?php

namespace App\DebugBar;

class DebugBar
{
    private static $instance = null;
    private $collectors = [];
    private $enabled = false;
    private $startTime;
    private $startMemory;

    private function __construct()
    {
        $this->startTime = microtime(true);
        $this->startMemory = memory_get_usage();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function enable(): void
    {
        $this->enabled = true;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function addCollector(CollectorInterface $collector): void
    {
        $this->collectors[$collector->getName()] = $collector;
    }

    public function collect(): array
    {
        if (!$this->enabled) {
            return [];
        }

        $data = [];
        foreach ($this->collectors as $name => $collector) {
            $data[$name] = $collector->collect();
        }

        $data['_meta'] = [
            'execution_time' => round((microtime(true) - $this->startTime) * 1000, 2),
            'memory_usage' => memory_get_usage() - $this->startMemory,
            'peak_memory' => memory_get_peak_usage(),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        return $data;
    }

    public function getCollector(string $name): ?CollectorInterface
    {
        return $this->collectors[$name] ?? null;
    }
}