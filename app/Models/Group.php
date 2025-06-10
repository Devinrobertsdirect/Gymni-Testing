<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
  
    use HasFactory;
    protected $table    = 'groups';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image','group_name','members','group_description','created_by','user_id'
    ];

     public static function uploadVideo($video,$name=""){
        $imageName = $name.time().'.'.$video->extension();
        $video->move(public_path('group/'), $imageName);
        $imageName = filter_var($imageName,FILTER_SANITIZE_STRING);
        return $imageName;
    }
}
