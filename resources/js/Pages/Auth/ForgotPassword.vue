<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password - MAS Fitness" />

        <h2 class="text-xl font-extrabold text-zinc-900 dark:text-white mb-1">Forgot Password?</h2>
        <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-6 leading-relaxed">
            No problem. Enter your email and we'll send you a password reset link to regain access.
        </p>

        <div v-if="status" class="mb-5 rounded-xl bg-emerald-500/10 border border-emerald-500/25 p-3 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">Email</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-zinc-400 dark:text-zinc-500">
                        <i class="fa-duotone fa-envelope text-base"></i>
                    </span>
                    <input
                        id="email" type="email" v-model="form.email"
                        required autofocus autocomplete="username"
                        placeholder="you@example.com"
                        class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-950 pl-10 pr-4 py-2.5 sm:py-2.5 text-base sm:text-sm text-zinc-800 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition"
                    />
                </div>
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <!-- Submit -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 py-3 sm:py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-500/20 hover:shadow-emerald-500/35 hover:scale-[1.01] active:scale-[0.99] transition-all disabled:opacity-50 disabled:cursor-not-allowed mt-2"
            >
                <i v-if="form.processing" class="fa-duotone fa-spinner-third fa-spin text-base"></i>
                <span>Email Password Reset Link</span>
            </button>

            <!-- Back to Login -->
            <p class="text-center text-xs text-zinc-500 dark:text-zinc-400 pt-4 border-t border-zinc-200 dark:border-zinc-800 mt-6">
                Remember your password?
                <Link :href="route('login')" class="font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-500 ml-1 transition">Sign in</Link>
            </p>
        </form>
    </GuestLayout>
</template>
