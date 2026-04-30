<template>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Posts CRUD (plain Vue + Laravel API)</h1>

        <div class="bg-white rounded-xl shadow border border-gray-100 p-5 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">
                {{ form.id ? 'Edit Post #' + form.id : 'Create Post' }}
            </h2>
            <form @submit.prevent="save" class="space-y-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Title</label>
                    <input
                        v-model="form.title"
                        type="text"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="errors.title ? 'border-red-400' : 'border-gray-300'"
                    />
                    <p v-if="errors.title" class="text-sm text-red-600 mt-1">{{ errors.title[0] }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Author</label>
                    <input
                        v-model="form.author"
                        type="text"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="errors.author ? 'border-red-400' : 'border-gray-300'"
                    />
                    <p v-if="errors.author" class="text-sm text-red-600 mt-1">{{ errors.author[0] }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Content</label>
                    <textarea
                        v-model="form.content"
                        rows="3"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="errors.content ? 'border-red-400' : 'border-gray-300'"
                    />
                    <p v-if="errors.content" class="text-sm text-red-600 mt-1">{{ errors.content[0] }}</p>
                </div>
                <div class="flex gap-2">
                    <button
                        type="submit"
                        :disabled="saving"
                        class="px-5 py-2 bg-blue-600 text-white font-semibold rounded shadow hover:bg-blue-700 disabled:opacity-50 transition"
                    >
                        {{ saving ? 'Saving...' : (form.id ? 'Update' : 'Create') }}
                    </button>
                    <button
                        v-if="form.id"
                        type="button"
                        @click="resetForm"
                        class="px-5 py-2 bg-gray-100 text-gray-700 font-semibold rounded hover:bg-gray-200 transition"
                    >
                        Cancel
                    </button>
                </div>
                <p v-if="message" class="text-sm text-green-600 font-semibold">{{ message }}</p>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow border border-gray-100">
            <div class="px-5 py-3 border-b flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">All Posts ({{ total }})</h2>
                <button
                    @click="loadPosts"
                    :disabled="loading"
                    class="text-sm text-blue-600 hover:underline disabled:opacity-50"
                >
                    {{ loading ? 'Loading...' : 'Refresh' }}
                </button>
            </div>

            <div v-if="loading && posts.length === 0" class="p-8 text-center text-gray-500">
                Loading posts...
            </div>
            <div v-else-if="posts.length === 0" class="p-8 text-center text-gray-500">
                No posts yet. Create one above!
            </div>
            <ul v-else class="divide-y">
                <li v-for="post in posts" :key="post.id" class="p-4 hover:bg-gray-50 flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-xs text-gray-400">#{{ post.id }}</p>
                            <span class="text-xs text-gray-500">by <span class="font-semibold">{{ post.author }}</span></span>
                        </div>
                        <h3 class="font-semibold text-gray-800">{{ post.title }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ post.content }}</p>
                    </div>
                    <div class="flex gap-2 flex-shrink-0">
                        <button
                            @click="edit(post)"
                            class="px-3 py-1 text-xs font-semibold bg-blue-50 text-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition"
                        >
                            Edit
                        </button>
                        <button
                            @click="remove(post)"
                            class="px-3 py-1 text-xs font-semibold bg-red-50 text-red-600 rounded-full hover:bg-red-600 hover:text-white transition"
                        >
                            Delete
                        </button>
                    </div>
                </li>
            </ul>

            <div v-if="lastPage > 1" class="p-4 flex items-center justify-center gap-2 border-t">
                <button
                    @click="goToPage(currentPage - 1)"
                    :disabled="currentPage === 1 || loading"
                    class="px-3 py-1 text-sm border rounded disabled:opacity-50"
                >
                    &larr; Prev
                </button>
                <span class="text-sm text-gray-600">Page {{ currentPage }} / {{ lastPage }}</span>
                <button
                    @click="goToPage(currentPage + 1)"
                    :disabled="currentPage === lastPage || loading"
                    class="px-3 py-1 text-sm border rounded disabled:opacity-50"
                >
                    Next &rarr;
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            posts: [],
            currentPage: 1,
            lastPage: 1,
            total: 0,
            loading: false,
            saving: false,
            form: { id: null, title: '', author: '', content: '' },
            errors: {},
            message: '',
        };
    },
    mounted() {
        this.loadPosts();
    },
    methods: {
        async loadPosts(page = this.currentPage) {
            this.loading = true;
            try {
                const res = await fetch(`/api/posts?page=${page}`);
                const data = await res.json();
                this.posts = data.data;
                this.currentPage = data.current_page;
                this.lastPage = data.last_page;
                this.total = data.total;
            } finally {
                this.loading = false;
            }
        },
        async save() {
            this.saving = true;
            this.errors = {};
            this.message = '';
            const url = this.form.id ? `/api/posts/${this.form.id}` : '/api/posts';
            const method = this.form.id ? 'PUT' : 'POST';
            try {
                const res = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        title: this.form.title,
                        author: this.form.author,
                        content: this.form.content,
                    }),
                });
                if (res.status === 422) {
                    const data = await res.json();
                    this.errors = data.errors || {};
                    return;
                }
                if (!res.ok) throw new Error('Request failed');
                this.message = this.form.id ? 'Post updated!' : 'Post created!';
                this.resetForm();
                this.loadPosts(1);
                setTimeout(() => (this.message = ''), 2000);
            } finally {
                this.saving = false;
            }
        },
        edit(post) {
            this.form = { id: post.id, title: post.title, author: post.author, content: post.content };
            this.errors = {};
            this.message = '';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        async remove(post) {
            if (!confirm(`Delete post "${post.title}"?`)) return;
            const res = await fetch(`/api/posts/${post.id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json' },
            });
            if (res.ok) {
                this.message = 'Post deleted';
                this.loadPosts();
                setTimeout(() => (this.message = ''), 2000);
            }
        },
        goToPage(page) {
            if (page < 1 || page > this.lastPage) return;
            this.loadPosts(page);
        },
        resetForm() {
            this.form = { id: null, title: '', author: '', content: '' };
            this.errors = {};
        },
    },
};
</script>
