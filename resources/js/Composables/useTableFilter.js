import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

/**
 * Reusable table filtering composable with debounced search,
 * state preservation, and clean URL parameter handling.
 *
 * @param {string} routeName - Name of the route to submit filters to
 * @param {Object} initialFilters - Initial filter values from page props
 * @param {number} debounceMs - Debounce delay in milliseconds (default 300ms)
 */
export function useTableFilter(routeName, initialFilters = {}, debounceMs = 300) {
    const filters = ref({ ...initialFilters });
    const isSearching = ref(false);
    let debounceTimer = null;

    // Apply all active filters to Inertia router
    const applyFilters = () => {
        isSearching.value = true;

        // Clean out empty/null values so URL remains neat
        const params = {};
        for (const [key, val] of Object.entries(filters.value)) {
            if (val !== '' && val !== null && val !== undefined) {
                params[key] = val;
            }
        }

        router.get(route(routeName), params, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            onFinish: () => {
                isSearching.value = false;
            },
        });
    };

    // Debounced search trigger (ideal for text search input)
    const onSearchInput = () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            applyFilters();
        }, debounceMs);
    };

    // Instant filter change (for dropdowns/selects)
    const onFilterChange = (key, value) => {
        filters.value[key] = value;
        applyFilters();
    };

    // Clear a specific filter
    const clearFilter = (key) => {
        filters.value[key] = '';
        applyFilters();
    };

    // Reset all filters to default
    const resetAllFilters = () => {
        for (const key of Object.keys(filters.value)) {
            filters.value[key] = '';
        }
        applyFilters();
    };

    return {
        filters,
        isSearching,
        applyFilters,
        onSearchInput,
        onFilterChange,
        clearFilter,
        resetAllFilters,
    };
}
