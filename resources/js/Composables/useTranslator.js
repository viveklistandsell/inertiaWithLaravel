import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const getDeep = (obj, key) => {
    if (!obj) return undefined;
    return key.split('.').reduce((acc, part) => (acc && typeof acc === 'object' ? acc[part] : undefined), obj);
};

const interpolate = (value, replacements) => {
    if (typeof value !== 'string' || !replacements) return value;
    return Object.keys(replacements).reduce(
        (str, k) => str.replaceAll(`:${k}`, String(replacements[k])),
        value,
    );
};

export function useTranslator() {
    const page = usePage();

    const locale = computed(() => page.props.locale ?? 'de');
    const supportedLocales = computed(() => page.props.supportedLocales ?? ['de', 'en']);
    const translations = computed(() => page.props.translations ?? {});

    const t = (key, replacements) => {
        const value = getDeep(translations.value, key);
        if (value === undefined || value === null) return key;
        return interpolate(value, replacements);
    };

    const localePath = (path = '/', targetLocale) => {
        const loc = targetLocale ?? locale.value;
        const clean = '/' + String(path).replace(/^\/+/, '');
        return `/${loc}${clean === '/' ? '' : clean}`;
    };

    const switchLocaleUrl = (targetLocale) => {
        const current = page.url || '/';
        const supported = supportedLocales.value.join('|');
        const re = new RegExp(`^/(?:${supported})(?=/|$|\\?|#)`);
        const stripped = current.replace(re, '') || '/';
        const clean = stripped === '/' ? '' : stripped;
        return `/${targetLocale}${clean}`;
    };

    return { t, locale, supportedLocales, translations, localePath, switchLocaleUrl };
}
