<script setup>
defineProps({
    size: {
        type: String,
        default: 'md', // sm, md, lg
    },
    subtitle: {
        type: String,
        default: 'FITNESS TRACKER',
    }
});
</script>

<template>
    <div class="inline-flex items-center gap-3 select-none group">
        <!-- Logo Icon -->
        <div
            class="relative flex items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-500 text-white font-black shadow-lg shadow-emerald-500/20 transition-all duration-300 group-hover:shadow-emerald-500/40 group-hover:scale-105"
            :class="{
                'w-9 h-9': size === 'sm',
                'w-11 h-11': size === 'md',
                'w-14 h-14': size === 'lg',
            }"
        >
            <i
                class="fa-duotone fa-dumbbell transition-transform duration-300 group-hover:rotate-12"
                :class="{ 'text-sm': size === 'sm', 'text-base': size === 'md', 'text-xl': size === 'lg' }"
            ></i>
        </div>

        <!-- Text -->
        <div class="flex flex-col">
            <div class="flex items-center gap-0.5 leading-none">
                <span class="font-extrabold tracking-tight text-zinc-800 dark:text-white uppercase"
                    :class="{ 'text-base': size === 'sm', 'text-xl': size === 'md', 'text-2xl': size === 'lg' }">
                    MAS
                </span>
                <span class="font-extrabold tracking-tight text-emerald-500 uppercase"
                    :class="{ 'text-base': size === 'sm', 'text-xl': size === 'md', 'text-2xl': size === 'lg' }">
                    FITNESS
                </span>
            </div>
            <span class="text-[10px] tracking-widest font-semibold uppercase text-zinc-400 dark:text-zinc-500 mt-0.5">
                {{ subtitle }}
            </span>
        </div>
    </div>
</template>
