<?php

namespace App\Models;

use App\Core\Model;

class PerformanceReview extends Model
{
    protected static $table = 'performance_reviews';

    protected static $fillable = [
        'employee_id',
        'reviewer_id',
        'review_date',
        'rating',
        'comments',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeProfile::class, 'employee_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}