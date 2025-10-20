<?php

namespace App\Database\Migrations;

class CreateTokensTable009
{
    public static function up()
    {
        return "
            CREATE TABLE IF NOT EXISTS tokens (
                id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) NOT NULL,
                token VARCHAR(500) NOT NULL,
                expires_at TIMESTAMP NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ";
    }

    public static function down()
    {
        return "DROP TABLE IF EXISTS tokens";
    }
}