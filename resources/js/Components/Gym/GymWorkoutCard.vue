<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    title: {
        type: String,
        default: 'Push Day (Chest, Shoulders, Triceps)',
    },
    duration: {
        type: String,
        default: '55 mins',
    },
    intensity: {
        type: String,
        default: 'High Intensity',
    },
    date: {
        type: String,
        default: "Today's Routine",
    }
});

const exercises = ref([
    {
        id: 1,
        name: 'Barbell Flat Bench Press',
        targetMuscle: 'Chest',
        sets: [
            { setNumber: 1, weight: 80, reps: 10, completed: true },
            { setNumber: 2, weight: 85, reps: 8, completed: true },
            { setNumber: 3, weight: 90, reps: 6, completed: false },
        ]
    },
    {
        id: 2,
        name: 'Incline Dumbbell Press',
        targetMuscle: 'Upper Chest',
        sets: [
            { setNumber: 1, weight: 28, reps: 12, completed: false },
            { setNumber: 2, weight: 32, reps: 10, completed: false },
            { setNumber: 3, weight: 32, reps: 8, completed: false },
        ]
    },
    {
        id: 3,
        name: 'Overhead Standing Dumbbell Press',
        targetMuscle: 'Shoulders',
        sets: [
            { setNumber: 1, weight: 20, reps: 12, completed: false },
            { setNumber: 2, weight: 22, reps: 10, completed: false },
        ]
    },
    {
        id: 4,
        name: 'Tricep Rope Pushdowns',
        targetMuscle: 'Triceps',
        sets: [
            { setNumber: 1, weight: 35, reps: 15, completed: false },
            { setNumber: 2, weight: 40, reps: 12, completed: false },
            { setNumber: 3, weight: 45, reps: 10, completed: false },
        ]
    }
]);

const totalSets = computed(() => {
    return exercises.value.reduce((acc, ex) => acc + ex.sets.length, 0);
});

const completedSetsCount = computed(() => {
    return exercises.value.reduce((acc, ex) => {
        return acc + ex.sets.filter(s => s.completed).length;
    }, 0);
});

const progressPercentage = computed(() => {
    if (totalSets.value === 0) return 0;
    return Math.round((completedSetsCount.value / totalSets.value) * 100);
});

const toggleSet = (set) => {
    set.completed = !set.completed;
};

const addSet = (exercise) => {
    const lastSet = exercise.sets[exercise.sets.length - 1];
    exercise.sets.push({
        setNumber: exercise.sets.length + 1,
        weight: lastSet ? lastSet.weight : 20,
        reps: lastSet ? lastSet.reps : 10,
        completed: false,
    });
};
</script>

<template>
    <div class="rounded-3xl bg-zinc-900/90 border border-zinc-800 p-6 shadow-xl shadow-black/50">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-zinc-800/80 pb-5">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-xs font-semibold text-emerald-400 border border-emerald-500/20">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        {{ date }}
                    </span>
                    <span class="text-xs font-medium text-zinc-400">
                        • {{ duration }}
                    </span>
                    <span class="hidden sm:inline-block text-xs font-medium text-amber-400 bg-amber-500/10 border border-amber-500/20 rounded-full px-2 py-0.5">
                        {{ intensity }}
                    </span>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                    {{ title }}
                </h3>
            </div>

            <!-- Progress Badge & Finish Action -->
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <span class="text-xs text-zinc-400 font-medium">Progress</span>
                    <p class="text-sm font-bold text-white tabular-nums">
                        {{ completedSetsCount }} / {{ totalSets }} Sets
                    </p>
                </div>
                <div class="relative h-11 w-11 flex items-center justify-center rounded-xl bg-zinc-800 border border-zinc-700">
                    <span class="text-xs font-extrabold text-emerald-400">
                        {{ progressPercentage }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-zinc-800">
            <div 
                class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-500 ease-out"
                :style="{ width: `${progressPercentage}%` }"
            ></div>
        </div>

        <!-- Exercises List -->
        <div class="mt-6 space-y-4">
            <div 
                v-for="exercise in exercises" 
                :key="exercise.id"
                class="rounded-2xl border border-zinc-800/80 bg-zinc-950/60 p-4 transition-colors hover:border-zinc-700/60"
            >
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-zinc-800 text-xs font-bold text-emerald-400">
                            {{ exercise.id }}
                        </div>
                        <div>
                            <h4 class="text-sm sm:text-base font-bold text-zinc-100">
                                {{ exercise.name }}
                            </h4>
                            <span class="text-xs text-zinc-400">
                                {{ exercise.targetMuscle }}
                            </span>
                        </div>
                    </div>

                    <button 
                        @click="addSet(exercise)"
                        type="button" 
                        class="inline-flex items-center gap-1 rounded-lg border border-zinc-700 bg-zinc-800/80 px-2.5 py-1 text-xs font-semibold text-zinc-300 hover:text-white hover:bg-zinc-700 transition"
                    >
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Set
                    </button>
                </div>

                <!-- Sets Row Table / Grid -->
                <div class="space-y-2">
                    <div 
                        v-for="set in exercise.sets" 
                        :key="set.setNumber"
                        class="flex items-center justify-between rounded-xl px-3 py-2 text-xs transition-colors"
                        :class="set.completed ? 'bg-emerald-500/10 border border-emerald-500/20 text-emerald-300' : 'bg-zinc-900 border border-zinc-800 text-zinc-300'"
                    >
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-zinc-400 w-8">
                                SET {{ set.setNumber }}
                            </span>
                            <div class="flex items-center gap-2">
                                <span class="rounded bg-zinc-800 px-2 py-1 font-mono font-bold text-white">
                                    {{ set.weight }} kg
                                </span>
                                <span class="text-zinc-500">×</span>
                                <span class="rounded bg-zinc-800 px-2 py-1 font-mono font-bold text-white">
                                    {{ set.reps }} reps
                                </span>
                            </div>
                        </div>

                        <!-- Checkbox Button -->
                        <button 
                            type="button"
                            @click="toggleSet(set)"
                            class="flex h-7 w-7 items-center justify-center rounded-lg transition-all duration-200"
                            :class="set.completed ? 'bg-emerald-500 text-zinc-950 font-bold shadow-md shadow-emerald-500/30' : 'border border-zinc-700 bg-zinc-800 text-transparent hover:border-emerald-500/60'"
                            :title="set.completed ? 'Mark incomplete' : 'Mark completed'"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Summary Actions -->
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-zinc-800/80">
            <div class="text-xs text-zinc-400 flex items-center gap-2">
                <svg class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
                Rest timer auto-starts after set completion
            </div>

            <button 
                type="button"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 px-5 py-2.5 text-sm font-bold text-zinc-950 shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all"
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 13l4 4L19 7" />
                </svg>
                Complete Workout
            </button>
        </div>
    </div>
</template>
