<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SearchableSelect from '@/Components/SearchableSelect.vue';
import { VueDraggable } from 'vue-draggable-plus';
import axios from 'axios';

const props = defineProps({
    muscleGroups: {
        type: Array,
        default: () => [],
    },
    selectedMuscleGroupId: {
        type: Number,
        default: null,
    },
    exercisesPool: {
        type: Array,
        default: () => [],
    },
    assignedExercises: {
        type: Array,
        default: () => [],
    },
    assignedExerciseIds: {
        type: Array,
        default: () => [],
    },
});

// Active selected muscle group state
const currentMuscleGroupId = ref(props.selectedMuscleGroupId);

// Reactive local state for assigned exercises (for instant 0ms optimistic updates)
const localAssignedList = ref([...props.assignedExercises]);
const localAssignedIds = ref(new Set(props.assignedExerciseIds));

// Muscle group search filter in vertical sidebar
const muscleGroupSearch = ref('');
const filteredMuscleGroups = computed(() => {
    if (!muscleGroupSearch.value.trim()) return props.muscleGroups;
    const query = muscleGroupSearch.value.toLowerCase().trim();
    return props.muscleGroups.filter((mg) => mg.name?.toLowerCase().includes(query));
});

// Sync when props change (e.g. when switching muscle groups)
watch(() => props.assignedExercises, (newVal) => {
    localAssignedList.value = [...newVal];
});

watch(() => props.assignedExerciseIds, (newVal) => {
    localAssignedIds.value = new Set(newVal);
});

watch(() => props.selectedMuscleGroupId, (newVal) => {
    currentMuscleGroupId.value = newVal;
});

// Currently selected muscle group object
const currentMuscleGroup = computed(() => {
    return props.muscleGroups.find((mg) => mg.id === currentMuscleGroupId.value) || props.muscleGroups[0] || null;
});

// Formatted muscle group options for Mobile SearchableSelect
const muscleGroupSelectOptions = computed(() => {
    return props.muscleGroups.map((mg) => ({
        value: mg.id,
        label: `${mg.name} (${mg.exercises_count ?? 0})`,
        icon: 'fa-duotone fa-person-running',
    }));
});

// Switch muscle group
const switchMuscleGroup = (id) => {
    if (!id || id === currentMuscleGroupId.value) return;
    currentMuscleGroupId.value = id;
    router.get(
        route('setup.exercise-assignments.manage'),
        { muscle_group_id: id },
        { preserveState: true, preserveScroll: true }
    );
};

// Exercise Library Pool Filters
const poolSearch = ref('');
const poolEquipmentFilter = ref('all');

const equipmentTabs = [
    { value: 'all', label: 'All' },
    { value: 'barbell', label: 'Barbell' },
    { value: 'dumbbell', label: 'Dumbbell' },
    { value: 'cable', label: 'Cable' },
    { value: 'machine', label: 'Machine' },
    { value: 'bodyweight', label: 'Bodyweight' },
];

// Filtered pool exercises
const filteredPoolExercises = computed({
    get() {
        let list = [...props.exercisesPool];

        if (poolEquipmentFilter.value !== 'all') {
            list = list.filter((ex) => ex.equipment?.toLowerCase() === poolEquipmentFilter.value);
        }

        if (poolSearch.value.trim()) {
            const query = poolSearch.value.toLowerCase().trim();
            list = list.filter(
                (ex) =>
                    ex.name?.toLowerCase().includes(query) ||
                    ex.description?.toLowerCase().includes(query)
            );
        }

        return list;
    },
    set(val) {
        // VueDraggable v-model setter for clone list
    },
});

// Mobile Tab Toggle between "Assigned" and "Library Pool"
const mobileActiveTab = ref('assigned'); // 'assigned' | 'pool'

// Mobile detection to completely disable drag & drop on mobile touch screens
const isMobile = ref(false);
const checkMobile = () => {
    if (typeof window !== 'undefined') {
        isMobile.value = window.innerWidth < 1024;
    }
};

onMounted(() => {
    checkMobile();
    window.addEventListener('resize', checkMobile);
});

onUnmounted(() => {
    window.removeEventListener('resize', checkMobile);
});

// Toast notification state
const toast = ref({
    show: false,
    message: '',
    type: 'success',
});

let toastTimeout = null;
const showToast = (message, type = 'success') => {
    if (toastTimeout) clearTimeout(toastTimeout);
    toast.value = { show: true, message, type };
    toastTimeout = setTimeout(() => {
        toast.value.show = false;
    }, 2800);
};

// VueDraggable Clone Factory function
const cloneExercise = (element) => {
    return {
        ...element,
        pivot: { is_active: true, notes: null },
    };
};

// VueDraggable: When an item is dropped into localAssignedList
const onDraggableAdd = async (event) => {
    const addedItem = localAssignedList.value[event.newIndex];
    if (!addedItem) return;

    // Check for duplicate in assigned list
    const occurrences = localAssignedList.value.filter((ex) => ex.id === addedItem.id);
    if (occurrences.length > 1) {
        // Remove the duplicate clone
        localAssignedList.value.splice(event.newIndex, 1);
        showToast(`${addedItem.name} is already assigned to ${currentMuscleGroup.value?.name}.`, 'success');
        return;
    }

    // Add to tracking Set
    localAssignedIds.value.add(addedItem.id);

    // Update count badge
    if (currentMuscleGroup.value) {
        currentMuscleGroup.value.exercises_count = (currentMuscleGroup.value.exercises_count || 0) + 1;
    }

    showToast(`Assigned ${addedItem.name} to ${currentMuscleGroup.value?.name}`, 'success');

    // Real-time backend sync
    try {
        await axios.post(route('setup.exercise-assignments.assign'), {
            exercise_id: addedItem.id,
            muscle_group_id: currentMuscleGroupId.value,
        });
    } catch (err) {
        // Rollback on network failure
        localAssignedList.value.splice(event.newIndex, 1);
        localAssignedIds.value.delete(addedItem.id);
        if (currentMuscleGroup.value && currentMuscleGroup.value.exercises_count > 0) {
            currentMuscleGroup.value.exercises_count--;
        }
        showToast('Failed to assign exercise. Please try again.', 'error');
    }
};

// Click-to-Assign Handler (Comfortable for mobile & desktop touch)
const assignExercise = async (exercise) => {
    if (!currentMuscleGroupId.value) {
        showToast('Please select a muscle group first.', 'error');
        return;
    }

    if (localAssignedIds.value.has(exercise.id)) {
        showToast(`${exercise.name} is already assigned.`, 'success');
        return;
    }

    // 1. Optimistic UI update
    localAssignedIds.value.add(exercise.id);
    localAssignedList.value.push({
        ...exercise,
        pivot: { is_active: true, notes: null },
    });

    if (currentMuscleGroup.value) {
        currentMuscleGroup.value.exercises_count = (currentMuscleGroup.value.exercises_count || 0) + 1;
    }

    showToast(`Assigned ${exercise.name} to ${currentMuscleGroup.value?.name}`, 'success');

    // 2. Real-time background network sync
    try {
        await axios.post(route('setup.exercise-assignments.assign'), {
            exercise_id: exercise.id,
            muscle_group_id: currentMuscleGroupId.value,
        });
    } catch (err) {
        localAssignedIds.value.delete(exercise.id);
        localAssignedList.value = localAssignedList.value.filter((ex) => ex.id !== exercise.id);
        if (currentMuscleGroup.value && currentMuscleGroup.value.exercises_count > 0) {
            currentMuscleGroup.value.exercises_count--;
        }
        showToast('Failed to assign exercise. Please try again.', 'error');
    }
};

// Click-to-Unassign Handler
const unassignExercise = async (exercise) => {
    if (!currentMuscleGroupId.value) return;

    const backupItem = localAssignedList.value.find((ex) => ex.id === exercise.id);
    localAssignedIds.value.delete(exercise.id);
    localAssignedList.value = localAssignedList.value.filter((ex) => ex.id !== exercise.id);

    if (currentMuscleGroup.value && currentMuscleGroup.value.exercises_count > 0) {
        currentMuscleGroup.value.exercises_count--;
    }

    showToast(`Removed ${exercise.name}`, 'success');

    try {
        await axios.post(route('setup.exercise-assignments.unassign'), {
            exercise_id: exercise.id,
            muscle_group_id: currentMuscleGroupId.value,
        });
    } catch (err) {
        if (backupItem) {
            localAssignedIds.value.add(exercise.id);
            localAssignedList.value.push(backupItem);
            if (currentMuscleGroup.value) {
                currentMuscleGroup.value.exercises_count = (currentMuscleGroup.value.exercises_count || 0) + 1;
            }
        }
        showToast('Failed to remove exercise. Please try again.', 'error');
    }
};

// Equipment Badge helper
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
    <Head title="Assignment Workspace" />

    <AuthenticatedLayout>
        <!-- Top App Bar / Header -->
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <Link
                        :href="route('setup.exercise-assignments.index')"
                        class="flex h-10 w-10 sm:h-11 sm:w-11 items-center justify-center rounded-2xl bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300 hover:text-emerald-500 dark:hover:text-emerald-400 hover:border-emerald-500/30 transition shadow-sm shrink-0"
                        title="Back to Overview"
                    >
                        <i class="fa-duotone fa-arrow-left text-sm"></i>
                    </Link>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h1 class="text-base sm:text-xl font-extrabold text-zinc-900 dark:text-white tracking-tight truncate">
                                Assignment Workspace
                            </h1>
                            <span class="rounded-full bg-gradient-to-r from-emerald-500/10 to-teal-500/10 border border-emerald-500/20 px-2.5 py-0.5 text-[10px] sm:text-[11px] font-bold text-emerald-600 dark:text-emerald-400 shrink-0">
                                <span class="sm:hidden">Tap (+) to Assign</span>
                                <span class="hidden sm:inline">Drag & Drop / Tap (+)</span>
                            </span>
                        </div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate hidden xs:block">
                            <span class="sm:hidden">Select muscle group, then tap (+) on any exercise to assign</span>
                            <span class="hidden sm:inline">Select muscle group on the left, then drag exercises or tap (+) to assign side-by-side</span>
                        </p>
                    </div>
                </div>

                <!-- Right Action: Return to Table Overview -->
                <Link
                    :href="route('setup.exercise-assignments.index')"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3.5 py-2.5 sm:py-2 text-xs font-bold text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-700/60 transition shadow-xs"
                >
                    <i class="fa-duotone fa-table-list text-xs"></i>
                    <span>Overview Table</span>
                </Link>
            </div>
        </template>

        <div class="py-4 sm:py-6">
            <div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8 space-y-4">

                <!-- Mobile Muscle Group Quick Selector (visible only on small screens) -->
                <div class="block lg:hidden rounded-2xl border border-zinc-200/90 dark:border-zinc-800/90 bg-white dark:bg-zinc-900 p-3.5 shadow-sm space-y-2.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-500">
                                <i class="fa-duotone fa-person-running text-xs"></i>
                            </div>
                            <span class="text-xs font-bold text-zinc-900 dark:text-white">Active Muscle Group:</span>
                        </div>
                        <span class="text-xs font-extrabold text-emerald-600 dark:text-emerald-400">
                            {{ currentMuscleGroup?.name }} ({{ localAssignedList.length }})
                        </span>
                    </div>
                    <SearchableSelect
                        :model-value="currentMuscleGroupId"
                        :options="muscleGroupSelectOptions"
                        placeholder="Switch Muscle Group..."
                        search-placeholder="Search anatomy..."
                        accent-color="emerald"
                        @change="switchMuscleGroup"
                    />

                    <!-- Mobile Sub-Tab Switcher: Assigned vs Library Pool -->
                    <div class="grid grid-cols-2 gap-2 pt-1 border-t border-zinc-100 dark:border-zinc-800">
                        <button
                            type="button"
                            @click="mobileActiveTab = 'assigned'"
                            class="py-2 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5"
                            :class="mobileActiveTab === 'assigned'
                                ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-sm'
                                : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300'"
                        >
                            <i class="fa-duotone fa-bullseye-arrow"></i>
                            <span>Assigned ({{ localAssignedList.length }})</span>
                        </button>
                        <button
                            type="button"
                            @click="mobileActiveTab = 'pool'"
                            class="py-2 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5"
                            :class="mobileActiveTab === 'pool'
                                ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-sm'
                                : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300'"
                        >
                            <i class="fa-duotone fa-dumbbell"></i>
                            <span>Library Pool ({{ filteredPoolExercises.length }})</span>
                        </button>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- MAIN WORKSPACE LAYOUT:                                         -->
                <!-- Left: TARGET MUSCLE GROUP (Vertical Section)                  -->
                <!-- Right: Side-by-Side [Assigned Exercises] + [Exercise Pool]     -->
                <!-- ============================================================== -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

                    <!-- ============================================================== -->
                    <!-- SECTION 1: TARGET MUSCLE GROUP (VERTICAL SIDEBAR / LIST)       -->
                    <!-- ============================================================== -->
                    <div class="hidden lg:block lg:col-span-3 rounded-2xl sm:rounded-3xl border border-zinc-200/90 dark:border-zinc-800/90 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden sticky top-20">
                        <!-- Sidebar Header -->
                        <div class="p-4 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/60">
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 shadow-xs">
                                    <i class="fa-duotone fa-person-running text-base"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white">
                                        Target Muscle Group
                                    </h3>
                                    <p class="text-[11px] text-zinc-400">
                                        Select target anatomy
                                    </p>
                                </div>
                            </div>

                            <!-- Search Input for Muscle Groups -->
                            <div class="mt-3 relative">
                                <i class="fa-duotone fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs text-zinc-400"></i>
                                <input
                                    v-model="muscleGroupSearch"
                                    type="text"
                                    placeholder="Filter muscle groups..."
                                    class="w-full pl-8 pr-3 py-1.5 rounded-xl text-xs bg-white dark:bg-zinc-800/80 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-400 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                                />
                            </div>
                        </div>

                        <!-- Vertical Muscle Groups List -->
                        <div class="p-2 space-y-1 max-h-[calc(100vh-280px)] overflow-y-auto scrollbar-thin">
                            <button
                                v-for="mg in filteredMuscleGroups"
                                :key="mg.id"
                                @click="switchMuscleGroup(mg.id)"
                                type="button"
                                class="w-full flex items-center justify-between p-2.5 rounded-xl text-xs font-bold transition-all border select-none group text-left"
                                :class="mg.id === currentMuscleGroupId
                                    ? 'bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 text-white border-transparent shadow-md shadow-emerald-500/25 ring-2 ring-emerald-500/20'
                                    : 'bg-transparent text-zinc-700 dark:text-zinc-300 border-transparent hover:bg-zinc-100 dark:hover:bg-zinc-800/70 hover:border-zinc-200 dark:hover:border-zinc-700'"
                            >
                                <div class="flex items-center gap-2.5 min-w-0 pr-1">
                                    <span
                                        class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg text-[10px]"
                                        :class="mg.id === currentMuscleGroupId
                                            ? 'bg-white/20 text-white'
                                            : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 group-hover:text-emerald-500'"
                                    >
                                        <i class="fa-duotone fa-dumbbell"></i>
                                    </span>
                                    <span class="truncate">{{ mg.name }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <span
                                        class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-[10px] font-black"
                                        :class="mg.id === currentMuscleGroupId
                                            ? 'bg-white/25 text-white'
                                            : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400'"
                                    >
                                        {{ mg.exercises_count ?? 0 }}
                                    </span>
                                    <i
                                        class="fa-duotone fa-chevron-right text-[10px] transition-transform"
                                        :class="mg.id === currentMuscleGroupId ? 'translate-x-0.5 text-white' : 'text-zinc-400 opacity-0 group-hover:opacity-100'"
                                    ></i>
                                </div>
                            </button>

                            <div v-if="filteredMuscleGroups.length === 0" class="py-6 text-center text-xs text-zinc-400">
                                No muscle group matches "{{ muscleGroupSearch }}"
                            </div>
                        </div>
                    </div>

                    <!-- ============================================================== -->
                    <!-- SECTION 2 & 3: SIDE-BY-SIDE COLUMNS (lg:col-span-9)            -->
                    <!-- Left: "Assigned to [Muscle Group]" (Drop Zone)                 -->
                    <!-- Right: "Exercise Library Pool" (Available Exercises)           -->
                    <!-- ============================================================== -->
                    <div class="lg:col-span-9 grid grid-cols-1 md:grid-cols-2 gap-5 items-start">

                        <!-- ========================================================== -->
                        <!-- COLUMN A: ASSIGNED TO [SELECTED MUSCLE GROUP]              -->
                        <!-- ========================================================== -->
                        <div
                            class="rounded-2xl sm:rounded-3xl border border-zinc-200/90 dark:border-zinc-800/90 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden flex flex-col min-h-[580px]"
                            :class="{ 'hidden md:flex': mobileActiveTab !== 'assigned' }"
                        >
                            <!-- Header -->
                            <div class="p-4 sm:p-5 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/60 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-sm shrink-0">
                                        <i class="fa-duotone fa-bullseye-arrow text-base"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="text-xs sm:text-sm font-extrabold text-zinc-900 dark:text-white truncate">
                                                Assigned to {{ currentMuscleGroup?.name ?? 'Target' }}
                                            </h3>
                                        </div>
                                        <p class="text-[11px] text-zinc-400 truncate">
                                            <span class="sm:hidden">Tap (×) to remove</span>
                                            <span class="hidden sm:inline">Drop movements here or use (+)</span>
                                        </p>
                                    </div>
                                </div>

                                <span class="inline-flex items-center gap-1 rounded-full bg-gradient-to-r from-emerald-500/10 to-teal-500/10 border border-emerald-500/20 px-2.5 py-0.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 shrink-0">
                                    {{ localAssignedList.length }}
                                </span>
                            </div>

                            <!-- Drop Area Notification Bar -->
                            <div class="px-4 py-2 bg-emerald-50/60 dark:bg-emerald-950/20 border-b border-emerald-500/10 flex items-center justify-between text-[11px] text-emerald-600 dark:text-emerald-400">
                                <span class="flex items-center gap-1.5 font-medium">
                                    <i :class="isMobile ? 'fa-duotone fa-bullseye-arrow' : 'fa-duotone fa-arrow-down-to-bracket'"></i>
                                    <span>{{ isMobile ? 'Assigned Movements' : 'Drag target zone active' }}</span>
                                </span>
                                <span class="text-[10px] text-zinc-400">{{ isMobile ? 'Tap (×) to remove' : 'Drag to reorder' }}</span>
                            </div>

                            <!-- VueDraggable: Assigned Target List -->
                            <div class="p-3 sm:p-4 bg-zinc-50/30 dark:bg-zinc-900/40 flex-1 flex flex-col">
                                <VueDraggable
                                    v-model="localAssignedList"
                                    :group="{ name: 'exercises', put: !isMobile }"
                                    :disabled="isMobile"
                                    ghost-class="opacity-40"
                                    drag-class="scale-102"
                                    @add="onDraggableAdd"
                                    class="space-y-2 flex-1 min-h-[280px]"
                                >
                                    <!-- Assigned Exercise Card -->
                                    <div
                                        v-for="(item, index) in localAssignedList"
                                        :key="item.id"
                                        class="group relative flex items-center justify-between p-3 rounded-2xl border border-zinc-200 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 hover:border-emerald-500/40 hover:shadow-md transition-all duration-150 select-none"
                                        :class="{ 'cursor-grab active:cursor-grabbing': !isMobile }"
                                    >
                                        <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-700/80 text-[10px] font-black text-zinc-600 dark:text-zinc-300">
                                                {{ index + 1 }}
                                            </span>
                                            <div class="min-w-0 flex-1">
                                                <h4 class="font-bold text-xs text-zinc-900 dark:text-white truncate">
                                                    {{ item.name }}
                                                </h4>
                                                <div class="mt-0.5">
                                                    <span
                                                        class="inline-flex items-center px-1.5 py-0.2 rounded-md border text-[9px] font-bold"
                                                        :class="getEquipmentBadge(item.equipment).class"
                                                    >
                                                        {{ getEquipmentBadge(item.equipment).label }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Actions: Grip Handle (Desktop Only) + Unassign Button -->
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <span v-if="!isMobile" class="drag-handle text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 cursor-grab px-1 text-xs hidden lg:inline-flex" title="Drag to reorder">
                                                <i class="fa-solid fa-grip-vertical"></i>
                                            </span>
                                            <button
                                                type="button"
                                                @click.stop="unassignExercise(item)"
                                                class="flex h-8 w-8 sm:h-7 sm:w-7 items-center justify-center rounded-xl bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition active:scale-95"
                                                title="Remove exercise"
                                            >
                                                <i class="fa-duotone fa-xmark text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </VueDraggable>

                                <!-- Empty Assigned State -->
                                <div
                                    v-if="localAssignedList.length === 0"
                                    class="my-auto py-12 px-4 rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-800 text-center flex flex-col items-center justify-center bg-zinc-50/50 dark:bg-zinc-900/50"
                                >
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-500 mb-3">
                                        <i class="fa-duotone fa-arrow-down-to-line text-xl"></i>
                                    </div>
                                    <h4 class="text-xs font-bold text-zinc-900 dark:text-white">
                                        No movements assigned yet
                                    </h4>
                                    <p class="text-[11px] text-zinc-400 max-w-xs mt-1">
                                        <span class="sm:hidden">Switch to Library Pool tab and tap (+) to assign to {{ currentMuscleGroup?.name }}</span>
                                        <span class="hidden sm:inline">Drag exercises from the library on the right or tap (+) to assign to {{ currentMuscleGroup?.name }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- ========================================================== -->
                        <!-- COLUMN B: EXERCISE LIBRARY POOL (SIDE-BY-SIDE NEXT TO IT)  -->
                        <!-- ========================================================== -->
                        <div
                            class="rounded-2xl sm:rounded-3xl border border-zinc-200/90 dark:border-zinc-800/90 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden flex flex-col min-h-[580px]"
                            :class="{ 'hidden md:flex': mobileActiveTab !== 'pool' }"
                        >
                            <!-- Header -->
                            <div class="p-4 sm:p-5 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/60 space-y-3">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white shadow-sm shrink-0">
                                            <i class="fa-duotone fa-dumbbell text-base"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xs sm:text-sm font-extrabold text-zinc-900 dark:text-white">
                                                Exercise Library Pool
                                            </h3>
                                            <p class="text-[11px] text-zinc-400">
                                                <span class="sm:hidden">Tap (+) to assign to active muscle group</span>
                                                <span class="hidden sm:inline">Drag into target or tap (+) to assign</span>
                                            </p>
                                        </div>
                                    </div>

                                    <span class="rounded-full bg-zinc-100 dark:bg-zinc-800 px-2.5 py-0.5 text-xs font-bold text-zinc-600 dark:text-zinc-300">
                                        {{ filteredPoolExercises.length }} Available
                                    </span>
                                </div>

                                <!-- Search Input for Exercise Library -->
                                <div class="relative">
                                    <i class="fa-duotone fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs text-zinc-400"></i>
                                    <input
                                        v-model="poolSearch"
                                        type="text"
                                        placeholder="Search exercise library..."
                                        class="w-full pl-8 pr-8 py-2 rounded-xl text-xs bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-400 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                                    />
                                    <button
                                        v-if="poolSearch"
                                        @click="poolSearch = ''"
                                        type="button"
                                        class="absolute right-2.5 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 text-xs"
                                    >
                                        <i class="fa-duotone fa-xmark"></i>
                                    </button>
                                </div>

                                <!-- Equipment Category Filter Tabs -->
                                <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5 scrollbar-none">
                                    <button
                                        v-for="tab in equipmentTabs"
                                        :key="tab.value"
                                        @click="poolEquipmentFilter = tab.value"
                                        type="button"
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition whitespace-nowrap shrink-0 border"
                                        :class="poolEquipmentFilter === tab.value
                                            ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white border-transparent shadow-xs'
                                            : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 border-zinc-200/80 dark:border-zinc-700/80 hover:bg-zinc-200 dark:hover:bg-zinc-700'"
                                    >
                                        {{ tab.label }}
                                    </button>
                                </div>
                            </div>

                            <!-- VueDraggable: Available Pool List -->
                            <div class="p-3 sm:p-4 bg-zinc-50/30 dark:bg-zinc-900/40 flex-1 overflow-y-auto max-h-[calc(100vh-280px)] scrollbar-thin">
                                <VueDraggable
                                    v-model="filteredPoolExercises"
                                    :group="{ name: 'exercises', pull: isMobile ? false : 'clone', put: false }"
                                    :disabled="isMobile"
                                    :sort="false"
                                    :clone="cloneExercise"
                                    ghost-class="opacity-40"
                                    class="space-y-2 min-h-[280px]"
                                >
                                    <!-- Exercise Card -->
                                    <div
                                        v-for="exercise in filteredPoolExercises"
                                        :key="exercise.id"
                                        class="group relative flex items-center justify-between p-3 rounded-2xl border transition-all duration-150 select-none"
                                        :class="[
                                            !isMobile ? 'cursor-grab active:cursor-grabbing' : '',
                                            localAssignedIds.has(exercise.id)
                                                ? 'border-emerald-500/30 bg-emerald-50/30 dark:bg-emerald-950/30 shadow-none'
                                                : 'border-zinc-200 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 hover:border-emerald-500/40 hover:shadow-md'
                                        ]"
                                    >
                                        <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                            <span v-if="!isMobile" class="drag-handle text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 cursor-grab px-1 text-xs shrink-0 hidden lg:inline-flex" title="Drag to assign">
                                                <i class="fa-solid fa-grip-vertical"></i>
                                            </span>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <h4 class="font-bold text-xs text-zinc-900 dark:text-white truncate">
                                                        {{ exercise.name }}
                                                    </h4>
                                                    <span
                                                        v-if="localAssignedIds.has(exercise.id)"
                                                        class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded-md bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 text-[9px] font-extrabold"
                                                    >
                                                        <i class="fa-solid fa-check text-[8px]"></i>
                                                        <span>Assigned</span>
                                                    </span>
                                                </div>
                                                <div class="mt-0.5 flex items-center gap-1.5">
                                                    <span
                                                        class="inline-flex items-center px-1.5 py-0.2 rounded-md border text-[9px] font-bold"
                                                        :class="getEquipmentBadge(exercise.equipment).class"
                                                    >
                                                        {{ getEquipmentBadge(exercise.equipment).label }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Quick (+) Assign Button -->
                                        <div class="shrink-0">
                                            <button
                                                v-if="!localAssignedIds.has(exercise.id)"
                                                type="button"
                                                @click.stop="assignExercise(exercise)"
                                                class="flex h-8 w-8 sm:h-7 sm:w-7 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white transition active:scale-95 shadow-xs"
                                                title="Quick Assign"
                                            >
                                                <i class="fa-duotone fa-plus text-xs"></i>
                                            </button>
                                            <span
                                                v-else
                                                class="flex h-8 w-8 sm:h-7 sm:w-7 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400"
                                                title="Already Assigned"
                                            >
                                                <i class="fa-duotone fa-check text-xs"></i>
                                            </span>
                                        </div>
                                    </div>
                                </VueDraggable>

                                <!-- Empty Pool Results -->
                                <div
                                    v-if="filteredPoolExercises.length === 0"
                                    class="py-12 px-4 rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-800 text-center flex flex-col items-center justify-center bg-zinc-50/50 dark:bg-zinc-900/50"
                                >
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-zinc-100 dark:bg-zinc-800 text-zinc-400 mb-2">
                                        <i class="fa-duotone fa-magnifying-glass text-lg"></i>
                                    </div>
                                    <h4 class="text-xs font-bold text-zinc-900 dark:text-white">
                                        No exercises found
                                    </h4>
                                    <p class="text-[11px] text-zinc-400 mt-0.5">
                                        Try adjusting your search or equipment filter
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <!-- Floating Toast Alert Notification -->
        <transition
            enter-active-class="transform ease-out duration-300 transition"
            enter-from-class="translate-y-4 opacity-0 sm:translate-y-0 sm:translate-x-4"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="toast.show"
                class="fixed bottom-5 right-5 z-50 flex items-center gap-3 px-4 py-3 rounded-2xl shadow-xl border text-xs font-bold backdrop-blur-md"
                :class="toast.type === 'success'
                    ? 'bg-zinc-900/90 text-white border-zinc-700/80 shadow-emerald-500/10'
                    : 'bg-rose-950/90 text-rose-100 border-rose-800/80 shadow-rose-500/10'"
            >
                <div
                    class="flex h-6 w-6 items-center justify-center rounded-lg text-xs"
                    :class="toast.type === 'success' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400'"
                >
                    <i :class="toast.type === 'success' ? 'fa-duotone fa-circle-check' : 'fa-duotone fa-circle-exclamation'"></i>
                </div>
                <span>{{ toast.message }}</span>
            </div>
        </transition>
    </AuthenticatedLayout>
</template>

<style scoped>
.scrollbar-none::-webkit-scrollbar {
    display: none;
}
.scrollbar-none {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-thin::-webkit-scrollbar {
    width: 5px;
}
.scrollbar-thin::-webkit-scrollbar-track {
    background: transparent;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
    background: rgba(156, 163, 175, 0.3);
    border-radius: 9999px;
}
.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: rgba(156, 163, 175, 0.5);
}
</style>
