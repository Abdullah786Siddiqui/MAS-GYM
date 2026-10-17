<?php

use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ExerciseAssignmentController;
use App\Http\Controllers\MuscleGroupController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('setup')->name('setup.')->group(function () {

        // 1) Muscle Group
        Route::resource('muscle-groups', MuscleGroupController::class)
            ->parameters(['muscle-groups' => 'muscleGroup']);

        // 2) Exercise
        Route::resource('exercises', ExerciseController::class);

        // 3) Exercise Assignment
        Route::get('exercise-assignments/manage', [ExerciseAssignmentController::class, 'manage'])
            ->name('exercise-assignments.manage');
        Route::post('exercise-assignments/assign', [ExerciseAssignmentController::class, 'assign'])
            ->name('exercise-assignments.assign');
        Route::post('exercise-assignments/unassign', [ExerciseAssignmentController::class, 'unassign'])
            ->name('exercise-assignments.unassign');
        Route::resource('exercise-assignments', ExerciseAssignmentController::class)
            ->parameters(['exercise-assignments' => 'exerciseAssignment']);
    });

});

require __DIR__.'/auth.php';
