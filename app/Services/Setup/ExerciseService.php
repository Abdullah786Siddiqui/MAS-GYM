<?php

namespace App\Services\Setup;

use App\Models\Exercise;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class ExerciseService
 *
 * Encapsulates all business logic and Eloquent ORM operations for Exercises,
 * including soft deletion via record_deleted flag and automatic record revival.
 */
class ExerciseService
{
    /**
     * Retrieve paginated non-deleted exercises with relations and search filters.
     *
     * @param string|null $search        Search term for exercise name or description
     * @param string|null $equipment     Equipment category filter
     * @param int|null    $muscleGroupId Muscle group ID filter
     * @param int         $perPage       Records per page
     * @return LengthAwarePaginator
     */
    public function getPaginatedList(
        ?string $search = null,
        ?string $equipment = null,
        ?int $muscleGroupId = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        return Exercise::query()
            ->notDeleted()
            ->with(['muscleGroups' => function ($query) {
                $query->where('muscle_groups.record_deleted', false)
                      ->where('exercise_assignments.record_deleted', false)
                      ->select('muscle_groups.id', 'muscle_groups.name', 'muscle_groups.is_active');
            }])
            ->when($search, function ($query, $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                      ->orWhere('description', 'like', "%{$term}%");
                });
            })
            ->when($equipment, function ($query, $eq) {
                $query->where('equipment', $eq);
            })
            ->when($muscleGroupId, function ($query, $mgId) {
                $query->whereHas('muscleGroups', function ($q) use ($mgId) {
                    $q->where('muscle_groups.id', $mgId)
                      ->where('muscle_groups.record_deleted', false)
                      ->where('exercise_assignments.record_deleted', false);
                });
            })
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Find a single exercise by ID ensuring it is not deleted.
     *
     * @param int $id
     * @return Exercise
     */
    public function findById(int $id): Exercise
    {
        return Exercise::query()
            ->notDeleted()
            ->with(['muscleGroups' => function ($query) {
                $query->where('muscle_groups.record_deleted', false);
            }])
            ->findOrFail($id);
    }

    /**
     * Store a new exercise or revive an existing one if previously deleted.
     *
     * @param array $data Validated request attributes
     * @return Exercise
     */
    public function create(array $data): Exercise
    {
        // Check if an existing exercise with the same name was marked as deleted
        $existingDeleted = Exercise::query()
            ->where('name', $data['name'])
            ->where('record_deleted', true)
            ->first();

        if ($existingDeleted) {
            // Revive the deleted record instead of creating a duplicate row
            $existingDeleted->update([
                'name'           => $data['name'],
                'description'    => $data['description'] ?? null,
                'equipment'      => $data['equipment'] ?? null,
                'video_url'      => $data['video_url'] ?? null,
                'is_active'      => $data['is_active'] ?? true,
                'record_deleted' => false,
            ]);

            return $existingDeleted;
        }

        // Standard Eloquent creation
        $exercise = Exercise::create([
            'name'           => $data['name'],
            'description'    => $data['description'] ?? null,
            'equipment'      => $data['equipment'] ?? null,
            'video_url'      => $data['video_url'] ?? null,
            'is_active'      => $data['is_active'] ?? true,
            'record_deleted' => false,
        ]);

        return $exercise;
    }

    /**
     * Update an existing exercise.
     *
     * @param Exercise $exercise Target model instance
     * @param array    $data     Validated request attributes
     * @return Exercise
     */
    public function update(Exercise $exercise, array $data): Exercise
    {
        $exercise->update([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'equipment'   => $data['equipment'] ?? null,
            'video_url'   => $data['video_url'] ?? null,
            'is_active'   => $data['is_active'] ?? $exercise->is_active,
        ]);

        return $exercise;
    }

    /**
     * Soft delete an exercise by setting record_deleted to true.
     *
     * @param Exercise $exercise Target model instance
     * @return bool
     */
    public function delete(Exercise $exercise): bool
    {
        $exercise->update([
            'record_deleted' => true,
            'is_active'      => false,
        ]);

        $exercise->exerciseAssignments()->update([
            'record_deleted' => true,
        ]);

        return true;
    }

    /**
     * Toggle the active status of an exercise.
     *
     * @param Exercise $exercise Target model instance
     * @return Exercise
     */
    public function toggleStatus(Exercise $exercise): Exercise
    {
        $exercise->update([
            'is_active' => !$exercise->is_active,
        ]);

        return $exercise->fresh(['muscleGroups']);
    }

    /**
     * Aggregate metrics for non-deleted exercises.
     *
     * @return array<string, mixed>
     */
    public function getStatistics(): array
    {
        return [
            'total'    => Exercise::notDeleted()->count(),
            'active'   => Exercise::active()->count(),
            'inactive' => Exercise::notDeleted()->where('is_active', false)->count(),
        ];
    }
}
