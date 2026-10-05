<script setup>
const personalRecords = [
    { exercise: 'Barbell Flat Bench', weight: 110, reps: 1, unit: 'kg', date: '3 days ago', change: '+5 kg', icon: 'chest' },
    { exercise: 'Barbell Back Squat', weight: 155, reps: 2, unit: 'kg', date: 'Last week', change: '+10 kg', icon: 'legs' },
    { exercise: 'Conventional Deadlift', weight: 190, reps: 1, unit: 'kg', date: '2 weeks ago', change: '+7.5 kg', icon: 'back' },
    { exercise: 'Standing Overhead Press', weight: 72.5, reps: 3, unit: 'kg', date: 'This month', change: '+2.5 kg', icon: 'shoulders' },
];
</script>

<template>
    <div class="rounded-3xl bg-zinc-900/90 border border-zinc-800 p-6 shadow-xl shadow-black/50">
        <div class="flex items-center justify-between mb-5">
            <div>
                <p class="text-xs font-semibold tracking-wider uppercase text-zinc-400">Milestones</p>
                <h3 class="text-xl font-bold text-white tracking-tight mt-0.5">Personal Records (PRs)</h3>
            </div>
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-400">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6" />
                    <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18" />
                    <path d="M4 22h16" />
                    <path d="M6 4h12v7a6 6 0 0 1-12 0V4z" />
                </svg>
            </div>
        </div>

        <div class="space-y-3">
            <div 
                v-for="pr in personalRecords" 
                :key="pr.exercise"
                class="flex items-center justify-between rounded-2xl border border-zinc-800/80 bg-zinc-950/70 p-3.5 hover:border-zinc-700 transition-all duration-200"
            >
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-zinc-800 to-zinc-900 border border-zinc-700/60 text-zinc-300">
                        <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 5v14" /><path d="M18 5v14" /><path d="M3 8v8" /><path d="M21 8v8" /><path d="M6 12h12" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-zinc-100">{{ pr.exercise }}</h4>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-[11px] text-zinc-400">{{ pr.date }}</span>
                            <span class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 px-1.5 py-0.2 rounded border border-emerald-500/20">
                                {{ pr.change }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <div class="flex items-baseline justify-end gap-1">
                        <span class="text-lg font-black text-white font-mono tracking-tight">{{ pr.weight }}</span>
                        <span class="text-xs text-zinc-400 font-semibold">{{ pr.unit }}</span>
                    </div>
                    <span class="text-[10px] text-zinc-400 font-medium">for {{ pr.reps }} rep</span>
                </div>
            </div>
        </div>
    </div>
</template>
