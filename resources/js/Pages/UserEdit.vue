<template>
    <Head>
        <title>Edit User #{{ user.id }} — Inertia App</title>
    </Head>

    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <Link :href="`/user${buildQuery()}`" class="text-blue-600 hover:underline">&larr; Back to Users</Link>
        </div>

        <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
            <div class="flex items-center gap-4 mb-6 pb-4 border-b">
                <div
                    class="w-14 h-14 flex items-center justify-center rounded-full text-white font-bold text-lg shadow"
                    style="background: linear-gradient(135deg,#667eea,#764ba2)"
                >
                    {{ initials(form.name) }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Edit User</h1>
                    <p class="text-sm text-gray-500">User ID: #{{ user.id }}</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">ID</label>
                    <input
                        type="text"
                        :value="user.id"
                        readonly
                        class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded text-gray-500 cursor-not-allowed"
                    />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="form.errors.name ? 'border-red-400' : 'border-gray-300'"
                    />
                    <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="form.errors.email ? 'border-red-400' : 'border-gray-300'"
                    />
                    <p v-if="form.errors.email" class="text-sm text-red-600 mt-1">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                    <input
                        v-model="form.phone"
                        type="text"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="form.errors.phone ? 'border-red-400' : 'border-gray-300'"
                    />
                    <p v-if="form.errors.phone" class="text-sm text-red-600 mt-1">{{ form.errors.phone }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
                    <textarea
                        v-model="form.address"
                        rows="3"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="form.errors.address ? 'border-red-400' : 'border-gray-300'"
                    />
                    <p v-if="form.errors.address" class="text-sm text-red-600 mt-1">{{ form.errors.address }}</p>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded shadow hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
                    </button>
                    <Link
                        :href="`/user${buildQuery()}`"
                        class="px-6 py-2 bg-gray-100 text-gray-700 font-semibold rounded hover:bg-gray-200 transition"
                    >
                        Cancel
                    </Link>
                    <span v-if="form.recentlySuccessful" class="text-sm text-green-600">Saved!</span>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Layout from '../Shared/Layout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

export default {
    layout: Layout,
    components: { Head, Link },
    props: {
        user: Object,
        filters: { type: Object, default: () => ({}) },
    },
    setup(props) {
        const form = useForm({
            name: props.user.name,
            email: props.user.email,
            phone: props.user.phone,
            address: props.user.address,
        });

        const buildQuery = () => {
            const params = new URLSearchParams();
            for (const [k, v] of Object.entries(props.filters)) {
                if (v !== null && v !== undefined && v !== '') params.set(k, v);
            }
            const qs = params.toString();
            return qs ? `?${qs}` : '';
        };

        const submit = () => {
            form.put(`/user/${props.user.id}${buildQuery()}`);
        };

        return { form, submit, buildQuery };
    },
    methods: {
        initials(name) {
            if (!name) return '?';
            return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
        },
    },
};
</script>
