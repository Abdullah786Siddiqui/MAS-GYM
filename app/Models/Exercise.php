<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exercise extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exercises';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'equipment',
        'video_url',
        'is_active',
        'record_deleted',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active'      => 'boolean',
        'record_deleted' => 'boolean',
    ];

    /**
     * Get the exercise assignments for this exercise.
     */
    public function exerciseAssignments(): HasMany
    {
        return $this->hasMany(ExerciseAssignment::class)->where('record_deleted', false);
    }

    /**
     * The muscle groups targeted by this exercise.
     */
    public function muscleGroups(): BelongsToMany
    {
        return $this->belongsToMany(MuscleGroup::class, 'exercise_assignments')
            ->wherePivot('record_deleted', false)
            ->where('muscle_groups.record_deleted', false)
            ->withPivot('notes', 'is_active', 'record_deleted')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include records that are not deleted.
     */
    public function scopeNotDeleted(Builder $query): Builder
    {
        return $query->where('record_deleted', false);
    }

    /**
     * Scope a query to only include active and non-deleted exercises.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('record_deleted', false);
    }
}
