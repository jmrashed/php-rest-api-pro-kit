<?php

namespace App\Database\Migrations;

class CreateDepartmentsTable002
{
    public static function up()
    {
        return "
            CREATE TABLE IF NOT EXISTS departments (
                id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                manager_id INT(11),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE SET NULL
            )
        ";
    }

    public static function down()
    {
        return "DROP TABLE IF EXISTS departments";
    }
}