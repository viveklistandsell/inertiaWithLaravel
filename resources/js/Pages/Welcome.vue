<template>
    <Head>
        <title>Home — Inertia App</title>
        <meta name="description" content="Welcome to our Inertia.js + Laravel demo home page." />
        <meta property="og:title" content="Home — Inertia App" />
    </Head>

    <h1 class="text-4xl font-bold text-gray-800">Welcome to Inertia.js with Laravel!</h1>

    <h1 class="text-4xl font-bold text-gray-800 mt-4">Welcome, {{ name }}!</h1>
    <p>In this example, we're passing the user's name from Laravel to the Inertia.js component.</p>

    <h2 class="text-3xl font-bold text-gray-800 mt-6">Frameworks:</h2>
    <ul class="list-disc pl-5">
        <li v-for="framework in framworks" :key="framework" class="text-lg text-gray-600">
            {{ framework }}
        </li>
    </ul>
</template>
<script>
import Layout from '../Shared/Layout.vue';
import { Head } from '@inertiajs/vue3';

export default {
    layout: Layout,
    components: { Head },
    props: {
        name: String,
        framworks: Array,
    },
};
</script>
