<script setup>
import AppLogo from '@/Components/AppLogo.vue';
import ThemeToggle from '@/Components/ThemeToggle.vue';
import { Link } from '@inertiajs/vue3';
import { useTheme } from '@/Composables/useTheme.js';
import { onMounted } from 'vue';

const { initTheme } = useTheme();

onMounted(() => {
    initTheme();
});
</script>

<template>
    <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-800 dark:text-zinc-100 flex flex-col justify-center relative overflow-hidden selection:bg-emerald-500 selection:text-white font-sans transition-colors duration-300">
        <!-- Ambient Background Glows (dark only) -->
        <div class="absolute -top-40 -left-40 h-96 w-96 rounded-full bg-emerald-500/10 blur-[128px] pointer-events-none hidden dark:block"></div>
        <div class="absolute -bottom-40 -right-40 h-96 w-96 rounded-full bg-teal-500/10 blur-[128px] pointer-events-none hidden dark:block"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-[500px] w-[500px] rounded-full bg-cyan-500/5 blur-[150px] pointer-events-none hidden dark:block"></div>

        <!-- Subtle Grid Pattern (dark only) -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#27272a15_1px,transparent_1px),linear-gradient(to_bottom,#27272a15_1px,transparent_1px)] bg-[size:32px_32px] pointer-events-none hidden dark:block"></div>

        <!-- Light pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#e4e4e715_1px,transparent_1px),linear-gradient(to_bottom,#e4e4e715_1px,transparent_1px)] bg-[size:32px_32px] pointer-events-none dark:hidden"></div>

        <!-- Theme Toggle (top right) -->
        <div class="absolute top-3.5 right-3.5 sm:top-5 sm:right-6 z-30">
            <ThemeToggle />
        </div>

        <div class="relative z-10 w-full max-w-6xl mx-auto px-3.5 sm:px-6 lg:px-8 py-6 sm:py-10 flex flex-col lg:flex-row items-center justify-center gap-8 lg:gap-16">

            <!-- Left Branding Column (Desktop only) -->
            <div class="hidden lg:flex flex-col max-w-md">
                <Link href="/" class="inline-block mb-8">
                    <AppLogo size="lg" subtitle="STRENGTH & PERFORMANCE" />
                </Link>

                <h1 class="text-4xl font-extrabold text-zinc-900 dark:text-white tracking-tight leading-tight">
                    Track Every Rep.<br />
                    <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400 bg-clip-text text-transparent">
                        Own Your Progress.
                    </span>
                </h1>

                <p class="mt-4 text-zinc-500 dark:text-zinc-400 text-sm leading-relaxed">
                    Log your exercises, calculate progressive overload, and smash your personal records with high precision tracking.
                </p>

                <!-- Feature pills with Font Awesome Pro duotone icons -->
                <div class="mt-8 space-y-3">
                    <div class="flex items-center gap-3.5 p-3 rounded-2xl bg-white/80 dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 backdrop-blur-md shadow-sm dark:shadow-none">
                        <div class="h-9 w-9 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center">
                            <i class="fa-duotone fa-bolt-lightning text-base" style="--fa-primary-color: #059669; --fa-secondary-color: #34d399; --fa-secondary-opacity: 0.85;"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-zinc-800 dark:text-white">Live Set & Rest Timer</div>
                            <div class="text-[11px] text-zinc-500 dark:text-zinc-400">Accurate recovery between sets</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3.5 p-3 rounded-2xl bg-white/80 dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 backdrop-blur-md shadow-sm dark:shadow-none">
                        <div class="h-9 w-9 rounded-xl bg-cyan-500/15 border border-cyan-500/30 flex items-center justify-center">
                            <i class="fa-duotone fa-chart-line-up text-base" style="--fa-primary-color: #0891b2; --fa-secondary-color: #38bdf8; --fa-secondary-opacity: 0.85;"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-zinc-800 dark:text-white">Overload Analytics</div>
                            <div class="text-[11px] text-zinc-500 dark:text-zinc-400">Volume charts, tonnage & PR history</div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-xs text-zinc-400 dark:text-zinc-500 flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    MAS Fitness Tracker
                </div>
            </div>

            <!-- Right Form Card -->
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex justify-center mb-5 mt-2">
                    <Link href="/"><AppLogo size="md" /></Link>
                </div>

                <!-- Card -->
                <div class="rounded-2xl sm:rounded-3xl bg-white dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-800/90 p-5 sm:p-8 shadow-xl dark:shadow-2xl dark:shadow-black/80 backdrop-blur-xl transition-colors duration-300">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
