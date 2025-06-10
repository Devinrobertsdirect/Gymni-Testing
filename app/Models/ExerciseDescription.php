<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseDescription extends Model
{
    use HasFactory;

    protected $table = 'exercise_description';

    // protected $guarded = [];

    protected $fillable =
    [
        'id',
        'description_mode_id',
        'exercise_title',
        'exercise_name',
        'sets',
        'sets_status',
        'reps',
        'reps_status',
        'weight',
        'weight_status',
        'rpe',
        'rpe_status',
        'notes',
        'created_at',
        'updated_at',
    ];

    public function getDescriptionMode()
    {
        return $this->belongsTo(Desmode::class, 'description_mode_id');
    }
}
