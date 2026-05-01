<template>
    <Head>
        <title>Users — Inertia App</title>
        <meta name="description" content="Browse the list of registered users here." />
    </Head>

    <div class="flex items-end justify-between mb-4 gap-3">
        <div>
            <h1 class="text-4xl font-bold text-gray-800">Users</h1>
            <p class="text-sm text-gray-500 mt-1">
                Showing {{ users.from || 0 }}–{{ users.to || 0 }} of {{ users.total }} users
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">
                Page {{ users.current_page }} / {{ users.last_page }}
            </span>
            <Link
                v-if="canManageUsers"
                href="/user/create"
                class="px-4 py-2 text-sm font-semibold bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition"
            >
                + Create User
            </Link>
            <span
                v-else
                class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-500 rounded-full flex items-center gap-1"
                title="Admins only"
            >
                &#128274; Read-only
            </span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-5">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Search</label>
                <div class="relative">
                    <input
                        v-model="form.search"
                        type="text"
                        placeholder="Search by name or email..."
                        class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    />
                    <span class="absolute left-3 top-2.5 text-gray-400">&#128269;</span>
                </div>
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Sort by</label>
                <select
                    v-model="form.sort"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white"
                >
                    <option value="id">ID</option>
                    <option value="name">Name</option>
                    <option value="email">Email</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Direction</label>
                <select
                    v-model="form.direction"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white"
                >
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
            </div>

            <div class="md:col-span-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Per page</label>
                <select
                    v-model.number="form.per_page"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white"
                >
                    <option :value="10">10</option>
                    <option :value="25">25</option>
                    <option :value="50">50</option>
                    <option :value="100">100</option>
                </select>
            </div>

            <div class="md:col-span-1">
                <button
                    @click="reset"
                    type="button"
                    class="w-full px-3 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition"
                >
                    Reset
                </button>
            </div>
        </div>

        <div v-if="hasActiveFilters" class="mt-3 flex flex-wrap items-center gap-2 text-xs">
            <span class="text-gray-500">Active filters:</span>
            <span v-if="filters.search" class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full">
                search: "{{ filters.search }}"
            </span>
            <span v-if="filters.sort !== 'id' || filters.direction !== 'asc'" class="px-2 py-1 bg-purple-50 text-purple-700 rounded-full">
                sort: {{ filters.sort }} {{ filters.direction }}
            </span>
            <span v-if="filters.per_page !== 10" class="px-2 py-1 bg-amber-50 text-amber-700 rounded-full">
                {{ filters.per_page }} per page
            </span>
        </div>
    </div>

    <div v-if="users.data.length === 0" class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-100">
        <p class="text-gray-500">No users match your filters.</p>
    </div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div
            v-for="user in users.data"
            :key="user.id"
            class="group p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-300 transition flex items-center justify-between gap-3"
        >
            <div class="flex items-center gap-3 min-w-0">
                <div
                    class="w-10 h-10 flex items-center justify-center rounded-full text-white font-bold text-sm shadow flex-shrink-0"
                    :style="{ background: avatarColor(user.id) }"
                >
                    {{ initials(user.name) }}
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-xs text-gray-400">#{{ user.id }}</p>
                        <span
                            v-if="user.role === 'admin'"
                            class="px-1.5 py-0.5 text-[10px] font-bold bg-purple-100 text-purple-700 rounded"
                        >
                            ADMIN
                        </span>
                    </div>
                    <p class="font-semibold text-gray-800 truncate group-hover:text-blue-600">
                        {{ user.name }}
                    </p>
                    <p class="text-xs text-gray-500 truncate">{{ user.email }}</p>
                </div>
            </div>
            <Link
                v-if="canManageUsers"
                :href="editUrl(user.id)"
                class="px-3 py-1 text-xs font-semibold bg-blue-50 text-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition flex-shrink-0"
            >
                Edit
            </Link>
            <span
                v-else
                class="px-3 py-1 text-xs text-gray-400 flex-shrink-0"
                title="Admins only"
            >
                &#128274;
            </span>
        </div>
    </div>

    <Pagination :links="users.links" />
</template>

<script>
import Layout from '../Shared/Layout.vue';
import Pagination from '../Shared/Pagination.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { debounce, throttle } from '../Composables/useDebounce.js';

export default {
    layout: Layout,
    components: { Head, Link, Pagination },
    props: {
        users: Object,
        filters: Object,
    },
    data() {
        return {
            form: {
                search: this.filters.search ?? '',
                sort: this.filters.sort ?? 'id',
                direction: this.filters.direction ?? 'asc',
                per_page: this.filters.per_page ?? 10,
            },
            debouncedSearch: null,
            throttledFilter: null,
        };
    },
    created() {
        this.debouncedSearch = debounce(() => this.applyFilters(), 400);
        this.throttledFilter = throttle(() => this.applyFilters(), 200);
    },
    beforeUnmount() {
        this.debouncedSearch?.cancel?.();
    },
    computed: {
        canManageUsers() {
            return !!usePage().props.auth?.can?.manage_users;
        },
        hasActiveFilters() {
            return this.filters.search
                || this.filters.sort !== 'id'
                || this.filters.direction !== 'asc'
                || this.filters.per_page !== 10;
        },
    },
    watch: {
        'form.search'() { this.debouncedSearch(); },
        'form.sort'() { this.throttledFilter(); },
        'form.direction'() { this.throttledFilter(); },
        'form.per_page'() { this.throttledFilter(); },
    },
    methods: {
        applyFilters() {
            const params = {};
            if (this.form.search) params.search = this.form.search;
            if (this.form.sort !== 'id') params.sort = this.form.sort;
            if (this.form.direction !== 'asc') params.direction = this.form.direction;
            if (this.form.per_page !== 10) params.per_page = this.form.per_page;

            router.get('/user', params, {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            });
        },
        reset() {
            this.form = { search: '', sort: 'id', direction: 'asc', per_page: 10 };
        },
        editUrl(id) {
            const params = new URLSearchParams();
            if (this.filters.search) params.set('search', this.filters.search);
            if (this.filters.sort && this.filters.sort !== 'id') params.set('sort', this.filters.sort);
            if (this.filters.direction && this.filters.direction !== 'asc') params.set('direction', this.filters.direction);
            if (this.filters.per_page && this.filters.per_page !== 10) params.set('per_page', this.filters.per_page);
            if (this.users.current_page > 1) params.set('page', this.users.current_page);
            const qs = params.toString();
            return `/user/${id}/edit${qs ? '?' + qs : ''}`;
        },
        initials(name) {
            return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
        },
        avatarColor(id) {
            const palette = [
                'linear-gradient(135deg,#667eea,#764ba2)',
                'linear-gradient(135deg,#f093fb,#f5576c)',
                'linear-gradient(135deg,#4facfe,#00f2fe)',
                'linear-gradient(135deg,#43e97b,#38f9d7)',
                'linear-gradient(135deg,#fa709a,#fee140)',
                'linear-gradient(135deg,#30cfd0,#330867)',
                'linear-gradient(135deg,#a8edea,#fed6e3)',
                'linear-gradient(135deg,#ff9a9e,#fecfef)',
            ];
            return palette[id % palette.length];
        },
    },
};
</script>
