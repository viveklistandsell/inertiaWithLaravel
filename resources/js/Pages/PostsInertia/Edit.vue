<template>
    <Head>
        <title>Edit Post #{{ post.id }} — Inertia App</title>
    </Head>

    <div class="max-w-2xl mx-auto">
        <Link href="/postsinertia" class="text-blue-600 hover:underline">&larr; Back to Posts</Link>

        <div class="bg-white rounded-xl shadow border border-gray-100 p-6 mt-4">
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Edit Post</h1>
            <p class="text-sm text-gray-500 mb-6">Post #{{ post.id }}</p>

            <form @submit.prevent="form.put(`/postsinertia/${post.id}`)" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Title</label>
                    <input
                        v-model="form.title"
                        type="text"
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
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="form.errors.content ? 'border-red-400' : 'border-gray-300'"
                    />
                    <p v-if="form.errors.content" class="text-sm text-red-600 mt-1">{{ form.errors.content }}</p>
                </div>

                <div class="flex gap-2 pt-2">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded shadow hover:bg-blue-700 disabled:opacity-50 transition"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
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
    components: { Head, Link, useForm },
    props: {
        post: Object,
    },
    setup(props) {
        const form = useForm({
            title: props.post.title,
            author: props.post.author,
            content: props.post.content,
        });
        return { form };
    },
};
</script>
