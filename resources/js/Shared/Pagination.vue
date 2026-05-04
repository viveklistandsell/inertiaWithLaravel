<template>
    <nav v-if="links.length > 3" class="flex flex-wrap items-center justify-center gap-2 mt-6">
        <Link
            v-if="prev"
            :href="prev.url"
            preserve-scroll
            class="px-4 py-2 text-sm font-medium bg-white text-gray-700 border border-gray-300 rounded-lg shadow-sm hover:bg-blue-50 hover:border-blue-400 hover:text-blue-600 transition flex items-center gap-1"
        >
            <span>&larr;</span> {{ t('users.previous') }}
        </Link>
        <span
            v-else
            class="px-4 py-2 text-sm font-medium bg-gray-100 text-gray-400 border border-gray-200 rounded-lg cursor-not-allowed flex items-center gap-1 select-none"
        >
            <span>&larr;</span> {{ t('users.previous') }}
        </span>

        <template v-for="link in numberLinks" :key="link.label">
            <Link
                v-if="link.url"
                :href="link.url"
                preserve-scroll
                preserve-state
                class="min-w-[40px] px-3 py-2 text-sm font-medium border rounded-lg shadow-sm transition text-center select-none"
                :class="link.active
                    ? 'bg-blue-600 text-white border-blue-600 font-bold shadow-md scale-105'
                    : 'bg-white text-gray-700 border-gray-300 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-600'"
            >
                {{ link.label }}
            </Link>
            <span
                v-else
                class="min-w-[40px] px-3 py-2 text-sm text-gray-400 select-none text-center"
            >
                …
            </span>
        </template>

        <Link
            v-if="next"
            :href="next.url"
            preserve-scroll
            class="px-4 py-2 text-sm font-medium bg-white text-gray-700 border border-gray-300 rounded-lg shadow-sm hover:bg-blue-50 hover:border-blue-400 hover:text-blue-600 transition flex items-center gap-1"
        >
            {{ t('users.next') }} <span>&rarr;</span>
        </Link>
        <span
            v-else
            class="px-4 py-2 text-sm font-medium bg-gray-100 text-gray-400 border border-gray-200 rounded-lg cursor-not-allowed flex items-center gap-1 select-none"
        >
            {{ t('users.next') }} <span>&rarr;</span>
        </span>
    </nav>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useTranslator } from '../Composables/useTranslator.js';

const props = defineProps({
    links: {
        type: Array,
        required: true,
    },
});

const { t } = useTranslator();

const prev = computed(() => (props.links[0]?.url ? props.links[0] : null));
const next = computed(() => {
    const last = props.links[props.links.length - 1];
    return last?.url ? last : null;
});
const numberLinks = computed(() => props.links.slice(1, -1));
</script>
