<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsMulti extends Model
{
    use HasFactory;
    protected $table    = 'aboutus_multi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image','title','content'
    ];

     public static function uploadVideo($video,$name=""){
        $imageName = $name.time().'.'.$video->extension();
        $video->move(public_path('aboutImage/'), $imageName);
        $imageName = filter_var($imageName,FILTER_SANITIZE_STRING);
        return $imageName;
    }

}
