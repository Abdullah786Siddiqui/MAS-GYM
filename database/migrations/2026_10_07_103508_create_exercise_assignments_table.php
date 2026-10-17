<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exercise_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
            $table->foreignId('muscle_group_id')->constrained('muscle_groups')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('record_deleted')->default(false);
            $table->timestamps();

            $table->unique(['exercise_id', 'muscle_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_assignments');
    }
};
