<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTable from '@/Components/DataTable.vue';
import { useTableFilter } from '@/Composables/useTableFilter.js';

const props = defineProps({
    overview: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ search: '' }),
    },
    stats: {
        type: Object,
        default: () => ({ total_assignments: 0, covered_muscle_groups: 0, total_muscle_groups: 0 }),
    },
});

// Realtime Debounced Search State Management
const {
    filters: filterState,
    isSearching,
    onSearchInput,
    clearFilter,
    resetAllFilters,
} = useTableFilter('setup.exercise-assignments.index', {
    search: props.filters.search || '',
});

// Table columns definition with balanced percentages
const columns = [
    { key: 'name', label: 'Muscle Group', align: 'left', width: '28%' },
    { key: 'exercises', label: 'Assigned Exercises', align: 'left', width: '46%' },
    { key: 'exercises_count', label: 'Total Assigned', align: 'center', width: '12%' },
    { key: 'actions', label: 'Actions', align: 'right', width: '14%' },
];

// Equipment badge style helper
const getEquipmentBadge = (eq) => {
    switch (eq?.toLowerCase()) {
        case 'barbell':
            return {
                label: 'Barbell',
                class: 'bg-teal-500/10 text-teal-600 dark:text-teal-400 border-teal-500/20',
            };
        case 'dumbbell':
            return {
                label: 'Dumbbell',
                class: 'bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border-cyan-500/20',
            };
        case 'cable':
            return {
                label: 'Cable',
                class: 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
            };
        case 'machine':
            return {
                label: 'Machine',
                class: 'bg-violet-500/10 text-violet-600 dark:text-violet-400 border-violet-500/20',
            };
        case 'bodyweight':
            return {
                label: 'Bodyweight',
                class: 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
            };
        default:
            return {
                label: eq || 'Free',
                class: 'bg-zinc-500/10 text-zinc-500 dark:text-zinc-400 border-zinc-500/20',
            };
    }
};
</script>

<template>
    <Head title="Exercise Assignments" />

    <AuthenticatedLayout>
        <!-- Top App Bar / Header -->
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 sm:h-11 sm:w-11 items-center justify-center rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 shadow-sm shrink-0">
                        <i class="fa-duotone fa-diagram-project text-lg sm:text-xl"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h1 class="text-base sm:text-xl font-extrabold text-zinc-900 dark:text-white tracking-tight truncate">
                                Exercise Assignments
                            </h1>
                            <span class="rounded-full bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 text-[10px] sm:text-[11px] font-bold text-emerald-600 dark:text-emerald-400 shrink-0">
                                Anatomy Mapping
                            </span>
                        </div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate hidden xs:block">
                            Configure movements assigned to muscle anatomy groups
                        </p>
                    </div>
                </div>

                <!-- Primary Action: Go to Drag & Drop Assignment Workspace -->
                <Link
                    :href="route('setup.exercise-assignments.manage')"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-3 sm:py-2.5 text-xs sm:text-sm font-bold text-white shadow-md shadow-emerald-500/20 hover:from-emerald-600 hover:to-teal-700 transition active:scale-[0.98] focus:outline-none"
                >
                    <i class="fa-duotone fa-arrow-right-arrow-left text-sm"></i>
                    <span>Assignment Workspace</span>
                </Link>
            </div>
        </template>

        <div class="py-4 sm:py-8">
            <div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

                <!-- Reusable Mobile-Optimized DataTable -->
                <DataTable
                    :columns="columns"
                    :items="overview.data"
                    :pagination="overview"
                    :loading="isSearching"
                    empty-title="No muscle groups or assignments found"
                    :empty-subtitle="filterState.search ? 'Try adjusting your search criteria.' : 'Open the Assignment Workspace to map exercises to muscle groups.'"
                    empty-icon="fa-duotone fa-diagram-project"
                >
                    <!-- Live Search Toolbar -->
                    <template #toolbar>
                        <div class="flex items-center gap-2.5">
                            <!-- Live Search Input -->
                            <div class="relative flex-1">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                                    <i v-if="!isSearching" class="fa-duotone fa-magnifying-glass text-xs"></i>
                                    <i v-else class="fa-duotone fa-spinner-third fa-spin text-xs text-emerald-500"></i>
                                </span>
                                <input
                                    v-model="filterState.search"
                                    @input="onSearchInput"
                                    type="text"
                                    placeholder="Search by muscle group name or assigned exercise..."
                                    class="w-full pl-9 pr-9 py-2.5 sm:py-2 text-xs sm:text-sm rounded-xl border border-zinc-200 dark:border-zinc-700/80 bg-zinc-50/80 dark:bg-zinc-800/60 text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:border-emerald-500 focus:bg-white dark:focus:bg-zinc-800 focus:ring-2 focus:ring-emerald-500/20 transition-all"
                                />
                                <button
                                    v-if="filterState.search"
                                    @click="clearFilter('search')"
                                    type="button"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200"
                                    title="Clear search"
                                >
                                    <i class="fa-duotone fa-circle-xmark text-xs sm:text-sm"></i>
                                </button>
                            </div>

                            <!-- Reset Button if search active -->
                            <button
                                v-if="filterState.search"
                                @click="resetAllFilters"
                                type="button"
                                class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 px-3.5 py-2.5 sm:py-2 text-xs font-bold text-zinc-700 dark:text-zinc-200 hover:bg-zinc-200/80 dark:hover:bg-zinc-700 transition shrink-0"
                                title="Reset search"
                            >
                                <i class="fa-duotone fa-arrow-rotate-left text-xs"></i>
                                <span class="hidden sm:inline">Reset</span>
                            </button>
                        </div>
                    </template>

                    <!-- TABLE CELLS -->
                    <template #cell-name="{ item }">
                        <div class="flex items-center gap-3 min-w-0 pr-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500/10 to-teal-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                <i class="fa-duotone fa-person-running text-base"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-zinc-900 dark:text-white truncate">
                                    {{ item.name }}
                                </div>
                                <div class="text-[11px] text-zinc-400 truncate">
                                    {{ item.description || 'Target anatomy category' }}
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Assigned Exercises Chips -->
                    <template #cell-exercises="{ item }">
                        <div class="flex flex-wrap items-center gap-1.5 pr-2">
                            <template v-if="item.exercises && item.exercises.length > 0">
                                <span
                                    v-for="ex in item.exercises.slice(0, 4)"
                                    :key="ex.id"
                                    class="inline-flex items-center gap-1 rounded-lg border px-2 py-0.5 text-[11px] font-bold whitespace-nowrap"
                                    :class="getEquipmentBadge(ex.equipment).class"
                                >
                                    <span class="truncate max-w-[120px]">{{ ex.name }}</span>
                                </span>
                                <span
                                    v-if="item.exercises.length > 4"
                                    class="inline-flex items-center rounded-lg bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 px-1.5 py-0.5 text-[10px] font-extrabold text-zinc-600 dark:text-zinc-400"
                                >
                                    +{{ item.exercises.length - 4 }} more
                                </span>
                            </template>
                            <span v-else class="text-zinc-400 text-xs italic">
                                No exercises assigned yet
                            </span>
                        </div>
                    </template>

                    <!-- Total Count -->
                    <template #cell-exercises_count="{ item }">
                        <div class="flex items-center justify-center">
                            <span
                                class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-bold whitespace-nowrap"
                                :class="item.exercises_count > 0
                                    ? 'bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400'
                                    : 'bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-400'"
                            >
                                <i class="fa-duotone fa-dumbbell text-[10px]"></i>
                                {{ item.exercises_count ?? 0 }}
                            </span>
                        </div>
                    </template>

                    <!-- Actions: Manage in Workspace Button -->
                    <template #cell-actions="{ item }">
                        <div class="flex items-center justify-end">
                            <Link
                                :href="route('setup.exercise-assignments.manage', { muscle_group_id: item.id })"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-emerald-500/20 bg-emerald-50/50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500 hover:text-white transition-all text-xs font-bold shadow-xs active:scale-95"
                                title="Open Assignment Workspace for this muscle group"
                            >
                                <i class="fa-duotone fa-arrow-right-arrow-left text-xs"></i>
                                <span>Manage</span>
                            </Link>
                        </div>
                    </template>
                </DataTable>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
