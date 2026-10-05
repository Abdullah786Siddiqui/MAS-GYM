<script setup>
import { ref, watch, onMounted } from 'vue';
import AppLogo from '@/Components/AppLogo.vue';
import ThemeToggle from '@/Components/ThemeToggle.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { useTheme } from '@/Composables/useTheme.js';

const { initTheme } = useTheme();
const showingNavigationDropdown = ref(false);

onMounted(() => {
    initTheme();
});

watch(showingNavigationDropdown, (val) => {
    document.body.style.overflow = val ? 'hidden' : '';
});
</script>

<template>
    <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-800 dark:text-zinc-100 font-sans selection:bg-emerald-500 selection:text-white transition-colors duration-300">
        <!-- Ambient glow (dark only) -->
        <div class="fixed top-0 left-1/2 -translate-x-1/2 h-96 w-96 rounded-full bg-emerald-500/5 blur-[150px] pointer-events-none hidden dark:block"></div>

        <!-- Top Navigation Bar -->
        <nav class="sticky top-0 z-40 border-b border-zinc-200 dark:border-zinc-800/80 bg-white/90 dark:bg-zinc-950/90 backdrop-blur-xl transition-colors duration-300">
            <div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">

                    <div class="flex items-center gap-6 sm:gap-8">
                        <!-- Logo -->
                        <Link :href="route('dashboard')" class="flex shrink-0 items-center">
                            <AppLogo size="sm" />
                        </Link>

                        <!-- Desktop Nav Links -->
                        <div class="hidden sm:flex items-center space-x-1">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                              Dashboard
                            </NavLink>
                        </div>
                    </div>

                    <!-- Right: Theme Toggle + Profile Dropdown -->
                    <div class="hidden sm:flex items-center gap-3">
                        <!-- Theme Toggle -->
                        <ThemeToggle />

                        <!-- Profile Dropdown -->
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    type="button"
                                    class="inline-flex items-center gap-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-100/80 dark:bg-zinc-900/90 px-3 py-1.5 text-sm font-semibold text-zinc-700 dark:text-zinc-200 hover:border-zinc-300 dark:hover:border-zinc-700 hover:bg-zinc-200/60 dark:hover:bg-zinc-800 focus:outline-none transition shadow-sm"
                                >
                                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-500 to-teal-500 text-xs font-black text-white">
                                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <span class="max-w-[120px] truncate text-xs font-bold">
                                        {{ $page.props.auth.user.name }}
                                    </span>
                                    <i class="fa-duotone fa-chevron-down text-zinc-400 text-xs"></i>
                                </button>
                            </template>

                            <template #content>
                                <div class="p-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-2xl">
                                    <div class="px-3 py-2 border-b border-zinc-100 dark:border-zinc-800 mb-1">
                                        <div class="text-xs font-bold text-zinc-800 dark:text-white truncate">{{ $page.props.auth.user.name }}</div>
                                        <div class="text-[11px] text-zinc-500 dark:text-zinc-400 truncate">{{ $page.props.auth.user.email }}</div>
                                    </div>
                                    <DropdownLink :href="route('profile.edit')">
                                        <i class="fa-duotone fa-circle-user me-2 text-emerald-500"></i> Profile
                                    </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        <i class="fa-duotone fa-arrow-right-from-bracket me-2 text-rose-500"></i>
                                        <span class="text-rose-500 font-semibold">Log Out</span>
                                    </DropdownLink>
                                </div>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Mobile: Theme Toggle + Animated Hamburger -->
                    <div class="flex items-center gap-1.5 sm:hidden">
                        <ThemeToggle />

                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            type="button"
                            aria-label="Toggle Navigation Menu"
                            class="relative inline-flex items-center justify-center w-10 h-10 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white focus:outline-none transition active:scale-95 shadow-sm"
                        >
                            <div class="relative w-4 h-3.5 flex flex-col justify-between">
                                <span class="h-0.5 bg-current rounded-full transition-all duration-300 ease-in-out"
                                      :class="showingNavigationDropdown ? 'rotate-45 translate-y-[5px]' : ''"></span>
                                <span class="h-0.5 bg-current rounded-full transition-all duration-200 ease-in-out"
                                      :class="showingNavigationDropdown ? 'opacity-0 scale-0' : 'opacity-100'"></span>
                                <span class="h-0.5 bg-current rounded-full transition-all duration-300 ease-in-out"
                                      :class="showingNavigationDropdown ? '-rotate-45 -translate-y-[7px]' : ''"></span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Sidebar Backdrop -->
        <Transition
            enter-active-class="transition-opacity duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="showingNavigationDropdown"
                class="fixed inset-0 bg-black/60 dark:bg-black/75 backdrop-blur-sm z-50 sm:hidden"
                @click="showingNavigationDropdown = false"
            ></div>
        </Transition>

        <!-- Mobile Slide-out Drawer -->
        <div
            class="fixed top-0 right-0 bottom-0 w-[82%] max-w-xs bg-white dark:bg-zinc-950 border-l border-zinc-200 dark:border-zinc-800 shadow-2xl z-50 sm:hidden flex flex-col transition-transform duration-300 ease-out transform"
            :class="showingNavigationDropdown ? 'translate-x-0' : 'translate-x-full'"
        >
            <!-- Drawer Header -->
            <div class="flex items-center justify-between px-4 h-16 border-b border-zinc-200 dark:border-zinc-800 shrink-0">
                <AppLogo size="sm" />
                <button
                    @click="showingNavigationDropdown = false"
                    type="button"
                    class="p-2 text-zinc-500 hover:text-zinc-900 dark:hover:text-white rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-800/80 transition"
                    aria-label="Close Menu"
                >
                    <i class="fa-duotone fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Drawer Nav Links -->
            <div class="px-4 py-5 space-y-1.5 flex-1 overflow-y-auto">
                <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 mb-2">Navigation</p>

                <ResponsiveNavLink
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"
                    @click="showingNavigationDropdown = false"
                >
                    <i class="fa-duotone fa-gauge-high w-6 text-emerald-500 text-base"></i>
                    <span>Dashboard</span>
                </ResponsiveNavLink>
            </div>

            <!-- Drawer User Footer -->
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-800/90 bg-zinc-50 dark:bg-zinc-900/40 shrink-0">
                <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 text-sm font-black text-white shadow-md shadow-emerald-500/25">
                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-bold text-zinc-800 dark:text-white truncate">{{ $page.props.auth.user.name }}</div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ $page.props.auth.user.email }}</div>
                    </div>
                </div>

                <div class="space-y-1">
                    <ResponsiveNavLink
                        :href="route('profile.edit')"
                        :active="route().current('profile.edit')"
                        @click="showingNavigationDropdown = false"
                    >
                        <i class="fa-duotone fa-user-gear w-6 text-zinc-400 text-base"></i>
                        <span>Profile Settings</span>
                    </ResponsiveNavLink>
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        @click="showingNavigationDropdown = false"
                        class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-all duration-200"
                    >
                        <i class="fa-duotone fa-arrow-right-from-bracket w-6 text-rose-500 text-base"></i>
                        <span>Log Out</span>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Page Header Slot -->
        <header v-if="$slots.header" class="border-b border-zinc-200 dark:border-zinc-800/80 bg-white/60 dark:bg-zinc-900/40 transition-colors duration-300">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <main class="relative z-10">
            <slot />
        </main>
    </div>
</template>
