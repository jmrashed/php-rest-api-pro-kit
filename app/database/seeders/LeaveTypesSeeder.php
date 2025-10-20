<?php

namespace App\Database\Seeders;

class LeaveTypesSeeder
{
    public static function run()
    {
        return "
            INSERT INTO leave_types (name, days_allowed, description) VALUES
            ('Annual Leave', 21, 'Yearly vacation days'),
            ('Sick Leave', 10, 'Medical leave'),
            ('Maternity Leave', 90, 'Maternity leave for mothers'),
            ('Paternity Leave', 7, 'Paternity leave for fathers'),
            ('Emergency Leave', 3, 'Emergency situations')
        ";
    }
}