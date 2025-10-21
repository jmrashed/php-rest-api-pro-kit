<?php

namespace App\Cli\Commands;

use App\Cli\CommandInterface;
use App\Core\Database;

class MigrateCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'migrate';
    }

    public function getDescription(): string
    {
        return 'Run database migrations';
    }

    public function execute(array $args): int
    {
        $action = $args[0] ?? 'migrate';
        
        try {
            switch ($action) {
                case 'fresh':
                    $this->fresh();
                    break;
                case 'rollback':
                    $this->rollback();
                    break;
                case 'seed':
                    $this->seed();
                    break;
                default:
                    $this->migrate();
            }
            
            echo "\033[32mMigration completed successfully!\033[0m\n";
            return 0;
        } catch (\Exception $e) {
            echo "\033[31mMigration failed: " . $e->getMessage() . "\033[0m\n";
            return 1;
        }
    }

    private function migrate(): void
    {
        $db = Database::getInstance();
        $migrationFile = APP_PATH . '/database/Database.sql';
        
        if (!file_exists($migrationFile)) {
            throw new \Exception("Migration file not found: {$migrationFile}");
        }
        
        $sql = file_get_contents($migrationFile);
        $db->exec($sql);
        echo "Migrations executed\n";
    }

    private function fresh(): void
    {
        $this->rollback();
        $this->migrate();
        $this->seed();
    }

    private function rollback(): void
    {
        echo "Rolling back migrations...\n";
        // Implementation would drop tables
    }

    private function seed(): void
    {
        echo "Seeding database...\n";
        // Implementation would run seeders
    }
}