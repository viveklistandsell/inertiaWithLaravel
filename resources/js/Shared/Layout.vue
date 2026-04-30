<template>
    <div class="min-h-screen bg-gray-50">
        <header class="bg-white shadow">
            <nav class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
                <div class="text-xl font-bold text-gray-800">
                    <Link href="/">MyApp</Link>
                </div>

                <ul class="flex items-center gap-6">
                    <li>
                        <Link
                            href="/"
                            class="text-gray-700 hover:text-blue-600"
                            :class="{ 'font-bold text-blue-600 underline': $page.url === '/' }"
                        >
                            Home
                        </Link>
                    </li>
                    <li v-if="user">
                        <Link
                            href="/user"
                            class="text-gray-700 hover:text-blue-600"
                            :class="{ 'font-bold text-blue-600 underline': $page.url.startsWith('/user') }"
                        >
                            Users
                        </Link>
                    </li>
                    <li v-if="user">
                        <Link
                            href="/setting"
                            class="text-gray-700 hover:text-blue-600"
                            :class="{ 'font-bold text-blue-600 underline': $page.url.startsWith('/setting') }"
                        >
                            Settings
                        </Link>
                    </li>
                </ul>

                <div class="flex items-center gap-3">
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
                            href="/logout"
                            method="post"
                            as="button"
                            class="px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600 transition"
                        >
                            Logout
                        </Link>
                    </template>
                    <Link
                        v-else
                        href="/login"
                        class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                    >
                        Login
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

<script>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export default {
    components: { Link },
    setup() {
        const page = usePage();
        const user = computed(() => page.props.auth?.user);
        return { user };
    },
};
</script>
