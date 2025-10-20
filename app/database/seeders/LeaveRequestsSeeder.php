<?php

namespace App\Database\Seeders;

class LeaveRequestsSeeder
{
    public static function run()
    {
        return "
            INSERT INTO leave_requests (user_id, leave_type_id, start_date, end_date, days_requested, reason, status, approved_by) VALUES
            (3, 1, '2024-02-01', '2024-02-05', 5, 'Family vacation', 'approved', 2),
            (4, 2, '2024-01-20', '2024-01-22', 3, 'Medical appointment', 'approved', 2),
            (5, 1, '2024-03-01', '2024-03-03', 3, 'Personal work', 'pending', NULL)
        ";
    }
}