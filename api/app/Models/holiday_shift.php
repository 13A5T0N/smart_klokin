<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class holiday_shift extends Model
{
    use HasFactory;

    // override table name
    protected $table = 'holiday_shift';
}
