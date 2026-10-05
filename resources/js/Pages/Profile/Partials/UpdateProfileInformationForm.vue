<script setup>
import InputError from '@/Components/InputError.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header class="mb-5 sm:mb-6">
            <div class="flex items-center gap-2">
                <i class="fa-duotone fa-circle-user text-emerald-500"></i>
                <h3 class="text-base font-bold text-zinc-900 dark:text-white">Profile Information</h3>
            </div>
            <p class="mt-1 text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">Update your name and email address.</p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-4 sm:space-y-5">
            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">Name</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-zinc-400 dark:text-zinc-500">
                        <i class="fa-duotone fa-user text-base"></i>
                    </span>
                    <input
                        id="name" type="text" v-model="form.name"
                        required autofocus autocomplete="name"
                        class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-950 pl-10 pr-4 py-2.5 sm:py-2.5 text-base sm:text-sm text-zinc-800 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition"
                    />
                </div>
                <InputError class="mt-1.5" :message="form.errors.name" />
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">Email</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-zinc-400 dark:text-zinc-500">
                        <i class="fa-duotone fa-envelope text-base"></i>
                    </span>
                    <input
                        id="email" type="email" v-model="form.email"
                        required autocomplete="username"
                        class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-950 pl-10 pr-4 py-2.5 sm:py-2.5 text-base sm:text-sm text-zinc-800 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition"
                    />
                </div>
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                    Your email is unverified.
                    <Link :href="route('verification.send')" method="post" as="button"
                        class="text-emerald-600 dark:text-emerald-400 underline hover:text-emerald-500 transition">
                        Resend verification email.
                    </Link>
                </p>
                <div v-show="status === 'verification-link-sent'" class="mt-2 text-xs font-semibold text-emerald-500">
                    Verification link sent!
                </div>
            </div>

            <div class="flex items-center gap-4 pt-1">
                <button type="submit" :disabled="form.processing"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-500/20 hover:scale-[1.01] active:scale-[0.99] transition-all disabled:opacity-50">
                    <i v-if="form.processing" class="fa-duotone fa-spinner-third fa-spin text-sm"></i>
                    <i v-else class="fa-duotone fa-floppy-disk text-sm"></i>
                    <span>Save Changes</span>
                </button>
                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-xs font-semibold text-emerald-500">Saved!</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
