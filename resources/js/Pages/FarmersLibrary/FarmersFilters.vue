<template>
    <div class="farmers-filters">
        <div class="farmers-filters__content">
            <h2 class="farmers-filters__title h2">
                {{ $t("Tell us what information you're looking for") }}:
            </h2>
            <form class="select-boxes">
                <div
                    class="filter-group"
                    v-for="category in Object.keys(filters)"
                    :key="category"
                >
                    <span class="select-boxes__title">{{
                        category.charAt(0).toUpperCase() + category.slice(1)
                    }}</span>
                    <div class="select-boxes__boxes">
                        <label
                            class="select-box"
                            :class="
                                this.containsFilter(category, filter.id)
                                    ? 'active'
                                    : ''
                            "
                            v-for="filter in filters[category]"
                            :key="filter"
                        >
                            <input
                                type="radio"
                                :value="filter.id"
                                v-model="this.activeFilters[category]"
                                @click="
                                    addFilter(filter.id, filter.name, category)
                                "
                            />
                            <span class="checkbox-label">
                                {{ filter.name }}
                            </span>
                        </label>

                        <button
                            class="button-reset select-box"
                            :class="
                                this.isInActiveFilterList(category) &&
                                this.activeFilterList[category].length > 0
                                    ? ''
                                    : 'active'
                            "
                            @click.prevent="clearFilters(category)"
                        >
                            {{ $t('View all') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <button
            class="button button--fill button--span button--fixed"
            @click="updatePosts"
        >
            {{ $t('Continue') }}
        </button>
    </div>
</template>

<script>
export default {
    emits: ['removeCategory', 'closePopup', 'addFilter'],
    props: {
        filters: Array,
        filterTitle: String,
        activeFilters: Object,
        activeFilterList: Object,
    },
    methods: {
        clearFilters(category) {
            this.$emit('removeCategory', category);
        },
        closeFilters() {
            this.$emit('closePopup');
        },
        isInActiveFilterList(category) {
            return typeof this.activeFilterList[category] != 'undefined';
        },
        addFilter(id, name, category) {
            this.$emit('addFilter', { id, name, category });
        },
        containsFilter(category, id) {
            let containsFilter = false;
            if (this.isInActiveFilterList(category)) {
                this.activeFilterList[category].forEach((filter) => {
                    if (filter.id == id) {
                        containsFilter = true;
                    }
                });
            }
            return containsFilter;
        },
        updatePosts() {
            this.$emit('updatePosts');
            this.closeFilters();
        },
    },
};
</script>
