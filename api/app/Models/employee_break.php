<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employee_break extends Model
{
    use HasFactory;
    protected $table =  'break';

    protected $fillable = [
        'attendance',
        'employee',
        'break_date',
        'break_in',
        'break_out',
        'break_status',
        'created_at',
       'updated_at'
    ];
}
