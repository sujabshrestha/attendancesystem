<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'leave_type_id',
        'document_id',
        'reason',
        'status',
        'approved',
        'company_id'
    ];

}
