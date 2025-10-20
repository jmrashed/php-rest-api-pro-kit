<?php

namespace App\Models;

use App\Core\Model;

class LeaveType extends Model
{
    protected static $table = 'leave_types';

    protected static $fillable = [
        'name',
        'description',
        'max_days',
    ];
}