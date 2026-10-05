<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification - MAS Fitness" />

        <div class="flex items-center gap-3 mb-2">
            <div class="h-10 w-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-500">
                <i class="fa-duotone fa-envelope-circle-check text-xl"></i>
            </div>
            <h2 class="text-xl font-extrabold text-zinc-900 dark:text-white">Verify Your Email</h2>
        </div>
        <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-6 leading-relaxed">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just sent to your inbox?
        </p>

        <div v-if="verificationLinkSent" class="mb-6 rounded-xl bg-emerald-500/10 border border-emerald-500/25 p-3 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
            A new verification link has been sent to the email address you provided during registration.
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 py-3 sm:py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-500/20 hover:shadow-emerald-500/35 hover:scale-[1.01] active:scale-[0.99] transition-all disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <i v-if="form.processing" class="fa-duotone fa-spinner-third fa-spin text-base"></i>
                <span>Resend Verification Email</span>
            </button>

            <!-- Back to Login -->
            <p class="text-center text-xs text-zinc-500 dark:text-zinc-400 pt-4 border-t border-zinc-200 dark:border-zinc-800 mt-6">
                Wrong account?
                <Link :href="route('logout')" method="post" as="button" class="font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-500 ml-1 transition">Log Out</Link>
            </p>
        </form>
    </GuestLayout>
</template>
