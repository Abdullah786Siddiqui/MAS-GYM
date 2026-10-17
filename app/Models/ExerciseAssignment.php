<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseAssignment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exercise_assignments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'exercise_id',
        'muscle_group_id',
        'notes',
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
     * Get the exercise associated with this assignment.
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class)->where('record_deleted', false);
    }

    /**
     * Get the muscle group associated with this assignment.
     */
    public function muscleGroup(): BelongsTo
    {
        return $this->belongsTo(MuscleGroup::class)->where('record_deleted', false);
    }

    /**
     * Scope a query to only include records that are not deleted.
     */
    public function scopeNotDeleted(Builder $query): Builder
    {
        return $query->where('record_deleted', false);
    }

    /**
     * Scope a query to only include active assignments.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('record_deleted', false);
    }
}
