import { ref, watch } from 'vue';

export function useDebouncedRef(source, delay = 300) {
    const debounced = ref(source.value);
    let timer = null;

    watch(source, (value) => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            debounced.value = value;
        }, delay);
    });

    return debounced;
}

export function debounce(fn, delay = 300) {
    let timer = null;
    const debounced = (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
    debounced.cancel = () => clearTimeout(timer);
    return debounced;
}

export function throttle(fn, interval = 300) {
    let lastRun = 0;
    let timer = null;
    return (...args) => {
        const now = Date.now();
        const remaining = interval - (now - lastRun);
        if (remaining <= 0) {
            lastRun = now;
            fn(...args);
        } else {
            clearTimeout(timer);
            timer = setTimeout(() => {
                lastRun = Date.now();
                fn(...args);
            }, remaining);
        }
    };
}
