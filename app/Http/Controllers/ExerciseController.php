<?php

namespace App\Http\Controllers;

use App\Http\Requests\Setup\ExerciseRequest;
use App\Models\Exercise;
use App\Services\Setup\ExerciseService;
use App\Services\Setup\MuscleGroupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class ExerciseController
 *
 * Handles HTTP requests for Exercise resources.
 * Delegates data operations to ExerciseService and renders Inertia views.
 */
class ExerciseController extends Controller
{
    /**
     * @var ExerciseService
     */
    protected ExerciseService $exerciseService;

    /**
     * @var MuscleGroupService
     */
    protected MuscleGroupService $muscleGroupService;

    /**
     * Inject ExerciseService and MuscleGroupService.
     *
     * @param ExerciseService    $exerciseService
     * @param MuscleGroupService $muscleGroupService
     */
    public function __construct(
        ExerciseService $exerciseService,
        MuscleGroupService $muscleGroupService
    ) {
        $this->exerciseService = $exerciseService;
        $this->muscleGroupService = $muscleGroupService;
    }

    /**
     * Display a paginated listing of exercises with filters.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $search = $request->input('search');
        $equipment = $request->input('equipment');
        $muscleGroupId = $request->filled('muscle_group_id') ? (int) $request->input('muscle_group_id') : null;

        return Inertia::render('Setup/Exercises/Index', [
            'exercises'        => $this->exerciseService->getPaginatedList($search, $equipment, $muscleGroupId, 10),
            'filters'          => [
                'search'          => $search,
                'equipment'       => $equipment,
                'muscle_group_id' => $muscleGroupId,
            ],
            'equipmentOptions' => ExerciseRequest::VALID_EQUIPMENT,
            'muscleGroups'     => $this->muscleGroupService->getAllActive(),
            'stats'            => $this->exerciseService->getStatistics(),
        ]);
    }

    /**
     * Store a newly created exercise in storage.
     *
     * @param ExerciseRequest $request Validated request
     * @return RedirectResponse
     */
    public function store(ExerciseRequest $request): RedirectResponse
    {
        $this->exerciseService->create($request->validated());

        return redirect()
            ->route('setup.exercises.index')
            ->with('success', 'Exercise created successfully.');
    }

    /**
     * Update the specified exercise in storage.
     *
     * @param ExerciseRequest $request  Validated request
     * @param Exercise        $exercise Target model instance
     * @return RedirectResponse
     */
    public function update(ExerciseRequest $request, Exercise $exercise): RedirectResponse
    {
        $this->exerciseService->update($exercise, $request->validated());

        return redirect()
            ->route('setup.exercises.index')
            ->with('success', 'Exercise updated successfully.');
    }

    /**
     * Remove the specified exercise from storage.
     *
     * @param Exercise $exercise Target model instance
     * @return RedirectResponse
     */
    public function destroy(Exercise $exercise): RedirectResponse
    {
        $this->exerciseService->delete($exercise);

        return redirect()
            ->route('setup.exercises.index')
            ->with('success', 'Exercise deleted successfully.');
    }
}
