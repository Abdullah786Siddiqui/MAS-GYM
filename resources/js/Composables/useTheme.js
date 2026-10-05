import { ref, watch, onMounted } from 'vue';

const isDark = ref(true);

export function useTheme() {
    const applyTheme = (dark) => {
        if (dark) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('masfitness-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('masfitness-theme', 'light');
        }
    };

    const initTheme = () => {
        const saved = localStorage.getItem('masfitness-theme');
        // Default to dark if no preference saved
        isDark.value = saved !== 'light';
        applyTheme(isDark.value);
    };

    const toggleTheme = () => {
        isDark.value = !isDark.value;
    };

    watch(isDark, (val) => {
        applyTheme(val);
    });

    return { isDark, toggleTheme, initTheme };
}
