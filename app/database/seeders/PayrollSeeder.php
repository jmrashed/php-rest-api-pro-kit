<?php

namespace App\Database\Seeders;

class PayrollSeeder
{
    public static function run()
    {
        return "
            INSERT INTO payroll (user_id, month, year, basic_salary, allowances, deductions, overtime_hours, overtime_rate, gross_salary, net_salary, status) VALUES
            (3, 1, 2024, 65000.00, 5000.00, 8000.00, 10.00, 25.00, 70250.00, 62250.00, 'processed'),
            (4, 1, 2024, 60000.00, 4000.00, 7000.00, 5.00, 25.00, 64125.00, 57125.00, 'processed'),
            (5, 1, 2024, 55000.00, 3000.00, 6000.00, 8.00, 25.00, 58200.00, 52200.00, 'draft')
        ";
    }
}