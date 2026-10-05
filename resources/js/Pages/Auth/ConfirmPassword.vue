<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    password: '',
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password - MAS Fitness" />

        <h2 class="text-xl font-extrabold text-zinc-900 dark:text-white mb-1">Confirm Password</h2>
        <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-6 leading-relaxed">
            This is a secure area of the application. Please confirm your password before continuing.
        </p>

        <form @submit.prevent="submit" class="space-y-4">
            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">Password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-zinc-400 dark:text-zinc-500">
                        <i class="fa-duotone fa-lock-keyhole text-base"></i>
                    </span>
                    <input
                        id="password" :type="showPassword ? 'text' : 'password'" v-model="form.password"
                        required autocomplete="current-password" autofocus
                        placeholder="••••••••"
                        class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-950 pl-10 pr-10 py-2.5 sm:py-2.5 text-base sm:text-sm text-zinc-800 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition"
                    />
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-3 flex items-center text-zinc-400 dark:text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300 transition">
                        <i v-if="!showPassword" class="fa-duotone fa-eye text-base"></i>
                        <i v-else class="fa-duotone fa-eye-slash text-base"></i>
                    </button>
                </div>
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <!-- Submit -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 py-3 sm:py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-500/20 hover:shadow-emerald-500/35 hover:scale-[1.01] active:scale-[0.99] transition-all disabled:opacity-50 disabled:cursor-not-allowed mt-2"
            >
                <i v-if="form.processing" class="fa-duotone fa-spinner-third fa-spin text-base"></i>
                <span>Confirm</span>
            </button>
        </form>
    </GuestLayout>
</template>
