<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

   
    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];

    protected $table    = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name','email','password', 'dob', 'gender', 'weight', 'gols', 'profile_bio','phone','social_login','otp','subs_plan_start','subs_plan_end','payment_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     public static function scopeSearch($query,$where=[]){
        return $query->where($where);
    }
}
