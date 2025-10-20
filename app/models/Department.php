<?php

namespace App\Models;

use App\Core\Model;

class Department extends Model
{
    protected static $table = 'departments';

    protected static $fillable = [
        'name',
        'description',
    ];
}