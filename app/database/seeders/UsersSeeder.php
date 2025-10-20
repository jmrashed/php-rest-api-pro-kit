<?php

namespace App\Database\Seeders;

class UsersSeeder
{
    public static function run()
    {
        return "
            INSERT INTO users (employee_id, name, email, password, phone, role) VALUES
            ('EMP001', 'Admin User', 'admin@hrms.com', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567890', 'admin'),
            ('EMP002', 'HR Manager', 'hr@hrms.com', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567891', 'hr'),
            ('EMP003', 'John Doe', 'john@hrms.com', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567892', 'employee'),
            ('EMP004', 'Jane Smith', 'jane@hrms.com', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567893', 'employee'),
            ('EMP005', 'Mike Johnson', 'mike@hrms.com', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567894', 'employee')
        ";
    }
}