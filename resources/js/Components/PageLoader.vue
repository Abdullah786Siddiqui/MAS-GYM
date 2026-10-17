<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';

const isLoading = ref(false);
let startTimer = null;

// Listen to Inertia page navigation events
const handleStart = () => {
    // Small 60ms debounce so instantaneous local clicks don't flash
    startTimer = setTimeout(() => {
        isLoading.value = true;
    }, 60);
};

const handleFinish = () => {
    if (startTimer) clearTimeout(startTimer);
    isLoading.value = false;
};

onMounted(() => {
    const removeStartListener = router.on('start', handleStart);
    const removeFinishListener = router.on('finish', handleFinish);

    onUnmounted(() => {
        removeStartListener();
        removeFinishListener();
    });
});
</script>

<template>
    <Transition
        enter-active-class="transition-opacity duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-200 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="isLoading"
            class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-zinc-950/65 dark:bg-zinc-950/85 backdrop-blur-md select-none"
        >
            <!-- Ambient Glow -->
            <div class="absolute h-56 w-56 rounded-full bg-emerald-500/20 blur-3xl pointer-events-none animate-pulse"></div>

            <!-- Loader Center Card -->
            <div class="relative flex flex-col items-center justify-center p-6 sm:p-8 rounded-3xl bg-white/95 dark:bg-zinc-900/90 border border-zinc-200/80 dark:border-zinc-800 shadow-2xl shadow-emerald-500/10">
                
                <!-- Animated Dumbbell Vector Container -->
                <div class="relative flex items-center justify-center w-24 h-24 mb-4">
                    <!-- Spinning / Pulsing Glow Ring -->
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-tr from-emerald-500 via-teal-500 to-cyan-500 opacity-20 blur-md animate-spin-slow"></div>
                    
                    <!-- Emblem Box -->
                    <div class="relative flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-emerald-500 via-teal-500 to-emerald-600 text-white shadow-xl shadow-emerald-500/30">
                        
                        <!-- High Definition SVG Dumbbell Vector with Pumping Animation -->
                        <svg
                            viewBox="0 0 64 64"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-12 h-12 text-white drop-shadow-md dumbbell-animate"
                        >
                            <!-- Left Outer Weight Plate -->
                            <rect x="8" y="16" width="6" height="32" rx="3" fill="currentColor" />
                            <!-- Left Inner Weight Plate -->
                            <rect x="15" y="20" width="5" height="24" rx="2.5" fill="currentColor" fill-opacity="0.9" />
                            <!-- Left Collar / Lock -->
                            <rect x="20" y="27" width="3" height="10" rx="1" fill="#ecfdf5" />

                            <!-- Main Knurled Barbell Center Grip -->
                            <rect x="23" y="29.5" width="18" height="5" rx="1.5" fill="#f8fafc" />
                            <!-- Knurling texture lines on grip -->
                            <line x1="28" y1="30" x2="28" y2="34" stroke="#059669" stroke-width="1.2" stroke-linecap="round" />
                            <line x1="32" y1="30" x2="32" y2="34" stroke="#059669" stroke-width="1.2" stroke-linecap="round" />
                            <line x1="36" y1="30" x2="36" y2="34" stroke="#059669" stroke-width="1.2" stroke-linecap="round" />

                            <!-- Right Collar / Lock -->
                            <rect x="41" y="27" width="3" height="10" rx="1" fill="#ecfdf5" />
                            <!-- Right Inner Weight Plate -->
                            <rect x="44" y="20" width="5" height="24" rx="2.5" fill="currentColor" fill-opacity="0.9" />
                            <!-- Right Outer Weight Plate -->
                            <rect x="50" y="16" width="6" height="32" rx="3" fill="currentColor" />
                        </svg>
                    </div>
                </div>

                <!-- Brand Typography -->
                <div class="text-center">
                    <div class="flex items-center justify-center gap-1 leading-tight">
                        <span class="text-sm font-black tracking-wider text-zinc-900 dark:text-white uppercase">
                            MAS
                        </span>
                        <span class="text-sm font-black tracking-wider text-emerald-500 uppercase">
                            FITNESS
                        </span>
                    </div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mt-1">
                        Loading...
                    </p>
                </div>

                <!-- Sleek Shimmering Progress Bar -->
                <div class="mt-4 w-32 h-1 bg-zinc-200 dark:bg-zinc-800 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-400 to-emerald-500 rounded-full animate-shimmer"></div>
                </div>

            </div>
        </div>
    </Transition>
</template>

<style scoped>
/* Dumbbell Bicep Curl / Pumping Animation */
@keyframes dumbbell-pump {
    0%, 100% {
        transform: rotate(-12deg) scale(0.96) translateY(2px);
    }
    50% {
        transform: rotate(12deg) scale(1.08) translateY(-3px);
    }
}

.dumbbell-animate {
    animation: dumbbell-pump 1.1s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    transform-origin: center center;
}

/* Slow Ambient Glow Rotation */
@keyframes spin-slow {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin-slow {
    animation: spin-slow 6s linear infinite;
}

/* Shimmer Progress bar animation */
@keyframes shimmer {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(100%);
    }
}

.animate-shimmer {
    animation: shimmer 1.2s ease-in-out infinite;
    width: 60%;
}
</style>
