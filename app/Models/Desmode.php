<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desmode extends Model
{
    use HasFactory;
    protected $table        = 'description_mode';
    protected $primaryKey   = 'id';

    protected $fillable = [
        'video_id ',
        'video_mode_lastid',
        'description_id',
        'img_title',
        'description',
        'category',
        'demo_videoid',
        'duration',
        'intensity_rating',
        'muscle_group',
        'equipment',
        'rating',
        'intensity',
        'instructor',
        'img_path',
        'like',
        'share',
        'created_at',
        'updated_at',
        'deleted_at',
        'round_description',
        'demo_video'
    ];

    public static function uploadVideo($video, $name = "")
    {
        $imageName = $name . time() . '.' . $video->extension();
        $video->move(public_path('demo_video/'), $imageName);
        $imageName = filter_var($imageName, FILTER_SANITIZE_STRING);
        return $imageName;
    }

    public function getDescription()
    {
        return $this->belongsTo(ExerciseDescription::class, 'description_id');
    }
}
