<template>
    <Head>
        <title>{{ t('settings.title') }} — {{ t('app_name') }}</title>
        <meta name="description" content="Manage your application settings and preferences." />
        <meta property="og:title" :content="`${t('settings.title')} — ${t('app_name')}`" />
    </Head>

    <h1 class="text-4xl font-bold text-gray-800">{{ t('settings.title') }}</h1>
</template>

<script setup>
import Layout from '../Shared/Layout.vue';
import { Head } from '@inertiajs/vue3';
import { useTranslator } from '../Composables/useTranslator.js';

defineOptions({ layout: Layout });

const { t } = useTranslator();
</script>
