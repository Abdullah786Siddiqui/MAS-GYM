<?php

namespace App\Http\Controllers;

use App\Http\Requests\Setup\MuscleGroupRequest;
use App\Models\MuscleGroup;
use App\Services\Setup\MuscleGroupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class MuscleGroupController
 *
 * Handles HTTP requests for Muscle Group resources.
 * Delegates data operations to MuscleGroupService and renders Inertia views.
 */
class MuscleGroupController extends Controller
{
    /**
     * @var MuscleGroupService
     */
    protected MuscleGroupService $muscleGroupService;

    /**
     * Inject MuscleGroupService.
     *
     * @param MuscleGroupService $muscleGroupService
     */
    public function __construct(MuscleGroupService $muscleGroupService)
    {
        $this->muscleGroupService = $muscleGroupService;
    }

    /**
     * Display a listing of muscle groups with filters, pagination, and stats.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $search = $request->input('search');
        $status = $request->input('status');

        return Inertia::render('Setup/MuscleGroups/Index', [
            'muscleGroups' => $this->muscleGroupService->getPaginatedList($search, $status, 10),
            'filters'      => [
                'search' => $search,
                'status' => $status,
            ],
            'stats'        => $this->muscleGroupService->getStatistics(),
        ]);
    }

    /**
     * Store a newly created muscle group in storage.
     *
     * @param MuscleGroupRequest $request Validated request
     * @return RedirectResponse
     */
    public function store(MuscleGroupRequest $request): RedirectResponse
    {
        $this->muscleGroupService->create($request->validated());

        return redirect()
            ->route('setup.muscle-groups.index')
            ->with('success', 'Muscle group created successfully.');
    }

    /**
     * Update the specified muscle group in storage.
     *
     * @param MuscleGroupRequest $request     Validated request
     * @param MuscleGroup        $muscleGroup Target model instance
     * @return RedirectResponse
     */
    public function update(MuscleGroupRequest $request, MuscleGroup $muscleGroup): RedirectResponse
    {
        $this->muscleGroupService->update($muscleGroup, $request->validated());

        return redirect()
            ->route('setup.muscle-groups.index')
            ->with('success', 'Muscle group updated successfully.');
    }

    /**
     * Remove the specified muscle group from storage.
     *
     * @param MuscleGroup $muscleGroup Target model instance
     * @return RedirectResponse
     */
    public function destroy(MuscleGroup $muscleGroup): RedirectResponse
    {
        $this->muscleGroupService->delete($muscleGroup);

        return redirect()
            ->route('setup.muscle-groups.index')
            ->with('success', 'Muscle group deleted successfully.');
    }
}
