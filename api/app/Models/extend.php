<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class extend extends Model
{
    use HasFactory;

    protected $table = 'extend';

    
    protected $fillable = [
        'employee',
        'attendance',
        'date',
        'switch_in',
        'switch_out',
        'status',
        'created_at',
        'updated_at'
     ];
}
