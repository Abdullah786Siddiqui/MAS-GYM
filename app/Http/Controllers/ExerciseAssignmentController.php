<?php

namespace App\Http\Controllers;

use App\Http\Requests\Setup\ExerciseAssignmentRequest;
use App\Models\ExerciseAssignment;
use App\Services\Setup\ExerciseAssignmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class ExerciseAssignmentController
 *
 * Handles HTTP requests for Exercise Assignments, including the overview index,
 * interactive real-time drag-and-drop assignment workspace, and instant AJAX endpoints.
 */
class ExerciseAssignmentController extends Controller
{
    /**
     * @var ExerciseAssignmentService
     */
    protected ExerciseAssignmentService $assignmentService;

    /**
     * Inject ExerciseAssignmentService.
     *
     * @param ExerciseAssignmentService $assignmentService
     */
    public function __construct(ExerciseAssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    /**
     * Display a paginated overview listing of muscle groups with their assigned movements.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $search = $request->input('search');

        return Inertia::render('Setup/ExerciseAssignments/Index', [
            'overview' => $this->assignmentService->getOverviewList($search, 10),
            'filters'  => [
                'search' => $search,
            ],
            'stats'    => $this->assignmentService->getStatistics(),
        ]);
    }

    /**
     * Display the interactive real-time Drag & Drop assignment workspace.
     *
     * @param Request $request
     * @return Response
     */
    public function manage(Request $request): Response
    {
        $muscleGroups = $this->assignmentService->getAllMuscleGroupsWithCounts();

        // Selected muscle group ID (from query or first available)
        $selectedMuscleGroupId = $request->filled('muscle_group_id')
            ? (int) $request->input('muscle_group_id')
            : ($muscleGroups->first()?->id ?? null);

        $assignedExercises = $selectedMuscleGroupId
            ? $this->assignmentService->getAssignedExercises($selectedMuscleGroupId)
            : collect();

        $assignedExerciseIds = $selectedMuscleGroupId
            ? $this->assignmentService->getAssignedExerciseIds($selectedMuscleGroupId)
            : [];

        return Inertia::render('Setup/ExerciseAssignments/Assign', [
            'muscleGroups'          => $muscleGroups,
            'selectedMuscleGroupId' => $selectedMuscleGroupId,
            'exercisesPool'         => $this->assignmentService->getExercisesPool(),
            'assignedExercises'     => $assignedExercises,
            'assignedExerciseIds'   => $assignedExerciseIds,
        ]);
    }

    /**
     * Real-time assign endpoint (Supports both AJAX / JSON and Inertia visit).
     *
     * @param ExerciseAssignmentRequest $request
     * @return JsonResponse|RedirectResponse
     */
    public function assign(ExerciseAssignmentRequest $request): JsonResponse|RedirectResponse
    {
        $exerciseId = (int) $request->input('exercise_id');
        $muscleGroupId = (int) $request->input('muscle_group_id');
        $notes = $request->input('notes');

        $assignment = $this->assignmentService->assign($exerciseId, $muscleGroupId, $notes);

        if ($request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Exercise successfully assigned.',
                'assignment' => $assignment,
            ]);
        }

        return redirect()->back()->with('success', 'Exercise assigned successfully.');
    }

    /**
     * Real-time unassign endpoint (Supports both AJAX / JSON and Inertia visit).
     *
     * @param ExerciseAssignmentRequest $request
     * @return JsonResponse|RedirectResponse
     */
    public function unassign(ExerciseAssignmentRequest $request): JsonResponse|RedirectResponse
    {
        $exerciseId = (int) $request->input('exercise_id');
        $muscleGroupId = (int) $request->input('muscle_group_id');

        $this->assignmentService->unassign($exerciseId, $muscleGroupId);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Exercise removed from muscle group.',
            ]);
        }

        return redirect()->back()->with('success', 'Exercise removed from muscle group.');
    }

    /**
     * Remove the specified assignment resource (soft-delete).
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $assignment = ExerciseAssignment::query()->findOrFail($id);
        $assignment->update(['record_deleted' => true]);

        return redirect()->back()->with('success', 'Assignment removed successfully.');
    }
}
