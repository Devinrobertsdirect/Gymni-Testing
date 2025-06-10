<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $table    = 'subscription_plan';
        protected $primaryKey = 'id';
        protected $fillable = [
            'title','text','price', 'discount', 'subs_plan_start', 'subs_plan_end' , 'plan_for' , 'device_at_a_time', 'per_member', 'auto_renewal', 'created_at','updated_at','deleted_at','discount_codes','one_month_free_trial'
        ];
}
