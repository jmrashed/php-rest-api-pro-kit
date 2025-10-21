<?php

namespace App\Cli\Commands;

use App\Cli\CommandInterface;

class CacheCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'cache';
    }

    public function getDescription(): string
    {
        return 'Manage application cache (clear)';
    }

    public function execute(array $args): int
    {
        $action = $args[0] ?? 'clear';
        
        if ($action === 'clear') {
            $this->clearCache();
            echo "\033[32mCache cleared successfully!\033[0m\n";
            return 0;
        }
        
        echo "\033[31mUnknown cache action: {$action}\033[0m\n";
        return 1;
    }

    private function clearCache(): void
    {
        $cacheDir = dirname(__DIR__, 3) . '/storage/cache';
        
        if (is_dir($cacheDir)) {
            $files = glob($cacheDir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }
}