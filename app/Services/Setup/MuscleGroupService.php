<?php

namespace App\Services\Setup;

use App\Models\MuscleGroup;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class MuscleGroupService
 *
 * Encapsulates all business logic and Eloquent ORM operations for Muscle Groups,
 * including soft record deletion and automatic record revival.
 */
class MuscleGroupService
{
    /**
     * Retrieve paginated muscle groups that are not deleted.
     *
     * @param string|null $search  Search keyword matching name or description
     * @param string|null $status  Status filter ('active', 'inactive')
     * @param int         $perPage Number of records per page
     * @return LengthAwarePaginator
     */
    public function getPaginatedList(?string $search = null, ?string $status = null, int $perPage = 10): LengthAwarePaginator
    {
        return MuscleGroup::query()
            ->notDeleted()
            ->withCount(['exercises' => function ($query) {
                $query->where('exercises.record_deleted', false)
                      ->where('exercise_assignments.record_deleted', false);
            }])
            ->when($search, function ($query, $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                      ->orWhere('description', 'like', "%{$term}%");
                });
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                if ($status === 'active' || $status === '1') {
                    $query->where('is_active', true);
                } elseif ($status === 'inactive' || $status === '0') {
                    $query->where('is_active', false);
                }
            })
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Retrieve all active and non-deleted muscle groups.
     *
     * @return Collection<int, MuscleGroup>
     */
    public function getAllActive(): Collection
    {
        return MuscleGroup::query()
            ->active()
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'is_active']);
    }

    /**
     * Find a single muscle group by ID ensuring it is not deleted.
     *
     * @param int $id
     * @return MuscleGroup
     */
    public function findById(int $id): MuscleGroup
    {
        return MuscleGroup::query()
            ->notDeleted()
            ->with(['exercises' => function ($query) {
                $query->where('exercises.record_deleted', false);
            }])
            ->findOrFail($id);
    }

    /**
     * Store a new muscle group or revive an existing one if previously deleted.
     *
     * @param array $data Validated attributes
     * @return MuscleGroup
     */
    public function create(array $data): MuscleGroup
    {
        // Check if an existing muscle group with the same name was previously deleted
        $existingDeleted = MuscleGroup::query()
            ->where('name', $data['name'])
            ->where('record_deleted', true)
            ->first();

        if ($existingDeleted) {
            // Revive the record with updated information instead of creating a new row
            $existingDeleted->update([
                'name'           => $data['name'],
                'description'    => $data['description'] ?? null,
                'is_active'      => $data['is_active'] ?? true,
                'record_deleted' => false,
            ]);

            return $existingDeleted->fresh();
        }

        // Standard Eloquent creation
        return MuscleGroup::create([
            'name'           => $data['name'],
            'description'    => $data['description'] ?? null,
            'is_active'      => $data['is_active'] ?? true,
            'record_deleted' => false,
        ]);
    }

    /**
     * Update an existing muscle group using Eloquent.
     *
     * @param MuscleGroup $muscleGroup Target model instance
     * @param array       $data        Validated attributes
     * @return MuscleGroup
     */
    public function update(MuscleGroup $muscleGroup, array $data): MuscleGroup
    {
        $muscleGroup->update([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'is_active'   => $data['is_active'] ?? $muscleGroup->is_active,
        ]);

        return $muscleGroup->fresh();
    }

    /**
     * Mark a muscle group as deleted without permanently removing from database.
     *
     * @param MuscleGroup $muscleGroup Target model instance
     * @return bool
     */
    public function delete(MuscleGroup $muscleGroup): bool
    {
        // Set record_deleted flag to true
        $muscleGroup->update([
            'record_deleted' => true,
            'is_active'      => false,
        ]);

        // Soft delete associated assignments
        $muscleGroup->exerciseAssignments()->update([
            'record_deleted' => true,
        ]);

        return true;
    }

    /**
     * Toggle the active status of a muscle group.
     *
     * @param MuscleGroup $muscleGroup Target model instance
     * @return MuscleGroup
     */
    public function toggleStatus(MuscleGroup $muscleGroup): MuscleGroup
    {
        $muscleGroup->update([
            'is_active' => !$muscleGroup->is_active,
        ]);

        return $muscleGroup->fresh();
    }

    /**
     * Retrieve aggregate metrics for non-deleted records.
     *
     * @return array<string, int>
     */
    public function getStatistics(): array
    {
        return [
            'total'    => MuscleGroup::notDeleted()->count(),
            'active'   => MuscleGroup::active()->count(),
            'inactive' => MuscleGroup::notDeleted()->where('is_active', false)->count(),
        ];
    }
}
