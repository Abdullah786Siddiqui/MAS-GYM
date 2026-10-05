<script setup>
import { ref } from 'vue';

const weekData = ref([
    { day: 'Mon', fullDay: 'Monday', volume: 8200, workouts: 1, type: 'Push', intensity: 'High', active: true },
    { day: 'Tue', fullDay: 'Tuesday', volume: 9400, workouts: 1, type: 'Pull', intensity: 'Max', active: true },
    { day: 'Wed', fullDay: 'Wednesday', volume: 0, workouts: 0, type: 'Rest', intensity: 'Rest', active: false },
    { day: 'Thu', fullDay: 'Thursday', volume: 11200, workouts: 1, type: 'Legs', intensity: 'Max', active: true },
    { day: 'Fri', fullDay: 'Friday', volume: 7800, workouts: 1, type: 'Upper', intensity: 'Moderate', active: true },
    { day: 'Sat', fullDay: 'Saturday', volume: 6500, workouts: 1, type: 'Arms & Core', intensity: 'Moderate', active: true },
    { day: 'Sun', fullDay: 'Sunday', volume: 0, workouts: 0, type: 'Rest', intensity: 'Rest', active: false },
]);

const maxVolume = 12000;
const hoveredDay = ref(weekData.value[3]); // Default hover on Thursday
</script>

<template>
    <div class="rounded-3xl bg-zinc-900/90 border border-zinc-800 p-6 shadow-xl shadow-black/50">
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-xs font-semibold tracking-wider uppercase text-zinc-400">Weekly Consistency</p>
                <h3 class="text-xl font-bold text-white tracking-tight mt-0.5">Workout Volume</h3>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-zinc-800 px-3 py-1 text-xs font-semibold text-zinc-300 border border-zinc-700/60">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    5 of 7 Days Active
                </span>
            </div>
        </div>

        <!-- Selected / Hovered Day Callout -->
        <div class="mb-5 rounded-2xl bg-zinc-950/70 border border-zinc-800/80 p-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center font-bold text-emerald-400 text-sm">
                    {{ hoveredDay.day }}
                </div>
                <div>
                    <h5 class="text-xs font-bold text-white">{{ hoveredDay.fullDay }}</h5>
                    <p class="text-[11px] text-zinc-400">
                        {{ hoveredDay.type }} • {{ hoveredDay.intensity }}
                    </p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-xs font-mono font-bold text-emerald-400">
                    {{ hoveredDay.volume.toLocaleString() }} kg
                </span>
                <p class="text-[10px] text-zinc-500 uppercase tracking-wider font-semibold">Tonnage</p>
            </div>
        </div>

        <!-- Bar Visualizer -->
        <div class="grid grid-cols-7 gap-2 sm:gap-3 items-end h-44 pt-6 pb-2 px-1">
            <div 
                v-for="item in weekData" 
                :key="item.day"
                class="flex flex-col items-center h-full justify-end group cursor-pointer"
                @mouseenter="hoveredDay = item"
            >
                <!-- Bar Container -->
                <div class="w-full max-w-[36px] bg-zinc-800/80 rounded-xl relative flex flex-col justify-end overflow-hidden h-32 group-hover:bg-zinc-800 transition-colors">
                    <!-- Filled Bar -->
                    <div 
                        v-if="item.volume > 0"
                        class="w-full rounded-xl transition-all duration-500"
                        :class="hoveredDay.day === item.day ? 'bg-gradient-to-t from-emerald-500 via-teal-400 to-cyan-300 shadow-lg shadow-emerald-500/30' : 'bg-gradient-to-t from-emerald-600 to-teal-500/80 opacity-80 group-hover:opacity-100'"
                        :style="{ height: `${(item.volume / maxVolume) * 100}%` }"
                    ></div>
                    <!-- Rest Day empty indicator -->
                    <div v-else class="h-1.5 w-full bg-zinc-700/60 rounded-full mx-auto self-center mb-1"></div>
                </div>

                <!-- Label -->
                <span 
                    class="mt-2 text-xs font-semibold tracking-wider transition-colors duration-200"
                    :class="hoveredDay.day === item.day ? 'text-emerald-400 font-bold' : item.active ? 'text-zinc-300' : 'text-zinc-500'"
                >
                    {{ item.day }}
                </span>
            </div>
        </div>
    </div>
</template>
