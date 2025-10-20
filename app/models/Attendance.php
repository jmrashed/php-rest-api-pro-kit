<?php

namespace App\Models;

use App\Core\Model;

class Attendance extends Model
{
    protected static $table = 'attendance';

    protected static $fillable = [
        'employee_id',
        'date',
        'check_in_time',
        'check_out_time',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeProfile::class, 'employee_id');
    }
}