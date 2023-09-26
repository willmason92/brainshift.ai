<template>
    <div class="farmers-filters">
        <div class="farmers-filters__content">
            <h2 class="farmers-filters__title h2">
                {{ $t('Filter results') }}:
            </h2>
            <form class="select-boxes">
                <span class="select-boxes__title">{{ filterTitle }}</span>
                <div class="select-boxes__boxes">
                    <label
                        class="select-box"
                        :class="isFilterActive(filter.id) ? 'active' : ''"
                        v-for="filter in filters"
                        :key="filter"
                    >
                        <input
                            type="radio"
                            :value="filter.id"
                            @click="addFilter(filter.id, filter.name)"
                        />
                        <span class="checkbox-label">
                            {{ filter.name }}
                        </span>
                    </label>

                    <button
                        class="button-reset select-box"
                        :class="this.activeFilter.length == 0 ? 'active' : ''"
                        @click.prevent="clearFilters()"
                    >
                        {{ $t('View all') }}
                    </button>
                </div>
            </form>
        </div>
        <button
            class="button button--fill button--span button--continue"
            @click="closeFilters"
        >
            {{ $t('Continue') }}
        </button>
    </div>
</template>

<script>
export default {
    props: {
        filters: Array,
        filterTitle: String,
        activeFilter: Array,
    },
    methods: {
        clearFilters() {
            this.$emit('clearFilters');
        },

        closeFilters() {
            this.$emit('closePopup');
        },

        addFilter(id, name) {
            this.$emit('addFilter', { id, name });
        },

        isFilterActive(id) {
            return (
                this.activeFilter.filter(
                    (activeFilter) => activeFilter.id == id
                ).length > 0
            );
        },
    },
};
</script>
