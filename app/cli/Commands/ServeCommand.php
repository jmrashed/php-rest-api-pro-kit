<?php

namespace App\Cli\Commands;

use App\Cli\CommandInterface;

class ServeCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'serve';
    }

    public function getDescription(): string
    {
        return 'Start development server';
    }

    public function execute(array $args): int
    {
        $host = $args[0] ?? 'localhost';
        $port = $args[1] ?? '8000';
        
        echo "\033[32mStarting development server at http://{$host}:{$port}\033[0m\n";
        echo "\033[33mPress Ctrl+C to stop\033[0m\n\n";
        
        $command = "php -S {$host}:{$port} -t public";
        passthru($command);
        
        return 0;
    }
}