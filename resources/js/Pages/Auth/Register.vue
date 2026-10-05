<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const passwordStrength = computed(() => {
    const p = form.password;
    if (!p) return { score: 0, label: '', color: '' };
    let score = 0;
    if (p.length >= 8) score++;
    if (/[A-Z]/.test(p)) score++;
    if (/[0-9]/.test(p)) score++;
    if (/[^A-Za-z0-9]/.test(p)) score++;
    if (score <= 1) return { score, label: 'Weak', color: 'bg-rose-500', percent: 25 };
    if (score === 2) return { score, label: 'Fair', color: 'bg-amber-500', percent: 50 };
    if (score === 3) return { score, label: 'Good', color: 'bg-teal-500', percent: 75 };
    return { score, label: 'Strong', color: 'bg-emerald-500', percent: 100 };
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Create Account - MAS Fitness" />

        <h2 class="text-xl font-extrabold text-zinc-900 dark:text-white mb-1">Create Account</h2>
        <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-6">Start your fitness transformation today</p>

        <form @submit.prevent="submit" class="space-y-4">
            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">Full Name</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-zinc-400 dark:text-zinc-500">
                        <i class="fa-duotone fa-user text-base"></i>
                    </span>
                    <input
                        id="name" type="text" v-model="form.name"
                        required autofocus autocomplete="name"
                        placeholder="John Doe"
                        class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-950 pl-10 pr-4 py-2.5 sm:py-2.5 text-base sm:text-sm text-zinc-800 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition"
                    />
                </div>
                <InputError class="mt-1.5" :message="form.errors.name" />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">Email</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-zinc-400 dark:text-zinc-500">
                        <i class="fa-duotone fa-envelope text-base"></i>
                    </span>
                    <input
                        id="email" type="email" v-model="form.email"
                        required autocomplete="username"
                        placeholder="you@example.com"
                        class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-950 pl-10 pr-4 py-2.5 sm:py-2.5 text-base sm:text-sm text-zinc-800 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition"
                    />
                </div>
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">Password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-zinc-400 dark:text-zinc-500">
                        <i class="fa-duotone fa-lock-keyhole text-base"></i>
                    </span>
                    <input
                        id="password" :type="showPassword ? 'text' : 'password'" v-model="form.password"
                        required autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-950 pl-10 pr-10 py-2.5 sm:py-2.5 text-base sm:text-sm text-zinc-800 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition"
                    />
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-3 flex items-center text-zinc-400 dark:text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300 transition">
                        <i v-if="!showPassword" class="fa-duotone fa-eye text-base"></i>
                        <i v-else class="fa-duotone fa-eye-slash text-base"></i>
                    </button>
                </div>
                <!-- Password Strength Indicator -->
                <div v-if="form.password" class="mt-2">
                    <div class="h-1 w-full bg-zinc-200 dark:bg-zinc-800 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-300" :class="passwordStrength.color" :style="{ width: `${passwordStrength.percent}%` }"></div>
                    </div>
                    <p class="text-[10px] text-zinc-500 dark:text-zinc-400 mt-1 text-right font-medium">{{ passwordStrength.label }}</p>
                </div>
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">Confirm Password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-zinc-400 dark:text-zinc-500">
                        <i class="fa-duotone fa-shield-check text-base"></i>
                    </span>
                    <input
                        id="password_confirmation" :type="showConfirmPassword ? 'text' : 'password'" v-model="form.password_confirmation"
                        required autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-950 pl-10 pr-10 py-2.5 sm:py-2.5 text-base sm:text-sm text-zinc-800 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition"
                    />
                    <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-3 flex items-center text-zinc-400 dark:text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300 transition">
                        <i v-if="!showConfirmPassword" class="fa-duotone fa-eye text-base"></i>
                        <i v-else class="fa-duotone fa-eye-slash text-base"></i>
                    </button>
                </div>
                <InputError class="mt-1.5" :message="form.errors.password_confirmation" />
            </div>

            <!-- Submit -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 py-3 sm:py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-500/20 hover:shadow-emerald-500/35 hover:scale-[1.01] active:scale-[0.99] transition-all disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <i v-if="form.processing" class="fa-duotone fa-spinner-third fa-spin text-base"></i>
                <span>Create Account</span>
            </button>

            <!-- Login link -->
            <p class="text-center text-xs text-zinc-500 dark:text-zinc-400 pt-3 border-t border-zinc-200 dark:border-zinc-800">
                Already registered?
                <Link :href="route('login')" class="font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-500 ml-1 transition">Sign in</Link>
            </p>
        </form>
    </GuestLayout>
</template>
