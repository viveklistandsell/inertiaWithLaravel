<template>
    <Head>
        <title>{{ t('nav.home') }} — {{ t('app_name') }}</title>
        <meta name="description" content="Welcome to our Inertia.js + Laravel demo home page." />
        <meta property="og:title" :content="`${t('nav.home')} — ${t('app_name')}`" />
    </Head>

    <h1 class="text-4xl font-bold text-gray-800">{{ t('welcome.title') }}</h1>

    <h1 class="text-4xl font-bold text-gray-800 mt-4">{{ t('welcome.greeting', { name }) }}</h1>
    <p>{{ t('welcome.intro') }}</p>

    <h2 class="text-3xl font-bold text-gray-800 mt-6">{{ t('welcome.frameworks') }}</h2>
    <ul class="list-disc pl-5">
        <li v-for="framework in framworks" :key="framework" class="text-lg text-gray-600">
            {{ framework }}
        </li>
    </ul>
</template>

<script setup>
import Layout from '../Shared/Layout.vue';
import { Head } from '@inertiajs/vue3';
import { useTranslator } from '../Composables/useTranslator.js';

defineOptions({ layout: Layout });

defineProps({
    name: String,
    framworks: Array,
});

const { t } = useTranslator();
</script>
