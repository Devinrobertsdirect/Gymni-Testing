<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermCondition extends Model
{
    use HasFactory;
    protected $table    = 'terms_condition';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title','content'
    ];
}
