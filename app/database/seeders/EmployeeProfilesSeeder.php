<?php

namespace App\Database\Seeders;

class EmployeeProfilesSeeder
{
    public static function run()
    {
        return "
            INSERT INTO employee_profiles (user_id, department_id, position, salary, hire_date, address, emergency_contact) VALUES
            (1, 1, 'System Administrator', 80000.00, '2023-01-01', '123 Admin St, City', 'Emergency Contact 1'),
            (2, 1, 'HR Manager', 70000.00, '2023-02-01', '456 HR Ave, City', 'Emergency Contact 2'),
            (3, 2, 'Software Developer', 65000.00, '2023-03-01', '789 Dev Rd, City', 'Emergency Contact 3'),
            (4, 3, 'Financial Analyst', 60000.00, '2023-04-01', '321 Finance Blvd, City', 'Emergency Contact 4'),
            (5, 4, 'Marketing Specialist', 55000.00, '2023-05-01', '654 Marketing St, City', 'Emergency Contact 5')
        ";
    }
}