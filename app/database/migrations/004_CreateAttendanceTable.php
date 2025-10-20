<?php

namespace App\Database\Migrations;

class CreateAttendanceTable004
{
    public static function up()
    {
        return "
            CREATE TABLE IF NOT EXISTS attendance (
                id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) NOT NULL,
                date DATE NOT NULL,
                check_in TIME,
                check_out TIME,
                break_time INT DEFAULT 0,
                total_hours DECIMAL(4,2),
                status ENUM('present', 'absent', 'late', 'half_day') DEFAULT 'present',
                notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                UNIQUE KEY unique_user_date (user_id, date)
            )
        ";
    }

    public static function down()
    {
        return "DROP TABLE IF EXISTS attendance";
    }
}