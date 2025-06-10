<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermConditionMulti extends Model
{
    use HasFactory;
    protected $table    = 'terms_condition_multi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image','title','content'
    ];

     public static function uploadVideo($video,$name=""){
        $imageName = $name.time().'.'.$video->extension();
        $video->move(public_path('termImage/'), $imageName);
        $imageName = filter_var($imageName,FILTER_SANITIZE_STRING);
        return $imageName;
    }
}
