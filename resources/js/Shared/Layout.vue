<template>
    <div class="min-h-screen bg-gray-50">
        <header class="bg-white shadow">
            <nav class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
                <div class="text-xl font-bold text-gray-800">
                    <Link :href="localePath('/')">{{ t('app_name') }}</Link>
                </div>

                <ul class="flex items-center gap-6">
                    <li>
                        <Link
                            :href="localePath('/')"
                            class="text-gray-700 hover:text-blue-600"
                            :class="{ 'font-bold text-blue-600 underline': isActive('/', true) }"
                        >
                            {{ t('nav.home') }}
                        </Link>
                    </li>
                    <li v-if="user">
                        <Link
                            :href="localePath('/user')"
                            class="text-gray-700 hover:text-blue-600"
                            :class="{ 'font-bold text-blue-600 underline': isActive('/user') }"
                        >
                            {{ t('nav.users') }}
                        </Link>
                    </li>
                    <li v-if="user">
                        <Link
                            :href="localePath('/setting')"
                            class="text-gray-700 hover:text-blue-600"
                            :class="{ 'font-bold text-blue-600 underline': isActive('/setting') }"
                        >
                            {{ t('nav.settings') }}
                        </Link>
                    </li>
                </ul>

                <div class="flex items-center gap-3">
                    <LanguageSwitcher />

                    <template v-if="user">
                        <div class="text-sm text-gray-700 text-right">
                            <p class="font-semibold">{{ user.name }}</p>
                            <p class="text-xs text-gray-500 flex items-center gap-1 justify-end">
                                <span
                                    class="px-1.5 py-0.5 rounded text-[10px] font-bold"
                                    :class="user.role === 'admin'
                                        ? 'bg-purple-100 text-purple-700'
                                        : 'bg-gray-100 text-gray-600'"
                                >
                                    {{ user.role.toUpperCase() }}
                                </span>
                            </p>
                        </div>
                        <Link
                            :href="localePath('/logout')"
                            method="post"
                            as="button"
                            class="px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600 transition"
                        >
                            {{ t('nav.logout') }}
                        </Link>
                    </template>
                    <Link
                        v-else
                        :href="localePath('/login')"
                        class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                    >
                        {{ t('nav.login') }}
                    </Link>
                </div>
            </nav>
        </header>

        <div v-if="$page.props.flash?.message" class="max-w-7xl mx-auto mt-4 px-4">
            <div class="p-3 bg-green-100 text-green-800 rounded">
                {{ $page.props.flash.message }}
            </div>
        </div>

        <main class="max-w-7xl mx-auto px-4 py-6">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import LanguageSwitcher from './LanguageSwitcher.vue';
import { useTranslator } from '../Composables/useTranslator.js';

const page = usePage();
const { t, locale, localePath } = useTranslator();

const user = computed(() => page.props.auth?.user);

const stripLocale = (url) => {
    const path = (url || '/').split('?')[0];
    const re = new RegExp(`^/(?:de|en)(?=/|$)`);
    return path.replace(re, '') || '/';
};

const isActive = (path, exact = false) => {
    const here = stripLocale(page.url);
    if (exact) return here === path;
    return here === path || here.startsWith(path + '/');
};
</script>
