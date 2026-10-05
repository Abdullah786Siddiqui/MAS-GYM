<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="flex w-full items-center px-4 py-2 text-start text-sm leading-5 text-zinc-600 dark:text-zinc-300 transition duration-150 ease-in-out hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white focus:bg-zinc-100 dark:focus:bg-zinc-800 focus:outline-none rounded-lg"
    >
        <slot />
    </Link>
</template>
