<?php

namespace App\DebugBar\Collectors;

use App\DebugBar\BaseCollector;

class QueryCollector extends BaseCollector
{
    private $queries = [];

    public function getName(): string
    {
        return 'queries';
    }

    public function addQuery(string $sql, array $bindings = [], float $time = 0): void
    {
        $this->queries[] = [
            'sql' => $sql,
            'bindings' => $bindings,
            'time' => round($time * 1000, 2),
            'timestamp' => microtime(true)
        ];
    }

    public function collect(): array
    {
        return [
            'queries' => $this->queries,
            'total_queries' => count($this->queries),
            'total_time' => round(array_sum(array_column($this->queries, 'time')), 2)
        ];
    }
}