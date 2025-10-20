<?php

namespace App\Models;

use App\Core\Model;

class Payroll extends Model
{
    protected static $table = 'payroll';

    protected static $fillable = [
        'employee_id',
        'pay_date',
        'base_salary',
        'bonuses',
        'deductions',
        'net_salary',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeProfile::class, 'employee_id');
    }
}