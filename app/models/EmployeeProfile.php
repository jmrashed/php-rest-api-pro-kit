<?php

namespace App\Models;

use App\Core\Model;

class EmployeeProfile extends Model
{
    protected static $table = 'employee_profiles';

    protected static $fillable = [
        'user_id',
        'department_id',
        'position',
        'hire_date',
        'salary',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}