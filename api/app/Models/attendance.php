<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attendance extends Model
{
    use HasFactory;

    protected $table =  'attendance';

    protected $fillable = [
       'employee_id',
       'date',
       'type',
       'time_in',
       'branch',
       'status',
       'time_out',
       'break_in',
       'break_out',
       'created_at',
       'updated_at'
    ];
}
