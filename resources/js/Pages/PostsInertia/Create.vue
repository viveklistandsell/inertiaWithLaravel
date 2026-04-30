<template>
    <Head>
        <title>Create Post — Inertia App</title>
    </Head>

    <div class="max-w-2xl mx-auto">
        <Link href="/postsinertia" class="text-blue-600 hover:underline">&larr; Back to Posts</Link>

        <div class="bg-white rounded-xl shadow border border-gray-100 p-6 mt-4">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Create Post</h1>

            <form @submit.prevent="form.post('/postsinertia')" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Title</label>
                    <input
                        v-model="form.title"
                        type="text"
                        placeholder="Enter post title"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="form.errors.title ? 'border-red-400' : 'border-gray-300'"
                    />
                    <p v-if="form.errors.title" class="text-sm text-red-600 mt-1">{{ form.errors.title }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Author</label>
                    <input
                        v-model="form.author"
                        type="text"
                        placeholder="Author name"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="form.errors.author ? 'border-red-400' : 'border-gray-300'"
                    />
                    <p v-if="form.errors.author" class="text-sm text-red-600 mt-1">{{ form.errors.author }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Content</label>
                    <textarea
                        v-model="form.content"
                        rows="6"
                        placeholder="Write your post here..."
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="form.errors.content ? 'border-red-400' : 'border-gray-300'"
                    />
                    <p v-if="form.errors.content" class="text-sm text-red-600 mt-1">{{ form.errors.content }}</p>
                </div>

                <div class="flex gap-2 pt-2">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2 bg-green-600 text-white font-semibold rounded shadow hover:bg-green-700 disabled:opacity-50 transition"
                    >
                        {{ form.processing ? 'Creating...' : 'Create Post' }}
                    </button>
                    <Link
                        href="/postsinertia"
                        class="px-6 py-2 bg-gray-100 text-gray-700 font-semibold rounded hover:bg-gray-200 transition"
                    >
                        Cancel
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Layout from '../../Shared/Layout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

export default {
    layout: Layout,
    components: { Head, Link },
    setup() {
        const form = useForm({
            title: '',
            author: '',
            content: '',
        });
        return { form };
    },
};
</script>
