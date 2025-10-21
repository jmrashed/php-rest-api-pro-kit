<?php

namespace App\DebugBar\Collectors;

use App\DebugBar\BaseCollector;

class MessageCollector extends BaseCollector
{
    private $messages = [];

    public function getName(): string
    {
        return 'messages';
    }

    public function addMessage(string $message, string $level = 'info'): void
    {
        $this->messages[] = [
            'message' => $message,
            'level' => $level,
            'time' => microtime(true),
            'formatted_time' => date('H:i:s.') . substr(microtime(), 2, 3)
        ];
    }

    public function collect(): array
    {
        return ['messages' => $this->messages];
    }
}