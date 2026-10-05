<script setup>
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header class="mb-5 sm:mb-6">
            <div class="flex items-center gap-2">
                <i class="fa-duotone fa-shield-keyhole text-emerald-500"></i>
                <h3 class="text-base font-bold text-zinc-900 dark:text-white">Update Password</h3>
            </div>
            <p class="mt-1 text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">Ensure your account is using a long, random password to stay secure.</p>
        </header>

        <form @submit.prevent="updatePassword" class="space-y-4 sm:space-y-5">
            <div>
                <label for="current_password" class="block text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">Current Password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-zinc-400 dark:text-zinc-500">
                        <i class="fa-duotone fa-lock-keyhole text-base"></i>
                    </span>
                    <input
                        id="current_password" ref="currentPasswordInput"
                        v-model="form.current_password" type="password"
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-950 pl-10 pr-4 py-2.5 sm:py-2.5 text-base sm:text-sm text-zinc-800 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition"
                    />
                </div>
                <InputError :message="form.errors.current_password" class="mt-1.5" />
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">New Password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-zinc-400 dark:text-zinc-500">
                        <i class="fa-duotone fa-lock-keyhole text-base"></i>
                    </span>
                    <input
                        id="password" ref="passwordInput"
                        v-model="form.password" type="password"
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-950 pl-10 pr-4 py-2.5 sm:py-2.5 text-base sm:text-sm text-zinc-800 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition"
                    />
                </div>
                <InputError :message="form.errors.password" class="mt-1.5" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">Confirm Password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-zinc-400 dark:text-zinc-500">
                        <i class="fa-duotone fa-shield-check text-base"></i>
                    </span>
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation" type="password"
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-950 pl-10 pr-4 py-2.5 sm:py-2.5 text-base sm:text-sm text-zinc-800 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition"
                    />
                </div>
                <InputError :message="form.errors.password_confirmation" class="mt-1.5" />
            </div>

            <div class="flex items-center gap-4 pt-1">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-500/20 hover:scale-[1.01] active:scale-[0.99] transition-all disabled:opacity-50"
                >
                    <i v-if="form.processing" class="fa-duotone fa-spinner-third fa-spin text-sm"></i>
                    <i v-else class="fa-duotone fa-key text-sm"></i>
                    <span>Update Password</span>
                </button>
                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-xs font-semibold text-emerald-500">Updated!</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
