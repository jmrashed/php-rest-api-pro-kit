<?php

namespace App\Core;

use PDO;
use PDOException;
use App\Config\Env;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $db_connection = Env::get('DB_CONNECTION', 'mysql');
        $db_host = Env::get('DB_HOST', '127.0.0.1');
        $db_port = Env::get('DB_PORT', '3306');
        $db_database = Env::get('DB_DATABASE', 'api_starter_kit');
        $db_username = Env::get('DB_USERNAME', 'root');
        $db_password = Env::get('DB_PASSWORD', '');

        $dsn = "{$db_connection}:host={$db_host};port={$db_port};dbname={$db_database}";

        try {
            $this->connection = new PDO($dsn, $db_username, $db_password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // In a real application, you would log this error and show a generic message.
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}