<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoMode extends Model
{
    use HasFactory;
    protected $table        = 'video_mode';
    protected $primaryKey   = 'id';

    protected $fillable = [
        'video_id ', 'video_title', 'category', 'duration', 'intensity_rating', 'demo_videoid', 'workout_video_id', 'muscle_group', 'equipment', 'rating', 'intensity', 'instructor', 'video_path', 'like', 'share', 'created_at', 'updated_at', 'deleted_at', 'description'
    ];

    public static function uploadVideo($video, $name = "")
    {
        $imageName = $name . time() . '.' . $video->extension();
        $video->move(public_path('videos/'), $imageName);
        $imageName = filter_var($imageName, FILTER_SANITIZE_STRING);
        return $imageName;
    }

    public static function videoDuration($video_path)
    {
        $getID3     = new \getID3;
        $file       = $getID3->analyze($video_path);
        $duration   = date('H:i:s', $file['playtime_seconds']);
        return $duration;
    }
}
