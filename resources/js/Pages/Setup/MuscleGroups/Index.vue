<script setup>
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatsCards from '@/Components/StatsCards.vue';
import DataTable from '@/Components/DataTable.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { useTableFilter } from '@/Composables/useTableFilter.js';

const props = defineProps({
    muscleGroups: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ search: '' }),
    },
    stats: {
        type: Object,
        default: () => ({ total: 0, active: 0, inactive: 0 }),
    },
});

// Realtime Debounced Search State Management
const {
    filters: filterState,
    isSearching,
    onSearchInput,
    clearFilter,
    resetAllFilters,
} = useTableFilter('setup.muscle-groups.index', {
    search: props.filters.search || '',
});

// Configure Stats Cards (kept for reference or stats service)
const statCards = computed(() => [
    {
        title: 'Total Groups',
        value: props.stats.total,
        icon: 'fa-duotone fa-layer-group',
        color: 'zinc',
        subtitle: 'Anatomy categories defined',
    },
    {
        title: 'Active Groups',
        value: props.stats.active,
        icon: 'fa-duotone fa-circle-check',
        color: 'emerald',
        pulse: true,
        subtitle: 'Available for workout setup',
    },
    {
        title: 'Inactive Groups',
        value: props.stats.inactive,
        icon: 'fa-duotone fa-pause',
        color: 'amber',
        subtitle: 'Temporarily disabled',
    },
]);

// Table Columns Definition with balanced percentage widths
const columns = [
    { key: 'name', label: 'Muscle Group', align: 'left', width: '28%' },
    { key: 'description', label: 'Description', align: 'left', width: '38%' },
    { key: 'exercises_count', label: 'Linked Exercises', align: 'center', width: '14%' },
    { key: 'is_active', label: 'Status', align: 'center', width: '10%' },
    { key: 'actions', label: 'Actions', align: 'right', width: '10%' },
];

// Modal State Management
const isFormModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const isEditing = ref(false);
const selectedMuscleGroup = ref(null);

// Form instance (maxlength 50 on name)
const form = useForm({
    id: null,
    name: '',
    description: '',
    is_active: true,
});

// Open Create Modal
const openCreateModal = () => {
    isEditing.value = false;
    selectedMuscleGroup.value = null;
    form.reset();
    form.clearErrors();
    form.is_active = true;
    isFormModalOpen.value = true;
};

// Open Edit Modal
const openEditModal = (group) => {
    isEditing.value = true;
    selectedMuscleGroup.value = group;
    form.clearErrors();
    form.id = group.id;
    form.name = group.name;
    form.description = group.description || '';
    form.is_active = Boolean(group.is_active);
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
    if (isEditing.value && selectedMuscleGroup.value) {
        form.put(route('setup.muscle-groups.update', selectedMuscleGroup.value.id), {
            preserveScroll: true,
            onSuccess: () => closeFormModal(),
        });
    } else {
        form.post(route('setup.muscle-groups.store'), {
            preserveScroll: true,
            onSuccess: () => closeFormModal(),
        });
    }
};

// Open Delete Confirmation Modal
const confirmDelete = (group) => {
    selectedMuscleGroup.value = group;
    isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    selectedMuscleGroup.value = null;
};

// Execute Soft Deletion
const deleteForm = useForm({});
const executeDelete = () => {
    if (!selectedMuscleGroup.value) return;

    deleteForm.delete(route('setup.muscle-groups.destroy', selectedMuscleGroup.value.id), {
        preserveScroll: true,
        onSuccess: () => closeDeleteModal(),
    });
};
</script>

<template>
    <Head title="Muscle Groups Setup" />

    <AuthenticatedLayout>
        <!-- Top App Bar / Header -->
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 sm:h-11 sm:w-11 items-center justify-center rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 shadow-sm shrink-0">
                        <i class="fa-duotone fa-person-running text-lg sm:text-xl"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h1 class="text-base sm:text-xl font-extrabold text-zinc-900 dark:text-white tracking-tight truncate">
                                Muscle Groups
                            </h1>
                            <span class="rounded-full bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 text-[10px] sm:text-[11px] font-bold text-emerald-600 dark:text-emerald-400 shrink-0">
                                Anatomy
                            </span>
                        </div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate hidden xs:block">
                            Configure muscle anatomy categories for workouts
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
                    <span>Add Muscle Group</span>
                </button>
            </div>
        </template>

        <div class="py-4 sm:py-8">
            <div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

                <!-- Reusable DataTable Component (Unified table on mobile & desktop with horizontal scroll) -->
                <DataTable
                    :columns="columns"
                    :items="muscleGroups.data"
                    :pagination="muscleGroups"
                    :loading="isSearching"
                    empty-title="No muscle groups found"
                    :empty-subtitle="filterState.search ? 'Try adjusting your search criteria.' : 'Tap Add Muscle Group to configure your first anatomy group.'"
                    empty-icon="fa-duotone fa-person-running"
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
                                    placeholder="Live search muscle groups by name or description..."
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

                    <!-- DESKTOP TABLE CELLS -->
                    <template #cell-name="{ item }">
                        <div class="flex items-center gap-3 min-w-0 pr-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500/10 to-teal-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                <i class="fa-duotone fa-person text-base"></i>
                            </div>
                            <div class="font-bold text-zinc-900 dark:text-white truncate min-w-0">
                                {{ item.name }}
                            </div>
                        </div>
                    </template>

                    <template #cell-description="{ item }">
                        <div class="truncate text-zinc-500 dark:text-zinc-400 pr-2">
                            {{ item.description || '—' }}
                        </div>
                    </template>

                    <template #cell-exercises_count="{ item }">
                        <div class="flex items-center justify-center">
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                <i class="fa-duotone fa-dumbbell text-[10px]"></i>
                                {{ item.exercises_count ?? 0 }}
                            </span>
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
        <Modal :show="isFormModalOpen" @close="closeFormModal" max-width="lg">
            <div class="p-5 sm:p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl sm:rounded-3xl shadow-2xl">
                <!-- Modal Title -->
                <div class="flex items-center justify-between pb-4 border-b border-zinc-100 dark:border-zinc-800">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                            <i class="fa-duotone fa-person-running text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white">
                                {{ isEditing ? 'Edit Muscle Group' : 'Add Muscle Group' }}
                            </h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                {{ isEditing ? 'Update anatomy group details' : 'Define an anatomy movement category' }}
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
                    <!-- Name Input (Maxlength 50) -->
                    <div>
                        <div class="flex items-center justify-between">
                            <InputLabel for="mg_name" value="Muscle Group Name *" />
                            <span class="text-[10px] text-zinc-400">{{ (form.name || '').length }}/50</span>
                        </div>
                        <TextInput
                            id="mg_name"
                            v-model="form.name"
                            type="text"
                            maxlength="50"
                            class="mt-1 block w-full text-base sm:text-sm"
                            placeholder="e.g. Chest, Back, Shoulders"
                            required
                            autofocus
                        />
                        <InputError :message="form.errors.name" class="mt-1" />
                    </div>

                    <!-- Description Textarea -->
                    <div>
                        <InputLabel for="mg_desc" value="Description" />
                        <textarea
                            id="mg_desc"
                            v-model="form.description"
                            rows="3"
                            class="mt-1 block w-full text-base sm:text-sm rounded-xl border border-zinc-200 dark:border-zinc-700/80 bg-zinc-50/70 dark:bg-zinc-800/60 p-3 text-zinc-900 dark:text-zinc-100 shadow-sm focus:border-emerald-500 focus:bg-white dark:focus:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition placeholder-zinc-400"
                            placeholder="Anatomy targeting cues or movement notes..."
                        ></textarea>
                        <InputError :message="form.errors.description" class="mt-1" />
                    </div>

                    <!-- Active Toggle -->
                    <div class="flex items-center justify-between p-3.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/30">
                        <div>
                            <span class="text-xs font-bold text-zinc-800 dark:text-white block">Active Status</span>
                            <span class="text-[11px] text-zinc-400">Available for exercise assignments</span>
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
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white">Delete Muscle Group</h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Record will be marked as deleted.</p>
                    </div>
                </div>

                <p class="text-xs sm:text-sm text-zinc-600 dark:text-zinc-300 mb-5">
                    Are you sure you want to delete
                    <strong class="font-bold text-zinc-900 dark:text-white">"{{ selectedMuscleGroup?.name }}"</strong>?
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
