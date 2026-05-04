<template>
    <div class="relative" ref="root">
        <button
            type="button"
            @click="open = !open"
            class="flex items-center gap-1 px-2 py-1 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition"
            :aria-expanded="open"
            aria-haspopup="listbox"
        >
            <span class="font-semibold uppercase">{{ locale }}</span>
            <span>{{ flagFor(locale) }}</span>
            <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.06l3.71-3.83a.75.75 0 111.08 1.04l-4.25 4.39a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
        </button>

        <ul
            v-if="open"
            role="listbox"
            class="absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg z-10 overflow-hidden"
        >
            <li v-for="code in supportedLocales" :key="code">
                <Link
                    :href="switchLocaleUrl(code)"
                    preserve-scroll
                    @click="open = false"
                    class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-blue-50 transition"
                    :class="code === locale ? 'bg-blue-50 font-semibold text-blue-700' : 'text-gray-700'"
                >
                    <span>{{ flagFor(code) }}</span>
                    <span>{{ t('language.' + code) }}</span>
                    <span class="ml-auto text-xs text-gray-400 uppercase">{{ code }}</span>
                </Link>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useTranslator } from '../Composables/useTranslator.js';

const { t, locale, supportedLocales, switchLocaleUrl } = useTranslator();
const open = ref(false);
const root = ref(null);

const flagFor = (code) => ({ de: '🇩🇪', en: '🇬🇧' }[code] ?? '🌐');

const onDocClick = (e) => {
    if (root.value && !root.value.contains(e.target)) open.value = false;
};

onMounted(() => document.addEventListener('click', onDocClick));
onBeforeUnmount(() => document.removeEventListener('click', onDocClick));
</script>
