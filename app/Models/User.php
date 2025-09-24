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
        'name','email','password', 'dob', 'gender', 'weight', 'gols', 'profile_bio','phone','social_login','otp','subs_plan_start','subs_plan_end','payment_status',
        'role', 'trainer_code', 'assigned_trainer_id', 'is_active', 'permissions'
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

    // Role-based methods
    public function isAdmin() {
        return $this->role === 'admin';
    }

    public function isManager() {
        return $this->role === 'manager';
    }

    public function isClient() {
        return $this->role === 'client';
    }

    // Trainer code methods
    public function generateTrainerCode() {
        if ($this->isManager()) {
            $this->trainer_code = 'TRN' . strtoupper(substr(md5($this->id . time()), 0, 8));
            $this->save();
            return $this->trainer_code;
        }
        return null;
    }

    // Client assignment methods
    public function assignToTrainer($trainerCode) {
        $trainer = User::where('trainer_code', $trainerCode)
                      ->where('role', 'manager')
                      ->first();
        
        if ($trainer) {
            $this->assigned_trainer_id = $trainer->id;
            $this->save();
            return true;
        }
        return false;
    }

    // Relationship methods
    public function trainer() {
        return $this->belongsTo(User::class, 'assigned_trainer_id');
    }

    public function clients() {
        return $this->hasMany(User::class, 'assigned_trainer_id');
    }

    // Permission methods
    public function hasPermission($permission) {
        if ($this->isAdmin()) return true;
        
        $permissions = json_decode($this->permissions ?? '[]', true);
        return in_array($permission, $permissions);
    }

    public function addPermission($permission) {
        $permissions = json_decode($this->permissions ?? '[]', true);
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->permissions = json_encode($permissions);
            $this->save();
        }
    }

    public function removePermission($permission) {
        $permissions = json_decode($this->permissions ?? '[]', true);
        $permissions = array_diff($permissions, [$permission]);
        $this->permissions = json_encode(array_values($permissions));
        $this->save();
    }
}
