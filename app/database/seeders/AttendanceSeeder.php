<?php

namespace App\Database\Seeders;

class AttendanceSeeder
{
    public static function run()
    {
        return "
            INSERT INTO attendance (user_id, date, check_in, check_out, break_time, total_hours, status) VALUES
            (3, '2024-01-15', '09:00:00', '17:30:00', 30, 8.00, 'present'),
            (4, '2024-01-15', '09:15:00', '17:30:00', 30, 7.75, 'late'),
            (5, '2024-01-15', '09:00:00', '17:00:00', 60, 7.00, 'present'),
            (3, '2024-01-16', '09:00:00', '13:00:00', 0, 4.00, 'half_day'),
            (4, '2024-01-16', '09:00:00', '17:30:00', 30, 8.00, 'present')
        ";
    }
}