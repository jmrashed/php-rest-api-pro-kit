<?php

namespace App\Database;

use App\Core\Database;

class Migrator
{
    private $db;
    private $migrationsPath;
    private $seedersPath;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->migrationsPath = __DIR__ . '/migrations';
        $this->seedersPath = __DIR__ . '/seeders';
    }

    public function migrate()
    {
        $migrations = glob($this->migrationsPath . '/*.php');
        sort($migrations);

        foreach ($migrations as $migration) {
            $baseName = basename($migration, '.php');
            $className = 'App\\Database\\Migrations\\' . substr($baseName, 4); // Remove number prefix
            if (strpos($baseName, '001_') === 0) $className .= '001';
            elseif (strpos($baseName, '002_') === 0) $className = 'App\\Database\\Migrations\\CreateDepartmentsTable002';
            elseif (strpos($baseName, '003_') === 0) $className = 'App\\Database\\Migrations\\CreateEmployeeProfilesTable003';
            elseif (strpos($baseName, '004_') === 0) $className = 'App\\Database\\Migrations\\CreateAttendanceTable004';
            elseif (strpos($baseName, '005_') === 0) $className = 'App\\Database\\Migrations\\CreateLeaveTypesTable005';
            elseif (strpos($baseName, '006_') === 0) $className = 'App\\Database\\Migrations\\CreateLeaveRequestsTable006';
            elseif (strpos($baseName, '007_') === 0) $className = 'App\\Database\\Migrations\\CreatePayrollTable007';
            elseif (strpos($baseName, '008_') === 0) $className = 'App\\Database\\Migrations\\CreatePerformanceReviewsTable008';
            elseif (strpos($baseName, '009_') === 0) $className = 'App\\Database\\Migrations\\CreateTokensTable009';
            
            require_once $migration;
            $sql = $className::up();
            $this->db->exec($sql);
            echo "Migrated: " . basename($migration) . "\n";
        }
    }

    public function seed()
    {
        // Define seeder order based on dependencies
        $seederOrder = [
            'DepartmentsSeeder',
            'UsersSeeder',
            'EmployeeProfilesSeeder',
            'LeaveTypesSeeder',
            'LeaveRequestsSeeder',
            'AttendanceSeeder',
            'PayrollSeeder'
        ];

        foreach ($seederOrder as $seederName) {
            $seederFile = $this->seedersPath . '/' . $seederName . '.php';
            if (file_exists($seederFile)) {
                $className = 'App\\Database\\Seeders\\' . $seederName;
                require_once $seederFile;

                try {
                    $sql = $className::run();
                    $this->db->exec($sql);
                    echo "Seeded: " . $seederName . ".php\n";
                } catch (\PDOException $e) {
                    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                        echo "Skipped (already exists): " . $seederName . ".php\n";
                    } else {
                        throw $e;
                    }
                }
            }
        }
    }

    public function rollback()
    {
        $migrations = glob($this->migrationsPath . '/*.php');
        rsort($migrations);

        foreach ($migrations as $migration) {
            $baseName = basename($migration, '.php');
            $className = 'App\\Database\\Migrations\\' . substr($baseName, 4); // Remove number prefix
            if (strpos($baseName, '001_') === 0) $className .= '001';
            elseif (strpos($baseName, '002_') === 0) $className = 'App\\Database\\Migrations\\CreateDepartmentsTable002';
            elseif (strpos($baseName, '003_') === 0) $className = 'App\\Database\\Migrations\\CreateEmployeeProfilesTable003';
            elseif (strpos($baseName, '004_') === 0) $className = 'App\\Database\\Migrations\\CreateAttendanceTable004';
            elseif (strpos($baseName, '005_') === 0) $className = 'App\\Database\\Migrations\\CreateLeaveTypesTable005';
            elseif (strpos($baseName, '006_') === 0) $className = 'App\\Database\\Migrations\\CreateLeaveRequestsTable006';
            elseif (strpos($baseName, '007_') === 0) $className = 'App\\Database\\Migrations\\CreatePayrollTable007';
            elseif (strpos($baseName, '008_') === 0) $className = 'App\\Database\\Migrations\\CreatePerformanceReviewsTable008';
            elseif (strpos($baseName, '009_') === 0) $className = 'App\\Database\\Migrations\\CreateTokensTable009';

            require_once $migration;
            $sql = $className::down();
            $this->db->exec($sql);
            echo "Rolled back: " . basename($migration) . "\n";
        }
    }
}