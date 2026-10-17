<script setup>
defineProps({
    stats: {
        type: Array,
        required: true,
        // Each item: { title, value, icon, color?: 'emerald'|'cyan'|'amber'|'zinc'|'indigo'|'rose', pulse?: boolean, subtitle?: string }
    },
});

const getColorStyles = (color = 'emerald') => {
    switch (color) {
        case 'cyan':
            return {
                bgBadge: 'bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border-cyan-500/20',
                glow: 'bg-cyan-500/10',
                borderHover: 'hover:border-cyan-500/40',
                dot: 'bg-cyan-500',
                accentLine: 'from-cyan-500 to-blue-500',
            };
        case 'amber':
            return {
                bgBadge: 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
                glow: 'bg-amber-500/10',
                borderHover: 'hover:border-amber-500/40',
                dot: 'bg-amber-500',
                accentLine: 'from-amber-500 to-orange-500',
            };
        case 'rose':
            return {
                bgBadge: 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20',
                glow: 'bg-rose-500/10',
                borderHover: 'hover:border-rose-500/40',
                dot: 'bg-rose-500',
                accentLine: 'from-rose-500 to-pink-500',
            };
        case 'zinc':
            return {
                bgBadge: 'bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 border-zinc-200 dark:border-zinc-700',
                glow: 'bg-zinc-500/5',
                borderHover: 'hover:border-zinc-300 dark:hover:border-zinc-600',
                dot: 'bg-zinc-400',
                accentLine: 'from-zinc-400 to-zinc-600',
            };
        case 'emerald':
        default:
            return {
                bgBadge: 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
                glow: 'bg-emerald-500/10',
                borderHover: 'hover:border-emerald-500/40',
                dot: 'bg-emerald-500',
                accentLine: 'from-emerald-500 to-teal-500',
            };
    }
};
</script>

<template>
    <!-- Compact, touch-friendly grid on mobile, rich cards on desktop -->
    <div class="grid grid-cols-3 gap-2 sm:gap-4">
        <div
            v-for="(item, idx) in stats"
            :key="idx"
            class="relative overflow-hidden rounded-xl sm:rounded-2xl border border-zinc-200/90 dark:border-zinc-800 bg-white/95 dark:bg-zinc-900/95 p-3 sm:p-5 shadow-xs sm:shadow-sm transition-all duration-300 hover:shadow-md group backdrop-blur-xl flex flex-col sm:flex-row items-center sm:items-center justify-between text-center sm:text-left"
            :class="getColorStyles(item.color).borderHover"
        >
            <!-- Top Subtle Gradient Border Highlight -->
            <div
                class="absolute inset-x-0 top-0 h-0.5 sm:h-1 bg-gradient-to-r opacity-90 transition-opacity"
                :class="getColorStyles(item.color).accentLine"
            ></div>

            <!-- Content -->
            <div class="min-w-0 order-2 sm:order-1 mt-1 sm:mt-0 w-full sm:w-auto">
                <p class="text-[9px] sm:text-[11px] font-extrabold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 truncate">
                    {{ item.title }}
                </p>
                <div class="mt-0.5 flex items-baseline justify-center sm:justify-start gap-1.5 sm:gap-2">
                    <span class="text-lg sm:text-3xl font-black text-zinc-900 dark:text-white tracking-tight">
                        {{ item.value }}
                    </span>
                    <span
                        v-if="item.pulse"
                        class="relative flex h-2 w-2 items-center justify-center"
                    >
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="getColorStyles(item.color).dot"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5" :class="getColorStyles(item.color).dot"></span>
                    </span>
                </div>
                <p v-if="item.subtitle" class="hidden sm:block text-xs text-zinc-500 dark:text-zinc-400 mt-1 font-medium truncate">
                    {{ item.subtitle }}
                </p>
            </div>

            <!-- Duotone Icon Box -->
            <div
                class="flex h-9 w-9 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-xl sm:rounded-2xl border shadow-xs sm:shadow-sm transition-transform duration-300 group-hover:scale-105 order-1 sm:order-2"
                :class="getColorStyles(item.color).bgBadge"
            >
                <i :class="[item.icon, 'text-sm sm:text-xl']"></i>
            </div>
        </div>
    </div>
</template>
