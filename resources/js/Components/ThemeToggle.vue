<script setup>
import { onMounted } from 'vue';
import { useTheme } from '@/Composables/useTheme.js';

const { isDark, toggleTheme, initTheme } = useTheme();

onMounted(() => {
    initTheme();
});
</script>

<template>
    <button
        @click="toggleTheme"
        type="button"
        aria-label="Toggle Dark/Light Mode"
        class="relative inline-flex items-center justify-center w-10 h-10 rounded-xl border transition-all duration-200 focus:outline-none active:scale-95 group"
        :class="isDark
            ? 'border-zinc-700/80 bg-zinc-900 text-amber-400 hover:border-zinc-600 hover:bg-zinc-800'
            : 'border-zinc-200 bg-white text-amber-500 hover:border-zinc-300 hover:bg-zinc-50 shadow-sm'"
        :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
    >
        <!-- Sun icon (shown in dark mode — click to go light) -->
        <i
            v-if="isDark"
            class="fa-duotone fa-sun-bright text-lg transition-transform duration-300 group-hover:rotate-45"
            style="--fa-primary-color: #fbbf24; --fa-secondary-color: #f59e0b; --fa-secondary-opacity: 0.6;"
        ></i>
        <!-- Moon icon (shown in light mode — click to go dark) -->
        <i
            v-else
            class="fa-duotone fa-moon-stars text-lg transition-transform duration-300 group-hover:-rotate-12"
            style="--fa-primary-color: #4f46e5; --fa-secondary-color: #818cf8; --fa-secondary-opacity: 0.7;"
        ></i>
    </button>
</template>
