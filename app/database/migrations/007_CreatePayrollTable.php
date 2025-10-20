<?php

namespace App\Database\Migrations;

class CreatePayrollTable007
{
    public static function up()
    {
        return "
            CREATE TABLE IF NOT EXISTS payroll (
                id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) NOT NULL,
                month TINYINT NOT NULL,
                year YEAR NOT NULL,
                basic_salary DECIMAL(10,2) NOT NULL,
                allowances DECIMAL(10,2) DEFAULT 0,
                deductions DECIMAL(10,2) DEFAULT 0,
                overtime_hours DECIMAL(4,2) DEFAULT 0,
                overtime_rate DECIMAL(6,2) DEFAULT 0,
                gross_salary DECIMAL(10,2) NOT NULL,
                net_salary DECIMAL(10,2) NOT NULL,
                status ENUM('draft', 'processed', 'paid') DEFAULT 'draft',
                processed_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                UNIQUE KEY unique_user_month_year (user_id, month, year)
            )
        ";
    }

    public static function down()
    {
        return "DROP TABLE IF EXISTS payroll";
    }
}