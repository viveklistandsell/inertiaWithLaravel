<template>
    <Head>
        <title>Create User — Inertia App</title>
    </Head>

    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <Link href="/user" class="text-blue-600 hover:underline">&larr; Back to Users</Link>
        </div>

        <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6 pb-4 border-b">
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 flex items-center justify-center rounded-full text-white font-bold text-lg shadow"
                        style="background: linear-gradient(135deg,#43e97b,#38f9d7)"
                    >
                        +
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Create New User</h1>
                        <p class="text-sm text-gray-500">Demo of Inertia's <code>useForm</code> helper.</p>
                    </div>
                </div>
                <span v-if="form.isDirty" class="px-2 py-1 text-xs font-semibold bg-amber-100 text-amber-700 rounded-full">
                    Unsaved changes
                </span>
            </div>

            <div v-if="form.progress" class="mb-4">
                <div class="h-1 bg-gray-200 rounded overflow-hidden">
                    <div class="h-full bg-blue-500 transition-all" :style="{ width: form.progress.percentage + '%' }" />
                </div>
                <p class="text-xs text-gray-500 mt-1">Submitting... {{ form.progress.percentage }}%</p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="form.name"
                        @input="form.clearErrors('name')"
                        type="text"
                        placeholder="John Doe"
                        autocomplete="name"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="form.errors.name ? 'border-red-400' : 'border-gray-300'"
                    />
                    <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input
                            v-model="form.email"
                            @input="onEmailInput"
                            type="email"
                            placeholder="john@example.com"
                            autocomplete="email"
                            class="w-full px-3 py-2 pr-28 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                            :class="emailBorderClass"
                        />
                        <span
                            v-if="emailStatus !== 'idle'"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-xs font-semibold"
                            :class="emailStatusClass"
                        >
                            {{ emailStatusLabel }}
                        </span>
                    </div>
                    <p v-if="form.errors.email" class="text-sm text-red-600 mt-1">{{ form.errors.email }}</p>
                    <p v-else-if="emailStatus === 'taken'" class="text-sm text-red-600 mt-1">
                        This email is already registered.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                    <input
                        v-model="form.phone"
                        @input="form.clearErrors('phone')"
                        type="text"
                        placeholder="+1 555 123 4567"
                        autocomplete="tel"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="form.errors.phone ? 'border-red-400' : 'border-gray-300'"
                    />
                    <p v-if="form.errors.phone" class="text-sm text-red-600 mt-1">{{ form.errors.phone }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
                    <textarea
                        v-model="form.address"
                        @input="form.clearErrors('address')"
                        rows="3"
                        placeholder="Street, City, State, ZIP"
                        autocomplete="street-address"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="form.errors.address ? 'border-red-400' : 'border-gray-300'"
                    />
                    <p v-if="form.errors.address" class="text-sm text-red-600 mt-1">{{ form.errors.address }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.password"
                            @input="form.clearErrors('password')"
                            type="password"
                            placeholder="At least 6 characters"
                            autocomplete="new-password"
                            class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                            :class="form.errors.password ? 'border-red-400' : 'border-gray-300'"
                        />
                        <p v-if="form.errors.password" class="text-sm text-red-600 mt-1">{{ form.errors.password }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            placeholder="Repeat password"
                            autocomplete="new-password"
                            class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400 border-gray-300"
                        />
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button
                        v-if="!form.processing"
                        type="submit"
                        :disabled="!form.isDirty"
                        class="px-6 py-2 bg-green-600 text-white font-semibold rounded shadow hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
                    >
                        Create User
                    </button>
                    <button
                        v-else
                        type="button"
                        @click="form.cancel()"
                        class="px-6 py-2 bg-red-500 text-white font-semibold rounded shadow hover:bg-red-600 transition flex items-center gap-2"
                    >
                        <span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" />
                        Cancel Request
                    </button>

                    <button
                        type="button"
                        @click="form.reset()"
                        :disabled="form.processing"
                        class="px-6 py-2 bg-gray-100 text-gray-700 font-semibold rounded hover:bg-gray-200 disabled:opacity-50 transition"
                    >
                        Clear
                    </button>

                    <Link
                        href="/user"
                        class="px-6 py-2 text-gray-600 font-semibold hover:text-gray-800 transition"
                    >
                        Back
                    </Link>

                    <Transition
                        enter-active-class="transition ease-out duration-200"
                        enter-from-class="opacity-0 translate-x-2"
                        enter-to-class="opacity-100 translate-x-0"
                        leave-active-class="transition ease-in duration-150"
                        leave-from-class="opacity-100"
                        leave-to-class="opacity-0"
                    >
                        <span v-if="form.recentlySuccessful" class="text-sm text-green-600 font-semibold">
                            &#10003; User created!
                        </span>
                    </Transition>
                </div>

                <div v-if="hasErrors" class="mt-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                    <strong>Please fix {{ Object.keys(form.errors).length }} error(s) above.</strong>
                </div>
            </form>
        </div>

        <details class="mt-6 bg-gray-50 rounded-lg border border-gray-200 p-4 text-xs text-gray-600">
            <summary class="cursor-pointer font-semibold text-gray-700">Form Helper state (live)</summary>
            <pre class="mt-2 overflow-x-auto">{{ debugState }}</pre>
        </details>
    </div>
</template>

<script>
import Layout from '../Shared/Layout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { debounce, throttle } from '../Composables/useDebounce.js';

export default {
    layout: Layout,
    components: { Head, Link },
    setup() {
        const form = useForm({
            name: '',
            email: '',
            phone: '',
            address: '',
            password: '',
            password_confirmation: '',
        });

        const emailStatus = ref('idle');

        const checkEmailAvailability = debounce(async (email) => {
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                emailStatus.value = 'idle';
                return;
            }
            emailStatus.value = 'checking';
            try {
                const res = await fetch(`/user/check-email?email=${encodeURIComponent(email)}`);
                const data = await res.json();
                if (data.email !== form.email) return;
                emailStatus.value = data.available ? 'available' : 'taken';
            } catch (e) {
                emailStatus.value = 'idle';
            }
        }, 500);

        const onEmailInput = () => {
            form.clearErrors('email');
            emailStatus.value = 'pending';
            checkEmailAvailability(form.email.trim());
        };

        const emailStatusLabel = computed(() => ({
            pending: '...',
            checking: 'Checking…',
            available: '✓ Available',
            taken: '✗ Taken',
        }[emailStatus.value] ?? ''));

        const emailStatusClass = computed(() => ({
            pending: 'text-gray-400',
            checking: 'text-blue-500',
            available: 'text-green-600',
            taken: 'text-red-600',
        }[emailStatus.value] ?? ''));

        const emailBorderClass = computed(() => {
            if (form.errors.email || emailStatus.value === 'taken') return 'border-red-400';
            if (emailStatus.value === 'available') return 'border-green-400';
            return 'border-gray-300';
        });

        const doSubmit = () => {
            form
                .transform((data) => ({
                    ...data,
                    name: data.name.trim(),
                    email: data.email.trim().toLowerCase(),
                    phone: data.phone.trim(),
                    address: data.address.trim(),
                }))
                .post('/user', {
                    preserveScroll: true,
                    onBefore: () => {
                        if (!form.isDirty) {
                            return false;
                        }
                    },
                    onSuccess: () => {
                        form.reset();
                    },
                    onError: (errors) => {
                        const firstField = Object.keys(errors)[0];
                        if (firstField) {
                            const el = document.querySelector(`[name="${firstField}"], [v-model="form.${firstField}"]`);
                            el?.focus?.();
                        }
                    },
                    onFinish: () => {
                        form.password = '';
                        form.password_confirmation = '';
                    },
                });
        };

        const submit = throttle(doSubmit, 1000);

        const hasErrors = computed(() => Object.keys(form.errors).length > 0);

        const debugState = computed(() => JSON.stringify({
            isDirty: form.isDirty,
            processing: form.processing,
            wasSuccessful: form.wasSuccessful,
            recentlySuccessful: form.recentlySuccessful,
            emailStatus: emailStatus.value,
            hasErrors: hasErrors.value,
            errorCount: Object.keys(form.errors).length,
            data: { ...form.data() },
        }, null, 2));

        return {
            form,
            submit,
            hasErrors,
            debugState,
            emailStatus,
            emailStatusLabel,
            emailStatusClass,
            emailBorderClass,
            onEmailInput,
        };
    },
};
</script>
