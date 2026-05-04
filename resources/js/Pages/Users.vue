<template>
    <Head>
        <title>{{ t('users.title') }} — {{ t('app_name') }}</title>
        <meta name="description" content="Browse the list of registered users here." />
    </Head>

    <div class="flex items-end justify-between mb-4 gap-3">
        <div>
            <h1 class="text-4xl font-bold text-gray-800">{{ t('users.title') }}</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ t('users.showing', { from: users.from || 0, to: users.to || 0, total: users.total }) }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">
                {{ t('users.page_of', { current: users.current_page, last: users.last_page }) }}
            </span>
            <Link
                v-if="canManageUsers"
                :href="localePath('/user/create')"
                class="px-4 py-2 text-sm font-semibold bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition"
            >
                + {{ t('users.create_user') }}
            </Link>
            <span
                v-else
                class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-500 rounded-full flex items-center gap-1"
                title="Admins only"
            >
                &#128274; {{ t('users.read_only') }}
            </span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-5">
                <label class="block text-xs font-semibold text-gray-600 mb-1">{{ t('common.search') }}</label>
                <div class="relative">
                    <input
                        v-model="form.search"
                        type="text"
                        :placeholder="t('users.search_ph')"
                        class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    />
                    <span class="absolute left-3 top-2.5 text-gray-400">&#128269;</span>
                </div>
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">{{ t('users.sort_by') }}</label>
                <select
                    v-model="form.sort"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white"
                >
                    <option value="id">ID</option>
                    <option value="name">{{ t('users.name') }}</option>
                    <option value="email">{{ t('users.email') }}</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1">{{ t('users.direction') }}</label>
                <select
                    v-model="form.direction"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white"
                >
                    <option value="asc">{{ t('users.asc') }}</option>
                    <option value="desc">{{ t('users.desc') }}</option>
                </select>
            </div>

            <div class="md:col-span-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">{{ t('users.per_page') }}</label>
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
                    {{ t('common.reset') }}
                </button>
            </div>
        </div>

        <div v-if="hasActiveFilters" class="mt-3 flex flex-wrap items-center gap-2 text-xs">
            <span class="text-gray-500">{{ t('users.active_filters') }}</span>
            <span v-if="filters.search" class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full">
                {{ t('common.search').toLowerCase() }}: "{{ filters.search }}"
            </span>
            <span v-if="filters.sort !== 'id' || filters.direction !== 'asc'" class="px-2 py-1 bg-purple-50 text-purple-700 rounded-full">
                {{ t('users.sort_by').toLowerCase() }}: {{ filters.sort }} {{ filters.direction }}
            </span>
            <span v-if="filters.per_page !== 10" class="px-2 py-1 bg-amber-50 text-amber-700 rounded-full">
                {{ filters.per_page }} {{ t('users.per_page').toLowerCase() }}
            </span>
        </div>
    </div>

    <div v-if="users.data.length === 0" class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-100">
        <p class="text-gray-500">{{ t('users.no_results') }}</p>
    </div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div
            v-for="row in users.data"
            :key="row.id"
            class="group p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-300 transition flex items-center justify-between gap-3"
        >
            <div class="flex items-center gap-3 min-w-0">
                <div
                    class="w-10 h-10 flex items-center justify-center rounded-full text-white font-bold text-sm shadow flex-shrink-0"
                    :style="{ background: avatarColor(row.id) }"
                >
                    {{ initials(row.name) }}
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-xs text-gray-400">#{{ row.id }}</p>
                        <span
                            v-if="row.role === 'admin'"
                            class="px-1.5 py-0.5 text-[10px] font-bold bg-purple-100 text-purple-700 rounded"
                        >
                            ADMIN
                        </span>
                    </div>
                    <p class="font-semibold text-gray-800 truncate group-hover:text-blue-600">
                        {{ row.name }}
                    </p>
                    <p class="text-xs text-gray-500 truncate">{{ row.email }}</p>
                </div>
            </div>
            <Link
                v-if="canManageUsers"
                :href="editUrl(row.id)"
                class="px-3 py-1 text-xs font-semibold bg-blue-50 text-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition flex-shrink-0"
            >
                {{ t('common.edit') }}
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

<script setup>
import Layout from '../Shared/Layout.vue';
import Pagination from '../Shared/Pagination.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, reactive, watch } from 'vue';
import { debounce, throttle } from '../Composables/useDebounce.js';
import { useTranslator } from '../Composables/useTranslator.js';

defineOptions({ layout: Layout });

const props = defineProps({
    users: Object,
    filters: Object,
});

const { t, localePath } = useTranslator();
const page = usePage();

const form = reactive({
    search: props.filters.search ?? '',
    sort: props.filters.sort ?? 'id',
    direction: props.filters.direction ?? 'asc',
    per_page: props.filters.per_page ?? 10,
});

const canManageUsers = computed(() => !!page.props.auth?.can?.manage_users);

const hasActiveFilters = computed(() =>
    !!props.filters.search
    || props.filters.sort !== 'id'
    || props.filters.direction !== 'asc'
    || props.filters.per_page !== 10
);

const applyFilters = () => {
    const params = {};
    if (form.search) params.search = form.search;
    if (form.sort !== 'id') params.sort = form.sort;
    if (form.direction !== 'asc') params.direction = form.direction;
    if (form.per_page !== 10) params.per_page = form.per_page;

    router.get(localePath('/user'), params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const debouncedSearch = debounce(applyFilters, 400);
const throttledFilter = throttle(applyFilters, 200);

watch(() => form.search, () => debouncedSearch());
watch(() => form.sort, () => throttledFilter());
watch(() => form.direction, () => throttledFilter());
watch(() => form.per_page, () => throttledFilter());

onBeforeUnmount(() => debouncedSearch?.cancel?.());

const reset = () => {
    form.search = '';
    form.sort = 'id';
    form.direction = 'asc';
    form.per_page = 10;
};

const editUrl = (id) => {
    const params = new URLSearchParams();
    if (props.filters.search) params.set('search', props.filters.search);
    if (props.filters.sort && props.filters.sort !== 'id') params.set('sort', props.filters.sort);
    if (props.filters.direction && props.filters.direction !== 'asc') params.set('direction', props.filters.direction);
    if (props.filters.per_page && props.filters.per_page !== 10) params.set('per_page', props.filters.per_page);
    if (props.users.current_page > 1) params.set('page', props.users.current_page);
    const qs = params.toString();
    return `${localePath(`/user/${id}/edit`)}${qs ? '?' + qs : ''}`;
};

const initials = (name) => name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();

const avatarColor = (id) => {
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
};
</script>
