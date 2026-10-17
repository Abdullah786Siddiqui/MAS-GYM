<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 dark:bg-rose-600 px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-md shadow-rose-600/25 hover:bg-rose-700 hover:shadow-lg hover:shadow-rose-600/35 focus:outline-none focus:ring-2 focus:ring-rose-500/40 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 active:scale-[0.98] transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed"
    >
        <slot />
    </button>
</template>
