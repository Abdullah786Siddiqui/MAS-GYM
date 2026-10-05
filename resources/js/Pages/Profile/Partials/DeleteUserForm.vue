<script setup>
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({ password: '' });

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-4 sm:space-y-5">
        <header>
            <div class="flex items-center gap-2">
                <i class="fa-duotone fa-triangle-exclamation text-rose-500"></i>
                <h3 class="text-base font-bold text-rose-500">Delete Account</h3>
            </div>
            <p class="mt-1 text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">
                Once deleted, all your workout history and account data will be permanently removed. This cannot be undone.
            </p>
        </header>

        <button
            type="button"
            @click="confirmUserDeletion"
            class="inline-flex items-center gap-2 rounded-xl border border-rose-500/40 bg-rose-500/10 px-5 py-2.5 text-sm font-bold text-rose-500 hover:bg-rose-500/20 hover:border-rose-500/60 transition active:scale-95"
        >
            <i class="fa-duotone fa-trash-can"></i>
            <span>Delete Account</span>
        </button>

        <!-- Confirmation Modal -->
        <Transition
            enter-active-class="transition-opacity duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="confirmingUserDeletion" class="fixed inset-0 z-50 flex items-center justify-center p-3.5 sm:p-4">
                <div class="fixed inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm" @click="closeModal"></div>

                <div class="relative w-full max-w-md rounded-2xl sm:rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 sm:p-6 shadow-2xl z-10 transition-colors duration-300">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-500">
                            <i class="fa-duotone fa-trash-can text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-zinc-900 dark:text-white">Delete Account?</h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Confirm permanent account deletion</p>
                        </div>
                    </div>

                    <p class="mt-3 text-xs sm:text-sm text-zinc-600 dark:text-zinc-400">
                        Please enter your password to confirm you want to permanently delete your account.
                    </p>

                    <div class="mt-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">Password</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-zinc-400 dark:text-zinc-500">
                                <i class="fa-duotone fa-lock-keyhole text-base"></i>
                            </span>
                            <input
                                id="delete-password" ref="passwordInput"
                                v-model="form.password" type="password"
                                placeholder="••••••••"
                                @keyup.enter="deleteUser"
                                class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-950 pl-10 pr-4 py-2.5 sm:py-2.5 text-base sm:text-sm text-zinc-800 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition"
                            />
                        </div>
                        <InputError :message="form.errors.password" class="mt-1.5" />
                    </div>

                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2 sm:gap-3">
                        <button
                            type="button"
                            @click="closeModal"
                            class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 px-4 py-2.5 text-sm font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 hover:text-zinc-900 dark:hover:text-white transition active:scale-95"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            @click="deleteUser"
                            :disabled="form.processing"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-rose-600/20 hover:bg-rose-500 hover:scale-[1.01] active:scale-[0.99] transition-all disabled:opacity-50"
                        >
                            <i v-if="form.processing" class="fa-duotone fa-spinner-third fa-spin text-sm"></i>
                            <i v-else class="fa-duotone fa-trash-can text-sm"></i>
                            <span>Confirm Delete</span>
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </section>
</template>
