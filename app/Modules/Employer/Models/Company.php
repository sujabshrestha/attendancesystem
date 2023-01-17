<?php

namespace Employer\Models;

use App\Models\Candidate;
use App\Models\CompanyGovernmentleave;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
        'code',
        'employer_id',
        'address',
        'phone',
        'email',
        'working_days',
        'office_hour_start',
        'office_hour_end'
    ];

    protected $time =[
        'office_hour_start',
        'office_hour_end'
    ];

    public function employer(){
        return $this->belongsTo(Employer::class, 'employer_id');
    }

    public function candidates(){
        return $this->hasMany(Candidate::class, 'company_id');
    }


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }


    public function govLeaves(){
        return $this->hasMany(CompanyGovernmentleave::class, 'company_id');
    }


    public function specialLeaves(){
        return $this->hasMany(CompanyGovernmentleave::class, 'company_id');
    }

    public function leaveTypes(){
        return $this->hasMany(LeaveType::class, 'company_id');
    }



}
