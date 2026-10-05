<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    value: {
        type: [String, Number],
        required: true,
    },
    subtitle: {
        type: String,
        default: '',
    },
    change: {
        type: String,
        default: '',
    },
    isPositive: {
        type: Boolean,
        default: true,
    },
    icon: {
        type: String,
        default: 'fire', // fire, dumbbell, trophy, clock, calendar, trending
    },
    gradient: {
        type: String,
        default: 'emerald', // emerald, cyan, violet, amber
    }
});
</script>

<template>
    <div class="relative overflow-hidden rounded-2xl bg-zinc-900/90 border border-zinc-800/90 p-5 shadow-lg shadow-black/40 hover:border-zinc-700/80 transition-all duration-300 group hover:-translate-y-0.5">
        <!-- Top glow bar -->
        <div 
            class="absolute top-0 left-0 right-0 h-1 transition-all duration-300 group-hover:h-1.5"
            :class="{
                'bg-gradient-to-r from-emerald-500 to-teal-400': gradient === 'emerald',
                'bg-gradient-to-r from-cyan-500 to-blue-500': gradient === 'cyan',
                'bg-gradient-to-r from-amber-500 to-orange-500': gradient === 'amber',
                'bg-gradient-to-r from-purple-500 to-pink-500': gradient === 'violet',
            }"
        ></div>

        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold tracking-wider uppercase text-zinc-400">
                    {{ title }}
                </p>
                <h3 class="mt-2 text-2xl lg:text-3xl font-extrabold text-white tracking-tight tabular-nums">
                    {{ value }}
                </h3>
            </div>

            <!-- Icon badge -->
            <div 
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-zinc-700/60 bg-zinc-800/80 text-zinc-200 transition-colors duration-300 group-hover:scale-105"
                :class="{
                    'group-hover:border-emerald-500/50 group-hover:text-emerald-400 group-hover:bg-emerald-500/10': gradient === 'emerald',
                    'group-hover:border-cyan-500/50 group-hover:text-cyan-400 group-hover:bg-cyan-500/10': gradient === 'cyan',
                    'group-hover:border-amber-500/50 group-hover:text-amber-400 group-hover:bg-amber-500/10': gradient === 'amber',
                    'group-hover:border-purple-500/50 group-hover:text-purple-400 group-hover:bg-purple-500/10': gradient === 'violet',
                }"
            >
                <!-- Dumbbell -->
                <svg v-if="icon === 'dumbbell'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 5v14" /><path d="M18 5v14" /><path d="M3 8v8" /><path d="M21 8v8" /><path d="M6 12h12" />
                </svg>

                <!-- Fire / Streak -->
                <svg v-else-if="icon === 'fire'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z" />
                </svg>

                <!-- Trophy -->
                <svg v-else-if="icon === 'trophy'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6" />
                    <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18" />
                    <path d="M4 22h16" />
                    <path d="M10 14.66V17c0 .55-.45 1-1 1H8c-.55 0-1 .45-1 1v1c0 .55.45 1 1 1h8c.55 0 1-.45 1-1v-1c0-.55-.45-1-1-1h-1c-.55 0-1-.45-1-1v-2.34" />
                    <path d="M6 4h12v7a6 6 0 0 1-12 0V4z" />
                </svg>

                <!-- Clock -->
                <svg v-else-if="icon === 'clock'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>

                <!-- Default Calendar / Trending -->
                <svg v-else class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                    <polyline points="16 7 22 7 22 13" />
                </svg>
            </div>
        </div>

        <!-- Footer / Trend -->
        <div v-if="change || subtitle" class="mt-4 flex items-center gap-2 text-xs">
            <span 
                v-if="change" 
                class="inline-flex items-center gap-1 font-semibold rounded-md px-1.5 py-0.5"
                :class="isPositive ? 'bg-emerald-500/15 text-emerald-400' : 'bg-rose-500/15 text-rose-400'"
            >
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline :points="isPositive ? '18 15 12 9 6 15' : '6 9 12 15 18 9'" />
                </svg>
                {{ change }}
            </span>
            <span class="text-zinc-400 truncate">
                {{ subtitle }}
            </span>
        </div>
    </div>
</template>
