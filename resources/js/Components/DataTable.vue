<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    columns: {
        type: Array,
        required: true,
        // Each column: { key: string, label: string, align?: 'left'|'center'|'right', class?: string, width?: string }
    },
    items: {
        type: Array,
        default: () => [],
    },
    pagination: {
        type: Object,
        default: () => null,
    },
    emptyTitle: {
        type: String,
        default: 'No records found',
    },
    emptySubtitle: {
        type: String,
        default: 'There are no entries to display at this time.',
    },
    emptyIcon: {
        type: String,
        default: 'fa-duotone fa-folder-open',
    },
    loading: {
        type: Boolean,
        default: false,
    },
});

const getAlignClass = (align = 'left') => {
    switch (align) {
        case 'center':
            return 'text-center';
        case 'right':
            return 'text-right';
        case 'left':
        default:
            return 'text-left';
    }
};
</script>

<template>
    <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl border border-zinc-200/90 dark:border-zinc-800/90 bg-white dark:bg-zinc-900 shadow-sm transition-colors duration-300">

        <!-- Toolbar Slot (Search Input, SearchableSelect Dropdowns, Filter Buttons) -->
        <div v-if="$slots.toolbar" class="p-3 sm:p-5 border-b border-zinc-100 dark:border-zinc-800/80 bg-zinc-50/50 dark:bg-zinc-900/60">
            <slot name="toolbar" />
        </div>

        <!-- Subtle Top Progress Indicator (Zero layout shift, no screen shaking) -->
        <div class="h-0.5 w-full bg-zinc-100/60 dark:bg-zinc-800/60 overflow-hidden relative">
            <div
                v-if="loading"
                class="h-full bg-gradient-to-r from-emerald-500 via-teal-400 to-cyan-500 animate-pulse w-full"
            ></div>
        </div>

        <!-- Unified Table View (Mobile + Desktop with horizontal scroll) -->
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs sm:text-sm text-zinc-600 dark:text-zinc-300 border-collapse min-w-[760px]">
                <!-- Table Header -->
                <thead class="bg-zinc-50 dark:bg-zinc-800/60 text-[11px] uppercase tracking-wider font-extrabold text-zinc-400 dark:text-zinc-500 border-b border-zinc-200/80 dark:border-zinc-800">
                    <tr>
                        <th
                            v-for="col in columns"
                            :key="col.key"
                            scope="col"
                            class="px-5 py-3.5 select-none"
                            :class="[getAlignClass(col.align), col.class || '']"
                            :style="col.width ? { width: col.width } : {}"
                        >
                            <slot :name="`header-${col.key}`" :column="col">
                                {{ col.label }}
                            </slot>
                        </th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody
                    class="divide-y divide-zinc-100 dark:divide-zinc-800/60 font-medium transition-opacity duration-200"
                    :class="{ 'opacity-60 pointer-events-none': loading }"
                >
                    <!-- Data Rows -->
                    <tr
                        v-for="(item, index) in items"
                        :key="item.id || index"
                        class="hover:bg-zinc-50/90 dark:hover:bg-zinc-800/40 transition-colors duration-150"
                    >
                        <td
                            v-for="col in columns"
                            :key="col.key"
                            class="px-5 py-3.5 sm:py-4 align-middle"
                            :class="[getAlignClass(col.align), col.class || '']"
                            :style="col.width ? { width: col.width } : {}"
                        >
                            <!-- Named slot per column: #cell-[columnKey] -->
                            <slot
                                :name="`cell-${col.key}`"
                                :item="item"
                                :value="item[col.key]"
                                :index="index"
                            >
                                <slot
                                    name="cell"
                                    :column="col"
                                    :item="item"
                                    :value="item[col.key]"
                                    :index="index"
                                >
                                    {{ item[col.key] ?? '—' }}
                                </slot>
                            </slot>
                        </td>
                    </tr>

                    <!-- Empty State -->
                    <tr v-if="!items || items.length === 0">
                        <td :colspan="columns.length" class="py-14 sm:py-20 text-center">
                            <slot name="empty">
                                <div class="mx-auto flex h-14 w-14 sm:h-16 sm:w-16 items-center justify-center rounded-2xl sm:rounded-3xl bg-zinc-100 dark:bg-zinc-800 text-zinc-400 dark:text-zinc-500 mb-3 sm:mb-3.5 shadow-inner">
                                    <i :class="[emptyIcon, 'text-2xl sm:text-3xl']"></i>
                                </div>
                                <h4 class="text-sm sm:text-base font-bold text-zinc-800 dark:text-zinc-200">
                                    {{ emptyTitle }}
                                </h4>
                                <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1 max-w-sm mx-auto">
                                    {{ emptySubtitle }}
                                </p>
                            </slot>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination Section (Responsive for mobile & desktop) -->
        <div
            v-if="pagination && pagination.links && pagination.links.length > 3"
            class="px-4 sm:px-5 py-3 sm:py-4 border-t border-zinc-100 dark:border-zinc-800 bg-zinc-50/40 dark:bg-zinc-900/40 flex flex-col sm:flex-row items-center justify-between gap-3"
        >
            <div class="text-xs text-zinc-500 dark:text-zinc-400 text-center sm:text-left">
                Showing
                <span class="font-bold text-zinc-800 dark:text-zinc-200">{{ pagination.from ?? 0 }}</span>
                to
                <span class="font-bold text-zinc-800 dark:text-zinc-200">{{ pagination.to ?? 0 }}</span>
                of
                <span class="font-bold text-zinc-800 dark:text-zinc-200">{{ pagination.total }}</span>
                entries
            </div>

            <!-- Page Navigation Buttons with touch friendly targets -->
            <div class="flex flex-wrap items-center justify-center gap-1">
                <template v-for="(link, i) in pagination.links" :key="i">
                    <span
                        v-if="!link.url"
                        class="min-h-[36px] min-w-[36px] inline-flex items-center justify-center px-3 py-1.5 text-xs text-zinc-400 dark:text-zinc-600 rounded-xl cursor-not-allowed select-none border border-transparent"
                        v-html="link.label"
                    ></span>
                    <Link
                        v-else
                        :href="link.url"
                        preserve-scroll
                        class="min-h-[36px] min-w-[36px] inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold rounded-xl transition-all duration-150 active:scale-95"
                        :class="link.active
                            ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20'
                            : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 border border-zinc-200 dark:border-zinc-700/80'"
                        v-html="link.label"
                    />
                </template>
            </div>
        </div>

    </div>
</template>
