<?php

namespace App\Services\Setup;

use App\Models\Exercise;
use App\Models\ExerciseAssignment;
use App\Models\MuscleGroup;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ExerciseAssignmentService
 *
 * Encapsulates business logic for managing exercise-to-muscle-group mappings,
 * drag-and-drop assignments, soft deletions, and revival logic.
 */
class ExerciseAssignmentService
{
    /**
     * Get paginated overview list of muscle groups with their assigned exercises.
     *
     * @param string|null $search  Search keyword for muscle group or exercise name
     * @param int         $perPage Records per page
     * @return LengthAwarePaginator
     */
    public function getOverviewList(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return MuscleGroup::query()
            ->notDeleted()
            ->with(['exercises' => function ($query) {
                $query->where('exercises.record_deleted', false)
                      ->where('exercise_assignments.record_deleted', false)
                      ->select('exercises.id', 'exercises.name', 'exercises.equipment', 'exercises.is_active')
                      ->withPivot('id as assignment_id', 'notes', 'is_active');
            }])
            ->withCount(['exercises' => function ($query) {
                $query->where('exercises.record_deleted', false)
                      ->where('exercise_assignments.record_deleted', false);
            }])
            ->when($search, function ($query, $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                      ->orWhere('description', 'like', "%{$term}%")
                      ->orWhereHas('exercises', function ($eq) use ($term) {
                          $eq->where('exercises.record_deleted', false)
                             ->where('exercise_assignments.record_deleted', false)
                             ->where('name', 'like', "%{$term}%");
                      });
                });
            })
            ->orderBy('name', 'asc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Get all active muscle groups with assigned exercise counts for selector tabs/cards.
     *
     * @return Collection<int, MuscleGroup>
     */
    public function getAllMuscleGroupsWithCounts(): Collection
    {
        return MuscleGroup::query()
            ->notDeleted()
            ->active()
            ->withCount(['exercises' => function ($query) {
                $query->where('exercises.record_deleted', false)
                      ->where('exercise_assignments.record_deleted', false);
            }])
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'description', 'is_active']);
    }

    /**
     * Get all exercises available in the pool.
     *
     * @return Collection<int, Exercise>
     */
    public function getExercisesPool(): Collection
    {
        return Exercise::query()
            ->notDeleted()
            ->active()
            ->select('id', 'name', 'equipment', 'description', 'video_url', 'is_active')
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get array of exercise IDs assigned to a specific muscle group.
     *
     * @param int $muscleGroupId
     * @return array<int>
     */
    public function getAssignedExerciseIds(int $muscleGroupId): array
    {
        return ExerciseAssignment::query()
            ->where('muscle_group_id', $muscleGroupId)
            ->where('record_deleted', false)
            ->pluck('exercise_id')
            ->toArray();
    }

    /**
     * Get assigned exercises with assignment pivot details for a specific muscle group.
     *
     * @param int $muscleGroupId
     * @return Collection<int, Exercise>
     */
    public function getAssignedExercises(int $muscleGroupId): Collection
    {
        $muscleGroup = MuscleGroup::query()->notDeleted()->find($muscleGroupId);

        if (!$muscleGroup) {
            return new Collection();
        }

        return $muscleGroup->exercises()
            ->where('exercises.record_deleted', false)
            ->wherePivot('record_deleted', false)
            ->select('exercises.id', 'exercises.name', 'exercises.equipment', 'exercises.description', 'exercises.video_url', 'exercises.is_active')
            ->withPivot('id as assignment_id', 'notes', 'is_active')
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Assign an exercise to a muscle group.
     * Revives soft-deleted assignment if it previously existed, or creates a new record.
     *
     * @param int         $exerciseId
     * @param int         $muscleGroupId
     * @param string|null $notes
     * @return ExerciseAssignment
     */
    public function assign(int $exerciseId, int $muscleGroupId, ?string $notes = null): ExerciseAssignment
    {
        // Strictly ensure both the Exercise and MuscleGroup are active and not deleted
        Exercise::query()->notDeleted()->active()->findOrFail($exerciseId);
        MuscleGroup::query()->notDeleted()->active()->findOrFail($muscleGroupId);

        // Check for existing assignment row (including record_deleted = true)
        $assignment = ExerciseAssignment::query()
            ->where('exercise_id', $exerciseId)
            ->where('muscle_group_id', $muscleGroupId)
            ->first();

        if ($assignment) {
            $assignment->update([
                'record_deleted' => false,
                'is_active'      => true,
                'notes'          => $notes ?? $assignment->notes,
            ]);
        } else {
            $assignment = ExerciseAssignment::create([
                'exercise_id'     => $exerciseId,
                'muscle_group_id' => $muscleGroupId,
                'notes'           => $notes,
                'is_active'       => true,
                'record_deleted'  => false,
            ]);
        }

        return $assignment->load(['exercise', 'muscleGroup']);
    }

    /**
     * Soft delete / unassign an exercise from a muscle group.
     *
     * @param int $exerciseId
     * @param int $muscleGroupId
     * @return bool
     */
    public function unassign(int $exerciseId, int $muscleGroupId): bool
    {
        $assignment = ExerciseAssignment::query()
            ->where('exercise_id', $exerciseId)
            ->where('muscle_group_id', $muscleGroupId)
            ->first();

        if ($assignment) {
            return $assignment->update(['record_deleted' => true]);
        }

        return false;
    }

    /**
     * Get aggregate statistics for exercise assignments.
     *
     * @return array<string, int>
     */
    public function getStatistics(): array
    {
        $totalAssignments = ExerciseAssignment::query()
            ->notDeleted()
            ->whereHas('exercise', fn($q) => $q->where('record_deleted', false))
            ->whereHas('muscleGroup', fn($q) => $q->where('record_deleted', false))
            ->count();

        $coveredMuscleGroups = MuscleGroup::query()
            ->notDeleted()
            ->whereHas('exercises', function ($q) {
                $q->where('exercises.record_deleted', false)
                  ->where('exercise_assignments.record_deleted', false);
            })
            ->count();

        $totalMuscleGroups = MuscleGroup::query()->notDeleted()->count();

        return [
            'total_assignments'     => $totalAssignments,
            'covered_muscle_groups' => $coveredMuscleGroups,
            'total_muscle_groups'   => $totalMuscleGroups,
        ];
    }
}
