<?php

namespace App\DebugBar;

abstract class BaseCollector implements CollectorInterface
{
    protected $data = [];

    public function addData($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function collect(): array
    {
        return $this->data;
    }
}