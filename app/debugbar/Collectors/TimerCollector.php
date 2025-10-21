<?php

namespace App\DebugBar\Collectors;

use App\DebugBar\BaseCollector;

class TimerCollector extends BaseCollector
{
    private $timers = [];

    public function getName(): string
    {
        return 'timers';
    }

    public function start(string $name): void
    {
        $this->timers[$name] = ['start' => microtime(true)];
    }

    public function stop(string $name): void
    {
        if (isset($this->timers[$name])) {
            $this->timers[$name]['end'] = microtime(true);
            $this->timers[$name]['duration'] = round(($this->timers[$name]['end'] - $this->timers[$name]['start']) * 1000, 2);
        }
    }

    public function collect(): array
    {
        return ['timers' => $this->timers];
    }
}