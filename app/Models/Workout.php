<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'difficulty',
        'duration',
        'equipment',
        'muscle_groups',
        'instructions',
        'video_url',
        'image_url',
        'created_by',
        'is_public',
        'tags',
        'calories_burned',
        'sets',
        'reps',
        'weight',
        'rest_time'
    ];

    protected $casts = [
        'muscle_groups' => 'array',
        'tags' => 'array',
        'is_public' => 'boolean',
        'sets' => 'integer',
        'reps' => 'integer',
        'weight' => 'decimal:2',
        'rest_time' => 'integer',
        'calories_burned' => 'integer',
        'duration' => 'integer'
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    // Helper methods
    public function getFormattedDurationAttribute()
    {
        $minutes = $this->duration;
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        if ($hours > 0) {
            return $hours . 'h ' . $remainingMinutes . 'm';
        }
        
        return $minutes . 'm';
    }

    public function canBeAccessedBy(User $user)
    {
        // Admin can access all workouts
        if ($user->isAdmin()) {
            return true;
        }
        
        // Manager can access public workouts and their own created workouts
        if ($user->isManager()) {
            return $this->is_public || $this->created_by === $user->id;
        }
        
        // Client can access public workouts and workouts created by their trainer
        if ($user->isClient()) {
            if ($this->is_public) {
                return true;
            }
            
            if ($user->trainer && $this->created_by === $user->trainer->id) {
                return true;
            }
        }
        
        return false;
    }
}
