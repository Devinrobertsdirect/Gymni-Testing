<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostBlock extends Model
{
    use HasFactory;
    protected $table        = 'post_block';
    protected $fillable = ['block_user_id','block_by'];


}
