<?php

use Database\Seeders\ExerciseSetupSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Seeds initial real-world muscle groups, exercises, and assignments automatically
     * during 'php artisan migrate' (ideal for Render/production deployment).
     */
    public function up(): void
    {
        // Call ExerciseSetupSeeder to populate initial data
        (new ExerciseSetupSeeder())->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clean rollback if needed
        \App\Models\ExerciseAssignment::query()->forceDelete();
        \App\Models\Exercise::query()->forceDelete();
        \App\Models\MuscleGroup::query()->forceDelete();
    }
};
