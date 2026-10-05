<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
});
</script>

<template>
    <Head title="Profile Settings" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2.5">
                <i class="fa-duotone fa-user-gear text-emerald-500 text-lg"></i>
                <h2 class="text-lg font-bold text-zinc-800 dark:text-white tracking-tight">Profile Settings</h2>
            </div>
        </template>

        <div class="py-6 sm:py-10">
            <div class="mx-auto max-w-3xl px-3.5 sm:px-6 lg:px-8 space-y-5 sm:space-y-6">

                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4 sm:p-6 shadow-sm dark:shadow-lg transition-colors duration-300">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4 sm:p-6 shadow-sm dark:shadow-lg transition-colors duration-300">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="rounded-2xl border border-rose-200 dark:border-rose-900/40 bg-white dark:bg-zinc-900 p-4 sm:p-6 shadow-sm dark:shadow-lg transition-colors duration-300">
                    <DeleteUserForm class="max-w-xl" />
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
