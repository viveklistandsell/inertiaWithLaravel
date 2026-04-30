<template>
    <Head>
        <title>Login — Inertia App</title>
    </Head>

    <div class="min-h-[70vh] flex items-center justify-center">
        <div class="w-full max-w-md bg-white rounded-xl shadow border border-gray-100 p-8">
            <div class="text-center mb-6">
                <div
                    class="w-14 h-14 mx-auto flex items-center justify-center rounded-full text-white font-bold text-xl shadow"
                    style="background: linear-gradient(135deg,#667eea,#764ba2)"
                >
                    &#128274;
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mt-3">Sign in</h1>
                <p class="text-sm text-gray-500">Use your account to continue</p>
            </div>

            <div v-if="$page.props.flash?.error" class="mb-4 p-3 bg-red-50 text-red-700 border border-red-200 rounded text-sm">
                {{ $page.props.flash.error }}
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        autocomplete="email"
                        autofocus
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="form.errors.email ? 'border-red-400' : 'border-gray-300'"
                    />
                    <p v-if="form.errors.email" class="text-sm text-red-600 mt-1">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input
                        v-model="form.password"
                        type="password"
                        autocomplete="current-password"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="form.errors.password ? 'border-red-400' : 'border-gray-300'"
                    />
                    <p v-if="form.errors.password" class="text-sm text-red-600 mt-1">{{ form.errors.password }}</p>
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input v-model="form.remember" type="checkbox" class="rounded" />
                    Remember me
                </label>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded shadow hover:bg-blue-700 disabled:opacity-50 transition"
                >
                    {{ form.processing ? 'Signing in...' : 'Sign in' }}
                </button>
            </form>

            <div class="mt-6 p-3 bg-gray-50 border border-gray-200 rounded text-xs text-gray-600">
                <p class="font-semibold mb-1">Demo accounts (password: <code>password</code>):</p>
                <p>admin@example.com — full access</p>
                <p>user@example.com — read only</p>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from '../Shared/Layout.vue';
import { Head, useForm } from '@inertiajs/vue3';

export default {
    layout: Layout,
    components: { Head },
    setup() {
        const form = useForm({
            email: '',
            password: '',
            remember: false,
        });

        const submit = () => form.post('/login', { onFinish: () => form.password = '' });

        return { form, submit };
    },
};
</script>
