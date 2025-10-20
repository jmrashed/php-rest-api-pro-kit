<?php

namespace App\Database\Migrations;

class CreateUsersTable001
{
    public static function up()
    {
        return "
            CREATE TABLE IF NOT EXISTS users (
                id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                employee_id VARCHAR(20) UNIQUE,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                phone VARCHAR(20),
                role ENUM('admin', 'hr', 'employee') DEFAULT 'employee',
                status ENUM('active', 'inactive') DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ";
    }

    public static function down()
    {
        return "DROP TABLE IF EXISTS users";
    }
}