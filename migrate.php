<?php

require_once __DIR__ . '/bootstrap/app.php';

use App\Database\Migrator;

$migrator = new Migrator();

if ($argc < 2) {
    echo "Usage: php migrate.php [migrate|seed|rollback|fresh]\n";
    exit(1);
}

$command = $argv[1];

switch ($command) {
    case 'migrate':
        echo "Running migrations...\n";
        $migrator->migrate();
        echo "Migrations completed!\n";
        break;
        
    case 'seed':
        echo "Running seeders...\n";
        $migrator->seed();
        echo "Seeding completed!\n";
        break;
        
    case 'rollback':
        echo "Rolling back migrations...\n";
        $migrator->rollback();
        echo "Rollback completed!\n";
        break;
        
    case 'fresh':
        echo "Fresh migration (rollback + migrate + seed)...\n";
        $migrator->rollback();
        $migrator->migrate();
        $migrator->seed();
        echo "Fresh migration completed!\n";
        break;
        
    default:
        echo "Unknown command: $command\n";
        echo "Available commands: migrate, seed, rollback, fresh\n";
        exit(1);
}