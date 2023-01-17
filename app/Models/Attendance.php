<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'leave_type_id',
        'attendace_time',
        'employee_status',
        'company_id'
    ];

}
