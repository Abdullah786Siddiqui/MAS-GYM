<script setup>
import { ref, computed, onMounted, onUnmounted, watch, nextTick } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Number, Array],
        default: null,
    },
    options: {
        type: Array,
        required: true,
        // Array of { value: any, label: string, icon?: string, subtitle?: string } or simple strings/numbers
    },
    multiple: {
        type: Boolean,
        default: false,
    },
    placeholder: {
        type: String,
        default: 'Select an option...',
    },
    searchPlaceholder: {
        type: String,
        default: 'Type to search...',
    },
    clearable: {
        type: Boolean,
        default: true,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    accentColor: {
        type: String,
        default: 'emerald', // 'emerald' | 'cyan'
    },
});

const emit = defineEmits(['update:modelValue', 'change']);

const isOpen = ref(false);
const searchQuery = ref('');
const rootRef = ref(null);
const searchInputRef = ref(null);

// Normalize options to { value, label, icon, subtitle }
const normalizedOptions = computed(() => {
    return props.options.map((opt) => {
        if (typeof opt === 'object' && opt !== null) {
            return {
                value: opt.value ?? opt.id,
                label: String(opt.label ?? opt.name ?? opt.value),
                icon: opt.icon || null,
                subtitle: opt.subtitle || opt.description || null,
            };
        }
        return {
            value: opt,
            label: String(opt),
            icon: null,
            subtitle: null,
        };
    });
});

// Filtered options based on search input
const filteredOptions = computed(() => {
    const q = searchQuery.value.trim().toLowerCase();
    if (!q) return normalizedOptions.value;

    return normalizedOptions.value.filter((opt) => {
        return (
            opt.label.toLowerCase().includes(q) ||
            (opt.subtitle && opt.subtitle.toLowerCase().includes(q))
        );
    });
});

// Selected items helpers
const isSelected = (value) => {
    if (props.multiple) {
        return Array.isArray(props.modelValue) && props.modelValue.includes(value);
    }
    return props.modelValue === value;
};

const selectedOption = computed(() => {
    if (props.multiple) return null;
    return normalizedOptions.value.find((opt) => opt.value === props.modelValue) || null;
});

const selectedOptionsList = computed(() => {
    if (!props.multiple || !Array.isArray(props.modelValue)) return [];
    return normalizedOptions.value.filter((opt) => props.modelValue.includes(opt.value));
});

// Selection handler
const selectOption = (opt) => {
    if (props.disabled) return;

    if (props.multiple) {
        const current = Array.isArray(props.modelValue) ? [...props.modelValue] : [];
        const index = current.indexOf(opt.value);
        if (index > -1) {
            current.splice(index, 1);
        } else {
            current.push(opt.value);
        }
        emit('update:modelValue', current);
        emit('change', current);
    } else {
        emit('update:modelValue', opt.value);
        emit('change', opt.value);
        closeDropdown();
    }
};

const removeTag = (value, e) => {
    e.stopPropagation();
    if (!props.multiple || props.disabled) return;
    const current = Array.isArray(props.modelValue) ? [...props.modelValue] : [];
    const index = current.indexOf(value);
    if (index > -1) {
        current.splice(index, 1);
        emit('update:modelValue', current);
        emit('change', current);
    }
};

const clearValue = (e) => {
    e.stopPropagation();
    if (props.disabled) return;
    const emptyVal = props.multiple ? [] : null;
    emit('update:modelValue', emptyVal);
    emit('change', emptyVal);
};

// Toggle Dropdown
const toggleDropdown = () => {
    if (props.disabled) return;
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        searchQuery.value = '';
        nextTick(() => {
            searchInputRef.value?.focus();
        });
    }
};

const closeDropdown = () => {
    isOpen.value = false;
    searchQuery.value = '';
};

// Click outside listener
const handleClickOutside = (e) => {
    if (rootRef.value && !rootRef.value.contains(e.target)) {
        closeDropdown();
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

// Dynamic accents
const accentClasses = computed(() => {
    if (props.accentColor === 'cyan') {
        return {
            focusRing: 'focus:ring-cyan-500/20 focus:border-cyan-500',
            activeBg: 'bg-cyan-500 text-white',
            tagClass: 'bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border-cyan-500/20',
            hoverBg: 'hover:bg-cyan-50 dark:hover:bg-cyan-950/40',
            checkColor: 'text-cyan-500',
        };
    }
    return {
        focusRing: 'focus:ring-emerald-500/20 focus:border-emerald-500',
        activeBg: 'bg-emerald-500 text-white',
        tagClass: 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
        hoverBg: 'hover:bg-emerald-50 dark:hover:bg-emerald-950/40',
        checkColor: 'text-emerald-500',
    };
});
</script>

<template>
    <div ref="rootRef" class="relative w-full text-left">
        <!-- Trigger Input Box -->
        <div
            @click="toggleDropdown"
            class="min-h-[42px] w-full cursor-pointer rounded-xl border border-zinc-200 dark:border-zinc-700/80 bg-zinc-50/70 dark:bg-zinc-800/60 px-3.5 py-2 text-xs sm:text-sm text-zinc-900 dark:text-zinc-100 transition-all duration-200 hover:border-zinc-300 dark:hover:border-zinc-600 flex items-center justify-between gap-2 shadow-sm"
            :class="[
                isOpen ? accentClasses.focusRing + ' ring-2 bg-white dark:bg-zinc-800 border-transparent' : '',
                disabled ? 'opacity-50 cursor-not-allowed bg-zinc-100 dark:bg-zinc-900' : '',
            ]"
        >
            <!-- Single Select Display -->
            <div v-if="!multiple" class="flex items-center gap-2 truncate flex-1">
                <template v-if="selectedOption">
                    <i v-if="selectedOption.icon" :class="[selectedOption.icon, 'text-zinc-400']"></i>
                    <span class="font-bold text-zinc-800 dark:text-zinc-100 truncate">
                        {{ selectedOption.label }}
                    </span>
                </template>
                <span v-else class="text-zinc-400 dark:text-zinc-500 select-none">
                    {{ placeholder }}
                </span>
            </div>

            <!-- Multi Select Chips Display -->
            <div v-else class="flex flex-wrap items-center gap-1.5 flex-1 min-h-[26px]">
                <template v-if="selectedOptionsList.length > 0">
                    <span
                        v-for="item in selectedOptionsList"
                        :key="item.value"
                        class="inline-flex items-center gap-1 rounded-lg border px-2 py-0.5 text-xs font-bold transition"
                        :class="accentClasses.tagClass"
                    >
                        <span>{{ item.label }}</span>
                        <button
                            type="button"
                            @click="removeTag(item.value, $event)"
                            class="hover:opacity-75 focus:outline-none"
                        >
                            <i class="fa-duotone fa-xmark text-[10px]"></i>
                        </button>
                    </span>
                </template>
                <span v-else class="text-zinc-400 dark:text-zinc-500 select-none">
                    {{ placeholder }}
                </span>
            </div>

            <!-- Right Controls: Clear and Chevron -->
            <div class="flex items-center gap-1.5 shrink-0 text-zinc-400">
                <button
                    v-if="clearable && ((!multiple && modelValue !== null && modelValue !== '') || (multiple && Array.isArray(modelValue) && modelValue.length > 0))"
                    type="button"
                    @click="clearValue"
                    class="p-0.5 hover:text-zinc-600 dark:hover:text-zinc-200 transition"
                    title="Clear"
                >
                    <i class="fa-duotone fa-circle-xmark text-xs"></i>
                </button>
                <i
                    class="fa-duotone fa-chevron-down text-xs transition-transform duration-200"
                    :class="{ 'rotate-180': isOpen }"
                ></i>
            </div>
        </div>

        <!-- Floating Dropdown Panel -->
        <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="transform scale-95 opacity-0 -translate-y-2"
            enter-to-class="transform scale-100 opacity-100 translate-y-0"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="transform scale-100 opacity-100 translate-y-0"
            leave-to-class="transform scale-95 opacity-0 -translate-y-2"
        >
            <div
                v-if="isOpen"
                class="absolute left-0 right-0 z-50 mt-1.5 overflow-hidden rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white/95 dark:bg-zinc-900/95 shadow-2xl backdrop-blur-xl"
            >
                <!-- Search Input Header -->
                <div class="p-2 border-b border-zinc-100 dark:border-zinc-800/80 bg-zinc-50/60 dark:bg-zinc-800/40">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                            <i class="fa-duotone fa-magnifying-glass text-xs"></i>
                        </span>
                        <input
                            ref="searchInputRef"
                            v-model="searchQuery"
                            type="text"
                            :placeholder="searchPlaceholder"
                            class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 pl-8 pr-3 py-1.5 text-xs text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                            @keydown.esc="closeDropdown"
                        />
                    </div>
                </div>

                <!-- Options List -->
                <div class="max-h-56 overflow-y-auto p-1.5 space-y-0.5">
                    <div
                        v-for="opt in filteredOptions"
                        :key="opt.value"
                        @click="selectOption(opt)"
                        class="flex items-center justify-between px-3 py-2 rounded-xl text-xs sm:text-sm cursor-pointer transition select-none"
                        :class="[
                            isSelected(opt.value)
                                ? 'bg-zinc-100 dark:bg-zinc-800 font-bold text-zinc-900 dark:text-white'
                                : 'text-zinc-700 dark:text-zinc-300 ' + accentClasses.hoverBg,
                        ]"
                    >
                        <div class="flex items-center gap-2.5 truncate">
                            <i v-if="opt.icon" :class="[opt.icon, 'text-zinc-400 text-xs shrink-0']"></i>
                            <div class="truncate">
                                <div class="font-semibold truncate">{{ opt.label }}</div>
                                <div v-if="opt.subtitle" class="text-[11px] text-zinc-400 dark:text-zinc-500 truncate">
                                    {{ opt.subtitle }}
                                </div>
                            </div>
                        </div>

                        <!-- Checkmark Indicator -->
                        <span v-if="isSelected(opt.value)" class="shrink-0 ml-2">
                            <i class="fa-duotone fa-check font-bold" :class="accentClasses.checkColor"></i>
                        </span>
                    </div>

                    <!-- No Results -->
                    <div v-if="filteredOptions.length === 0" class="py-6 text-center text-zinc-400">
                        <i class="fa-duotone fa-face-frown text-lg mb-1 block"></i>
                        <span class="text-xs">No matching options found</span>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>
