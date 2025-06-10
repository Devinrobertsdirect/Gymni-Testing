<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privacypolicy extends Model
{
    use HasFactory;
    protected $table    = 'privacy_policy';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title','content'
    ];
}

