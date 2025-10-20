<?php

namespace App\Database\Migrations;

class CreateLeaveRequestsTable006
{
    public static function up()
    {
        return "
            CREATE TABLE IF NOT EXISTS leave_requests (
                id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) NOT NULL,
                leave_type_id INT(11) NOT NULL,
                start_date DATE NOT NULL,
                end_date DATE NOT NULL,
                days_requested INT NOT NULL,
                reason TEXT,
                status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                approved_by INT(11),
                approved_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (leave_type_id) REFERENCES leave_types(id) ON DELETE CASCADE,
                FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
            )
        ";
    }

    public static function down()
    {
        return "DROP TABLE IF EXISTS leave_requests";
    }
}