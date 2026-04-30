<template>
    <Head>
        <title>Posts (Inertia) — Inertia App</title>
    </Head>

    <div class="flex items-end justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Posts (Inertia)</h1>
            <p class="text-sm text-gray-500 mt-1">{{ posts.total }} posts total</p>
        </div>
        <Link
            href="/postsinertia/create"
            class="px-4 py-2 text-sm font-semibold bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition"
        >
            + Create Post
        </Link>
    </div>

    <div class="bg-white rounded-xl shadow border border-gray-100 divide-y">
        <div v-if="posts.data.length === 0" class="p-8 text-center text-gray-500">
            No posts yet. Click "Create Post" above.
        </div>
        <div
            v-for="post in posts.data"
            :key="post.id"
            class="p-4 flex items-start justify-between gap-4 hover:bg-gray-50"
        >
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <p class="text-xs text-gray-400">#{{ post.id }}</p>
                    <span class="text-xs text-gray-500">by <span class="font-semibold">{{ post.author }}</span></span>
                </div>
                <h3 class="font-semibold text-gray-800">{{ post.title }}</h3>
                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ post.content }}</p>
            </div>
            <div class="flex gap-2 flex-shrink-0">
                <Link
                    :href="`/postsinertia/${post.id}/edit`"
                    class="px-3 py-1 text-xs font-semibold bg-blue-50 text-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition"
                >
                    Edit
                </Link>
                <button
                    @click="remove(post)"
                    class="px-3 py-1 text-xs font-semibold bg-red-50 text-red-600 rounded-full hover:bg-red-600 hover:text-white transition"
                >
                    Delete
                </button>
            </div>
        </div>
    </div>

    <Pagination :links="posts.links" />
</template>

<script>
import Layout from '../../Shared/Layout.vue';
import Pagination from '../../Shared/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';

export default {
    layout: Layout,
    components: { Head, Link, Pagination },
    props: {
        posts: Object,
    },
    methods: {
        remove(post) {
            if (!confirm(`Delete post "${post.title}"?`)) return;
            router.delete(`/postsinertia/${post.id}`, { preserveScroll: true });
        },
    },
};
</script>
