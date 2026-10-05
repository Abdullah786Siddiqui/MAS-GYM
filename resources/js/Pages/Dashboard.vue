<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-6 sm:py-10">
            <div class="mx-auto max-w-4xl px-3.5 sm:px-6 lg:px-8 space-y-5 sm:space-y-6">

                <!-- Welcome Card -->
                <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 sm:p-7 shadow-sm dark:shadow-xl transition-all duration-300">
                    <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none"></div>

                    <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <div class="inline-flex items-center gap-2 rounded-full bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 mb-2.5">
                                <i class="fa-duotone fa-sparkles text-xs" style="--fa-primary-color: #10b981; --fa-secondary-color: #6ee7b7; --fa-secondary-opacity: 0.8;"></i>
                                <span>Active Session</span>
                            </div>
                            <h1 class="text-xl sm:text-2xl font-extrabold text-zinc-900 dark:text-white tracking-tight">
                                Welcome, {{ $page.props.auth.user.name }}!
                            </h1>
                            <p class="mt-1 text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">
                                You're successfully logged in to your MAS Fitness dashboard.
                            </p>
                        </div>

                        <!-- Quick Action Button (neutral/subtle icon) -->
                        <div class="flex items-center gap-2 pt-2 sm:pt-0">
                            <Link
                                :href="route('profile.edit')"
                                class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-zinc-100 dark:bg-zinc-800/90 border border-zinc-200 dark:border-zinc-700/80 px-4 py-2.5 text-xs sm:text-sm font-bold text-zinc-700 dark:text-zinc-200 hover:bg-zinc-200/70 dark:hover:bg-zinc-700 transition active:scale-95 shadow-sm"
                            >
                                <i class="fa-duotone fa-user-gear text-zinc-500 dark:text-zinc-400"></i>
                                <span>Settings</span>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid (Colorful duotone icons on highlight cards only) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 sm:gap-4">
                    <!-- Account Card -->
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4 sm:p-5 shadow-sm dark:shadow-none transition-colors duration-300 flex items-start gap-3.5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                            <i class="fa-duotone fa-circle-user text-xl" style="--fa-primary-color: #10b981; --fa-secondary-color: #34d399; --fa-secondary-opacity: 0.6;"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Account</p>
                            <p class="mt-1 text-sm sm:text-base font-bold text-zinc-800 dark:text-white truncate">{{ $page.props.auth.user.name }}</p>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 truncate">{{ $page.props.auth.user.email }}</p>
                        </div>
                    </div>

                    <!-- Status Card -->
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4 sm:p-5 shadow-sm dark:shadow-none transition-colors duration-300 flex items-start gap-3.5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-teal-500/10 border border-teal-500/20">
                            <i class="fa-duotone fa-badge-check text-xl" style="--fa-primary-color: #0d9488; --fa-secondary-color: #2dd4bf; --fa-secondary-opacity: 0.7;"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Membership</p>
                            <div class="mt-1 inline-flex items-center gap-1.5">
                                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-sm sm:text-base font-bold text-emerald-600 dark:text-emerald-400">Active Member</span>
                            </div>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500">Full Access</p>
                        </div>
                    </div>

                    <!-- Platform Card -->
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4 sm:p-5 shadow-sm dark:shadow-none transition-colors duration-300 flex items-start gap-3.5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-cyan-500/10 border border-cyan-500/20">
                            <i class="fa-duotone fa-dumbbell text-xl" style="--fa-primary-color: #0284c7; --fa-secondary-color: #38bdf8; --fa-secondary-opacity: 0.65;"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Platform</p>
                            <p class="mt-1 text-sm sm:text-base font-bold text-zinc-800 dark:text-white">MAS Fitness</p>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500">Tracker v1.0</p>
                        </div>
                    </div>
                </div>

                <!-- Fitness Quick Metrics with vibrant colorful duotone icons -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5 sm:gap-4">
                    <!-- Weekly Streak (Warm Fire Colors) -->
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4 shadow-sm dark:shadow-none">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Weekly Streak</span>
                            <i class="fa-duotone fa-fire-flame-curved text-xl" style="--fa-primary-color: #ea580c; --fa-secondary-color: #eab308; --fa-secondary-opacity: 0.85;"></i>
                        </div>
                        <p class="mt-2 text-xl sm:text-2xl font-black text-zinc-900 dark:text-white">5 Days</p>
                        <p class="text-[11px] text-emerald-500 font-semibold mt-0.5">Top 10% consistency</p>
                    </div>

                    <!-- Workouts (Emerald/Mint Calendar) -->
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4 shadow-sm dark:shadow-none">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Total Workouts</span>
                            <i class="fa-duotone fa-calendar-star text-xl" style="--fa-primary-color: #059669; --fa-secondary-color: #34d399; --fa-secondary-opacity: 0.75;"></i>
                        </div>
                        <p class="mt-2 text-xl sm:text-2xl font-black text-zinc-900 dark:text-white">24</p>
                        <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-0.5">This month</p>
                    </div>

                    <!-- Performance (Cyan/Blue Overload Chart) -->
                    <div class="col-span-2 sm:col-span-1 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4 shadow-sm dark:shadow-none">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Performance</span>
                            <i class="fa-duotone fa-chart-line-up text-xl" style="--fa-primary-color: #0891b2; --fa-secondary-color: #38bdf8; --fa-secondary-opacity: 0.85;"></i>
                        </div>
                        <p class="mt-2 text-xl sm:text-2xl font-black text-zinc-900 dark:text-white">+12.5%</p>
                        <p class="text-[11px] text-emerald-500 font-semibold mt-0.5">Volume overload</p>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
