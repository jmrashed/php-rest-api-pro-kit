<?php

namespace App\Database\Seeders;

class DepartmentsSeeder
{
    public static function run()
    {
        return "
            INSERT INTO departments (name, description) VALUES
            ('Human Resources', 'Manages employee relations and policies'),
            ('Information Technology', 'Handles technology infrastructure'),
            ('Finance', 'Manages financial operations'),
            ('Marketing', 'Handles marketing and promotions'),
            ('Operations', 'Manages day-to-day operations')
        ";
    }
}