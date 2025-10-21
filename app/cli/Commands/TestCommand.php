<?php

namespace App\Cli\Commands;

use App\Cli\CommandInterface;

class TestCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'test';
    }

    public function getDescription(): string
    {
        return 'Run PHPUnit tests';
    }

    public function execute(array $args): int
    {
        $testFile = $args[0] ?? '';
        
        if ($testFile) {
            $command = "vendor/bin/phpunit {$testFile}";
        } else {
            $command = "vendor/bin/phpunit";
        }
        
        echo "\033[36mRunning tests...\033[0m\n";
        passthru($command, $exitCode);
        
        return $exitCode;
    }
}