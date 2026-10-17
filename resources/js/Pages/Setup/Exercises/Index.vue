<script setup>
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatsCards from '@/Components/StatsCards.vue';
import DataTable from '@/Components/DataTable.vue';
import SearchableSelect from '@/Components/SearchableSelect.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { useTableFilter } from '@/Composables/useTableFilter.js';

const props = defineProps({
    exercises: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ search: '', equipment: '', muscle_group_id: '' }),
    },
    equipmentOptions: {
        type: Array,
        default: () => ['barbell', 'dumbbell', 'cable', 'machine', 'bodyweight'],
    },
    muscleGroups: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({ total: 0, active: 0, inactive: 0 }),
    },
});

// Realtime Debounced Search & Filter State Management
const {
    filters: filterState,
    isSearching,
    onSearchInput,
    onFilterChange,
    clearFilter,
    resetAllFilters,
} = useTableFilter('setup.exercises.index', {
    search: props.filters.search || '',
    equipment: props.filters.equipment || '',
    muscle_group_id: props.filters.muscle_group_id || '',
});

// Format options for SearchableSelect
const equipmentSelectOptions = computed(() => [
    { value: 'barbell', label: 'Barbell', icon: 'fa-duotone fa-dumbbell' },
    { value: 'dumbbell', label: 'Dumbbell', icon: 'fa-duotone fa-dumbbell' },
    { value: 'cable', label: 'Cable', icon: 'fa-duotone fa-arrows-up-down' },
    { value: 'machine', label: 'Machine', icon: 'fa-duotone fa-gear' },
    { value: 'bodyweight', label: 'Bodyweight', icon: 'fa-duotone fa-person-simple' },
]);

const muscleGroupSelectOptions = computed(() => {
    return (props.muscleGroups || []).map((mg) => ({
        value: mg.id,
        label: mg.name,
        icon: 'fa-duotone fa-person-running',
    }));
});

// Stats cards configuration
const statCards = computed(() => [
    {
        title: 'Total Exercises',
        value: props.stats.total,
        icon: 'fa-duotone fa-dumbbell',
        color: 'zinc',
        subtitle: 'Movement library entries',
    },
    {
        title: 'Active Exercises',
        value: props.stats.active,
        icon: 'fa-duotone fa-circle-check',
        color: 'cyan',
        pulse: true,
        subtitle: 'Available for routines',
    },
    {
        title: 'Inactive Exercises',
        value: props.stats.inactive,
        icon: 'fa-duotone fa-pause',
        color: 'amber',
        subtitle: 'Hidden from workout builders',
    },
]);

// Table Columns definition with balanced percentage widths
const columns = [
    { key: 'name', label: 'Exercise', align: 'left', width: '45%' },
    { key: 'equipment', label: 'Equipment', align: 'left', width: '20%' },
    { key: 'video_url', label: 'Demo', align: 'center', width: '10%' },
    { key: 'is_active', label: 'Status', align: 'center', width: '10%' },
    { key: 'actions', label: 'Actions', align: 'right', width: '15%' },
];

// Collapsible Filter Panel State
const isFilterOpen = ref(false);
const activeFilterCount = computed(() => {
    let count = 0;
    if (filterState.equipment) count++;
    return count;
});

// Modal State Management
const isFormModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const isEditing = ref(false);
const selectedExercise = ref(null);

// Form instance (maxlength 50 for name)
const form = useForm({
    id: null,
    name: '',
    description: '',
    equipment: '',
    video_url: '',
    is_active: true,
});

// Open Create Modal
const openCreateModal = () => {
    isEditing.value = false;
    selectedExercise.value = null;
    form.reset();
    form.clearErrors();
    form.is_active = true;
    isFormModalOpen.value = true;
};

// Open Edit Modal
const openEditModal = (exercise) => {
    isEditing.value = true;
    selectedExercise.value = exercise;
    form.clearErrors();
    form.id = exercise.id;
    form.name = exercise.name;
    form.description = exercise.description || '';
    form.equipment = exercise.equipment || '';
    form.video_url = exercise.video_url || '';
    form.is_active = Boolean(exercise.is_active);
    isFormModalOpen.value = true;
};

// Close Form Modal
const closeFormModal = () => {
    isFormModalOpen.value = false;
    form.reset();
    form.clearErrors();
};

// Submit Create or Update
const submitForm = () => {
    if (isEditing.value && selectedExercise.value) {
        form.put(route('setup.exercises.update', selectedExercise.value.id), {
            preserveScroll: true,
            onSuccess: () => closeFormModal(),
        });
    } else {
        form.post(route('setup.exercises.store'), {
            preserveScroll: true,
            onSuccess: () => closeFormModal(),
        });
    }
};

// Open Delete Confirmation
const confirmDelete = (exercise) => {
    selectedExercise.value = exercise;
    isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    selectedExercise.value = null;
};

// Execute Soft Deletion
const deleteForm = useForm({});
const executeDelete = () => {
    if (!selectedExercise.value) return;

    deleteForm.delete(route('setup.exercises.destroy', selectedExercise.value.id), {
        preserveScroll: true,
        onSuccess: () => closeDeleteModal(),
    });
};

// Equipment Badge Styles helper
const getEquipmentBadge = (eq) => {
    switch (eq?.toLowerCase()) {
        case 'barbell':
            return {
                label: 'Barbell',
                icon: 'fa-duotone fa-dumbbell',
                class: 'bg-teal-500/10 text-teal-600 dark:text-teal-400 border-teal-500/20',
            };
        case 'dumbbell':
            return {
                label: 'Dumbbell',
                icon: 'fa-duotone fa-dumbbell',
                class: 'bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border-cyan-500/20',
            };
        case 'cable':
            return {
                label: 'Cable',
                icon: 'fa-duotone fa-arrows-up-down',
                class: 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
            };
        case 'machine':
            return {
                label: 'Machine',
                icon: 'fa-duotone fa-gear',
                class: 'bg-violet-500/10 text-violet-600 dark:text-violet-400 border-violet-500/20',
            };
        case 'bodyweight':
            return {
                label: 'Bodyweight',
                icon: 'fa-duotone fa-person-simple',
                class: 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
            };
        default:
            return {
                label: eq || 'None',
                icon: 'fa-duotone fa-cube',
                class: 'bg-zinc-500/10 text-zinc-500 dark:text-zinc-400 border-zinc-500/20',
            };
    }
};
</script>

<template>
    <Head title="Exercises Setup" />

    <AuthenticatedLayout>
        <!-- Top App Bar / Header -->
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 sm:h-11 sm:w-11 items-center justify-center rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 shadow-sm shrink-0">
                        <i class="fa-duotone fa-dumbbell text-lg sm:text-xl"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h1 class="text-base sm:text-xl font-extrabold text-zinc-900 dark:text-white tracking-tight truncate">
                                Exercises Library
                            </h1>
                            <span class="rounded-full bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 text-[10px] sm:text-[11px] font-bold text-emerald-600 dark:text-emerald-400 shrink-0">
                                Movement Setup
                            </span>
                        </div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate hidden xs:block">
                            Manage gym exercises, equipment categories, and form instructions
                        </p>
                    </div>
                </div>

                <!-- Primary Action (Big, comfortable touch target on mobile) -->
                <button
                    @click="openCreateModal"
                    type="button"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-3 sm:py-2.5 text-xs sm:text-sm font-bold text-white shadow-md shadow-emerald-500/20 hover:from-emerald-600 hover:to-teal-700 transition active:scale-[0.98] focus:outline-none"
                >
                    <i class="fa-duotone fa-plus text-sm"></i>
                    <span>Add Exercise</span>
                </button>
            </div>
        </template>

        <div class="py-4 sm:py-8">
            <div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

                <!-- Collapsible Filter Section (Replaced Stats Cards - Hidden by default) -->
                <transition
                    enter-active-class="transition duration-200 ease-out"
                    enter-from-class="transform -translate-y-2 opacity-0"
                    enter-to-class="transform translate-y-0 opacity-100"
                    leave-active-class="transition duration-150 ease-in"
                    leave-from-class="transform translate-y-0 opacity-100"
                    leave-to-class="transform -translate-y-2 opacity-0"
                >
                    <div
                        v-show="isFilterOpen"
                        class="rounded-2xl sm:rounded-3xl border border-emerald-500/20 bg-gradient-to-b from-emerald-500/5 to-transparent dark:from-emerald-950/20 p-4 sm:p-5 shadow-sm space-y-4"
                    >
                        <div class="flex items-center justify-between pb-3 border-b border-zinc-200/80 dark:border-zinc-800">
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 shadow-xs">
                                    <i class="fa-duotone fa-filter-list text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white">
                                        Filter Exercises
                                    </h3>
                                    <p class="text-[11px] text-zinc-400">Filter exercises by equipment type and target anatomy</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <button
                                    v-if="filterState.equipment || filterState.muscle_group_id"
                                    @click="resetAllFilters"
                                    type="button"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold text-rose-500 hover:bg-rose-500/10 transition"
                                >
                                    <i class="fa-duotone fa-arrow-rotate-left text-[11px]"></i>
                                    <span>Reset Filters</span>
                                </button>
                                <button
                                    @click="isFilterOpen = false"
                                    type="button"
                                    class="h-7 w-7 rounded-lg text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800 flex items-center justify-center transition"
                                    title="Close Filter Panel"
                                >
                                    <i class="fa-duotone fa-xmark text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Filter Controls -->
                        <!-- Filter Controls -->
                        <div class="max-w-xs">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1.5">
                                Equipment Category
                            </label>
                            <SearchableSelect
                                v-model="filterState.equipment"
                                :options="equipmentSelectOptions"
                                placeholder="All Equipment"
                                search-placeholder="Search equipment..."
                                accent-color="emerald"
                                @change="onFilterChange('equipment', $event)"
                            />
                        </div>
                    </div>
                </transition>

                <!-- Reusable DataTable Component (Unified table on mobile & desktop with horizontal scroll) -->
                <DataTable
                    :columns="columns"
                    :items="exercises.data"
                    :pagination="exercises"
                    :loading="isSearching"
                    empty-title="No exercises found"
                    :empty-subtitle="filterState.search || filterState.equipment || filterState.muscle_group_id ? 'Try adjusting your search criteria or filters.' : 'Tap Add Exercise to define your first workout movement.'"
                    empty-icon="fa-duotone fa-dumbbell"
                >
                    <!-- Search & Filter Toolbar -->
                    <template #toolbar>
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
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
                                    placeholder="Live search by exercise name..."
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

                            <!-- Filter Toggle Button (In table toolbar) -->
                            <button
                                @click="isFilterOpen = !isFilterOpen"
                                type="button"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border px-3.5 py-2.5 sm:py-2 text-xs sm:text-sm font-bold transition shadow-xs shrink-0 select-none"
                                :class="isFilterOpen || activeFilterCount > 0
                                    ? 'border-emerald-500/50 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 ring-2 ring-emerald-500/20'
                                    : 'border-zinc-200 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-700/60'"
                            >
                                <i class="fa-duotone fa-filter text-xs"></i>
                                <span>Filter</span>
                                <span
                                    v-if="activeFilterCount > 0"
                                    class="inline-flex items-center justify-center h-4 min-w-[16px] px-1 rounded-full bg-emerald-500 text-[10px] font-black text-white"
                                >
                                    {{ activeFilterCount }}
                                </span>
                            </button>

                            <!-- Reset Button if any filter active -->
                            <button
                                v-if="filterState.search || filterState.equipment || filterState.muscle_group_id"
                                @click="resetAllFilters"
                                type="button"
                                class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 px-3 py-2.5 sm:py-2 text-xs font-bold text-zinc-700 dark:text-zinc-200 hover:bg-zinc-200/80 dark:hover:bg-zinc-700 transition shrink-0"
                                title="Reset all filters"
                            >
                                <i class="fa-duotone fa-arrow-rotate-left text-xs"></i>
                                <span class="hidden sm:inline">Reset</span>
                            </button>
                        </div>
                    </template>

                    <!-- DESKTOP TABLE CELLS -->
                    <template #cell-name="{ item }">
                        <div class="flex items-center gap-3 min-w-0 pr-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500/10 to-teal-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                <i class="fa-duotone fa-dumbbell text-base"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-zinc-900 dark:text-white truncate">
                                    {{ item.name }}
                                </div>
                                <div class="text-[11px] text-zinc-400 truncate">
                                    {{ item.description || 'No instructions provided.' }}
                                </div>
                            </div>
                        </div>
                    </template>

                    <template #cell-equipment="{ item }">
                        <div class="flex items-center">
                            <span
                                v-if="item.equipment"
                                class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-bold whitespace-nowrap"
                                :class="getEquipmentBadge(item.equipment).class"
                            >
                                <i :class="getEquipmentBadge(item.equipment).icon" class="text-[10px]"></i>
                                {{ getEquipmentBadge(item.equipment).label }}
                            </span>
                            <span v-else class="text-zinc-400 text-xs">—</span>
                        </div>
                    </template>


                    <template #cell-video_url="{ item }">
                        <div class="flex items-center justify-center">
                            <a
                                v-if="item.video_url"
                                :href="item.video_url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-500 hover:bg-rose-500/20 transition"
                                title="View Demonstration Video"
                            >
                                <i class="fa-duotone fa-play text-xs"></i>
                            </a>
                            <span v-else class="text-zinc-300 dark:text-zinc-600 text-xs">—</span>
                        </div>
                    </template>

                    <template #cell-is_active="{ item }">
                        <div class="flex items-center justify-center">
                            <span
                                v-if="item.is_active"
                                class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap"
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Active
                            </span>
                            <span
                                v-else
                                class="inline-flex items-center gap-1.5 rounded-full bg-zinc-500/10 border border-zinc-500/20 px-2.5 py-0.5 text-xs font-bold text-zinc-500 dark:text-zinc-400 whitespace-nowrap"
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-zinc-400"></span>
                                Inactive
                            </span>
                        </div>
                    </template>

                    <template #cell-actions="{ item }">
                        <div class="flex items-center justify-end gap-1.5">
                            <button
                                @click="openEditModal(item)"
                                type="button"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:text-emerald-500 hover:border-emerald-500/50 transition shadow-sm"
                                title="Edit"
                            >
                                <i class="fa-duotone fa-pen-to-square text-xs"></i>
                            </button>
                            <button
                                @click="confirmDelete(item)"
                                type="button"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 dark:border-rose-900/50 bg-rose-50/50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/40 transition shadow-sm"
                                title="Delete"
                            >
                                <i class="fa-duotone fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </template>
                </DataTable>

            </div>
        </div>

        <!-- Create / Edit Form Modal (Mobile optimized touch sheet) -->
        <Modal :show="isFormModalOpen" @close="closeFormModal" max-width="xl">
            <div class="p-5 sm:p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl sm:rounded-3xl shadow-2xl max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-zinc-100 dark:border-zinc-800">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                            <i class="fa-duotone fa-dumbbell text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white">
                                {{ isEditing ? 'Edit Exercise' : 'Add Exercise' }}
                            </h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                {{ isEditing ? 'Update exercise specifications and equipment' : 'Define movement instructions and equipment' }}
                            </p>
                        </div>
                    </div>
                    <button
                        @click="closeFormModal"
                        type="button"
                        class="p-2 rounded-xl text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition"
                    >
                        <i class="fa-duotone fa-xmark text-sm"></i>
                    </button>
                </div>

                <!-- Form Fields -->
                <form @submit.prevent="submitForm" class="mt-4 sm:mt-5 space-y-4">
                    <!-- Exercise Name (Maxlength 50) -->
                    <div>
                        <div class="flex items-center justify-between">
                            <InputLabel for="ex_name" value="Exercise Name *" />
                            <span class="text-[10px] text-zinc-400">{{ (form.name || '').length }}/50</span>
                        </div>
                        <TextInput
                            id="ex_name"
                            v-model="form.name"
                            type="text"
                            maxlength="50"
                            class="mt-1 block w-full text-base sm:text-sm"
                            placeholder="e.g. Incline Dumbbell Bench Press"
                            required
                            autofocus
                        />
                        <InputError :message="form.errors.name" class="mt-1" />
                    </div>

                    <!-- SearchableSelect for Equipment Type -->
                    <div>
                        <InputLabel value="Equipment Type" class="mb-1" />
                        <SearchableSelect
                            v-model="form.equipment"
                            :options="equipmentSelectOptions"
                            placeholder="Select Equipment Type"
                            search-placeholder="Search equipment..."
                            accent-color="emerald"
                        />
                        <InputError :message="form.errors.equipment" class="mt-1" />
                    </div>


                    <!-- Description / Instructions Textarea -->
                    <div>
                        <InputLabel for="ex_desc" value="Instructions / Form Cues" />
                        <textarea
                            id="ex_desc"
                            v-model="form.description"
                            rows="3"
                            class="mt-1 block w-full text-base sm:text-sm rounded-xl border border-zinc-200 dark:border-zinc-700/80 bg-zinc-50/70 dark:bg-zinc-800/60 p-3 text-zinc-900 dark:text-zinc-100 shadow-sm focus:border-emerald-500 focus:bg-white dark:focus:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition placeholder-zinc-400"
                            placeholder="Proper grip, movement range, breathing instructions..."
                        ></textarea>
                        <InputError :message="form.errors.description" class="mt-1" />
                    </div>

                    <!-- Video Demo URL -->
                    <div>
                        <InputLabel for="ex_video" value="Video Demo URL" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                                <i class="fa-duotone fa-video text-xs"></i>
                            </span>
                            <TextInput
                                id="ex_video"
                                v-model="form.video_url"
                                type="url"
                                class="block w-full pl-9 text-base sm:text-sm"
                                placeholder="https://youtube.com/watch?v=..."
                            />
                        </div>
                        <InputError :message="form.errors.video_url" class="mt-1" />
                    </div>

                    <!-- Active Toggle -->
                    <div class="flex items-center justify-between p-3.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/30">
                        <div>
                            <span class="text-xs font-bold text-zinc-800 dark:text-white block">Active Status</span>
                            <span class="text-[11px] text-zinc-400">Available in workout builders</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                v-model="form.is_active"
                                class="sr-only peer"
                            />
                            <div class="w-11 h-6 bg-zinc-300 dark:bg-zinc-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>

                    <!-- Modal Actions (Mobile thumb friendly) -->
                    <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-zinc-100 dark:border-zinc-800">
                        <SecondaryButton @click="closeFormModal" type="button" class="w-1/2 sm:w-auto py-3 sm:py-2.5">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            type="submit"
                            class="w-1/2 sm:w-auto py-3 sm:py-2.5"
                        >
                            <i v-if="form.processing" class="fa-duotone fa-spinner-third fa-spin mr-1.5"></i>
                            <span>{{ isEditing ? 'Update' : 'Save' }}</span>
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <Modal :show="isDeleteModalOpen" @close="closeDeleteModal" max-width="md">
            <div class="p-5 sm:p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl sm:rounded-3xl shadow-2xl">
                <div class="flex items-center gap-3 text-rose-500 mb-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-500/10 border border-rose-500/20">
                        <i class="fa-duotone fa-triangle-exclamation text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white">Delete Exercise</h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Record will be marked as deleted.</p>
                    </div>
                </div>

                <p class="text-xs sm:text-sm text-zinc-600 dark:text-zinc-300 mb-5">
                    Are you sure you want to delete
                    <strong class="font-bold text-zinc-900 dark:text-white">"{{ selectedExercise?.name }}"</strong>?
                    You can revive it anytime by adding the same name again.
                </p>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                    <SecondaryButton @click="closeDeleteModal" type="button" class="w-1/2 sm:w-auto">
                        Cancel
                    </SecondaryButton>
                    <DangerButton
                        @click="executeDelete"
                        :class="{ 'opacity-25': deleteForm.processing }"
                        :disabled="deleteForm.processing"
                        class="w-1/2 sm:w-auto"
                    >
                        <i v-if="deleteForm.processing" class="fa-duotone fa-spinner-third fa-spin mr-1.5"></i>
                        <span>Delete</span>
                    </DangerButton>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
