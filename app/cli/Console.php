<?php

namespace App\Cli;

class Console
{
    private $commands = [];

    public function register(CommandInterface $command): void
    {
        $this->commands[$command->getName()] = $command;
    }

    public function run(array $argv): int
    {
        $commandName = $argv[1] ?? 'help';
        $args = array_slice($argv, 2);

        if ($commandName === 'help') {
            $this->showHelp();
            return 0;
        }

        if (!isset($this->commands[$commandName])) {
            $this->output("Command '{$commandName}' not found.", 'error');
            $this->showHelp();
            return 1;
        }

        return $this->commands[$commandName]->execute($args);
    }

    private function showHelp(): void
    {
        $this->output("PHP REST API Pro Kit - CLI Tool\n", 'info');
        $this->output("Available commands:\n");
        
        foreach ($this->commands as $command) {
            $this->output("  {$command->getName()}\t{$command->getDescription()}");
        }
        
        $this->output("\nUsage: php console <command> [options]");
    }

    public function output(string $message, string $type = 'default'): void
    {
        $colors = [
            'error' => "\033[31m",
            'success' => "\033[32m",
            'warning' => "\033[33m",
            'info' => "\033[36m",
            'default' => "\033[0m"
        ];

        $color = $colors[$type] ?? $colors['default'];
        echo $color . $message . $colors['default'] . "\n";
    }
}