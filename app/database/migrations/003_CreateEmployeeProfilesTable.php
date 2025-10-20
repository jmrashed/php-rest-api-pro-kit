<?php

namespace App\Database\Migrations;

class CreateEmployeeProfilesTable003
{
    public static function up()
    {
        return "
            CREATE TABLE IF NOT EXISTS employee_profiles (
                id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) NOT NULL,
                department_id INT(11),
                position VARCHAR(255),
                salary DECIMAL(10,2),
                hire_date DATE,
                address TEXT,
                emergency_contact VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
            )
        ";
    }

    public static function down()
    {
        return "DROP TABLE IF EXISTS employee_profiles";
    }
}