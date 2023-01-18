<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'phone',
        'address',
        'type'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function otp(){
        return $this->hasOne(UserOtp::class, 'user_id');
    }

    public function leaves(){
        return $this->hasMany(Leave::class);
    }

    public function receivedInvitation(){
        return $this->hasMany(Invitation::class,'candidate_id','id');
    }

    public function receivedCompanyInvitation(){
        $user = Auth::user();
        return $this->hasMany(Invitation::class,'candidate_id','id')->where('company_id',$user->company_id);
    }

    public function sendInvitation(){
        return $this->hasMany(Invitation::class,'employer_id','id');
    }

    public function candidateCompanies(){
        return $this->belongsToMany(Company::class,'company_candidates','candidate_id','company_id');
    }

}
