<?php

namespace App\Database\Migrations;

class CreatePerformanceReviewsTable008
{
    public static function up()
    {
        return "
            CREATE TABLE IF NOT EXISTS performance_reviews (
                id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) NOT NULL,
                reviewer_id INT(11) NOT NULL,
                review_period_start DATE NOT NULL,
                review_period_end DATE NOT NULL,
                goals TEXT,
                achievements TEXT,
                rating ENUM('excellent', 'good', 'satisfactory', 'needs_improvement') DEFAULT 'satisfactory',
                comments TEXT,
                status ENUM('draft', 'completed') DEFAULT 'draft',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ";
    }

    public static function down()
    {
        return "DROP TABLE IF EXISTS performance_reviews";
    }
}