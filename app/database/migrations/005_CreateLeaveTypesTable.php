<?php

namespace App\Database\Migrations;

class CreateLeaveTypesTable005
{
    public static function up()
    {
        return "
            CREATE TABLE IF NOT EXISTS leave_types (
                id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                days_allowed INT DEFAULT 0,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
    }

    public static function down()
    {
        return "DROP TABLE IF EXISTS leave_types";
    }
}