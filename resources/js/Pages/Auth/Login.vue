<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Sign In - MAS Fitness" />

        <h2 class="text-xl font-extrabold text-zinc-900 dark:text-white mb-1">Welcome Back</h2>
        <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-6">Sign in to access your workout dashboard</p>

        <div v-if="status" class="mb-4 rounded-xl bg-emerald-500/10 border border-emerald-500/25 p-3 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
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

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-400">Password</label>
                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-500 transition">
                        Forgot password?
                    </Link>
                </div>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-zinc-400 dark:text-zinc-500">
                        <i class="fa-duotone fa-lock-keyhole text-base"></i>
                    </span>
                    <input
                        id="password" :type="showPassword ? 'text' : 'password'" v-model="form.password"
                        required autocomplete="current-password"
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

            <!-- Remember Me -->
            <label class="flex items-center gap-2.5 py-1 cursor-pointer select-none">
                <input type="checkbox" v-model="form.remember"
                    class="h-4 w-4 rounded border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-950 text-emerald-500 focus:ring-emerald-500/20 cursor-pointer"
                />
                <span class="text-xs text-zinc-600 dark:text-zinc-400 font-medium">Keep me signed in</span>
            </label>

            <!-- Submit -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 py-3 sm:py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-500/20 hover:shadow-emerald-500/35 hover:scale-[1.01] active:scale-[0.99] transition-all disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <i v-if="form.processing" class="fa-duotone fa-spinner-third fa-spin text-base"></i>
                <span>Sign In</span>
            </button>

            <!-- Register link -->
            <p class="text-center text-xs text-zinc-500 dark:text-zinc-400 pt-3 border-t border-zinc-200 dark:border-zinc-800">
                No account yet?
                <Link :href="route('register')" class="font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-500 ml-1 transition">Create one</Link>
            </p>
        </form>
    </GuestLayout>
</template>
