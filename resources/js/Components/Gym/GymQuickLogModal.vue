<script setup>
import { ref } from 'vue';

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    }
});

const emit = defineEmits(['close', 'saved']);

const exerciseName = ref('Barbell Deadlift');
const weight = ref(100);
const reps = ref(8);
const rpe = ref(8);
const notes = ref('');

const close = () => {
    emit('close');
};

const save = () => {
    // UI feedback only
    emit('saved', {
        exercise: exerciseName.value,
        weight: weight.value,
        reps: reps.value,
        rpe: rpe.value,
    });
    emit('close');
};
</script>

<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" @click="close"></div>

        <!-- Modal Box -->
        <div class="relative w-full max-w-lg rounded-3xl bg-zinc-900 border border-zinc-800 p-6 sm:p-8 shadow-2xl shadow-emerald-950/40 z-10 animate-in fade-in zoom-in-95 duration-200">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Log Workout Set</h3>
                        <p class="text-xs text-zinc-400">Record your latest set performance</p>
                    </div>
                </div>

                <button 
                    @click="close"
                    class="rounded-xl p-2 text-zinc-400 hover:bg-zinc-800 hover:text-white transition"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>

            <!-- Form Body -->
            <div class="mt-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                        Exercise Name
                    </label>
                    <input 
                        type="text" 
                        v-model="exerciseName"
                        class="w-full rounded-xl border border-zinc-700 bg-zinc-950 px-4 py-3 text-sm font-medium text-white placeholder-zinc-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                        placeholder="e.g. Barbell Bench Press"
                    />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                            Weight (kg)
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                v-model="weight"
                                class="w-full rounded-xl border border-zinc-700 bg-zinc-950 px-4 py-3 text-sm font-bold text-white font-mono focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                            />
                            <div class="absolute right-3 top-3 text-xs font-semibold text-zinc-500">KG</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                            Reps
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                v-model="reps"
                                class="w-full rounded-xl border border-zinc-700 bg-zinc-950 px-4 py-3 text-sm font-bold text-white font-mono focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                            />
                            <div class="absolute right-3 top-3 text-xs font-semibold text-zinc-500">REPS</div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="text-xs font-semibold uppercase tracking-wider text-zinc-400">
                            Rate of Perceived Exertion (RPE)
                        </label>
                        <span class="text-xs font-mono font-bold text-emerald-400">
                            {{ rpe }} / 10
                        </span>
                    </div>
                    <input 
                        type="range" 
                        min="1" 
                        max="10" 
                        v-model="rpe"
                        class="w-full h-2 bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-emerald-400"
                    />
                    <div class="flex justify-between text-[10px] text-zinc-500 mt-1">
                        <span>Light (1-5)</span>
                        <span>Moderate (6-8)</span>
                        <span>To Failure (9-10)</span>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="mt-8 flex items-center justify-end gap-3 pt-4 border-t border-zinc-800">
                <button 
                    type="button" 
                    @click="close"
                    class="rounded-xl border border-zinc-700 bg-zinc-800/80 px-4 py-2.5 text-sm font-semibold text-zinc-300 hover:bg-zinc-800 hover:text-white transition"
                >
                    Cancel
                </button>
                <button 
                    type="button" 
                    @click="save"
                    class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 px-6 py-2.5 text-sm font-bold text-zinc-950 shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all"
                >
                    Save Set
                </button>
            </div>
        </div>
    </div>
</template>
