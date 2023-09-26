<template>
    <div class="admin-table__controls">
        <AdminTableFilters
            v-if="!hideFilters"
            :meta="meta"
            @updateFilters="updateFilters"
        />

        <h2 v-if="hideFilters" class="table-controls__title">{{ title }}</h2>

        <div v-if="!hideSearch" class="table-controls__search">
            <div class="search-bar">
                <svg
                    class="search-bar__icon"
                    width="15"
                    height="16"
                    viewBox="0 0 15 16"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M6.25 2.21875C8.75256 2.21875 10.7812 4.24746 10.7812 6.75C10.7812 7.83244 10.4017 8.82619 9.76844 9.6055L12.8314 12.6686C13.0145 12.8516 13.0145 13.1484 12.8314 13.3314C12.6651 13.4979 12.4046 13.513 12.2211 13.3768L12.1686 13.3314L9.1055 10.2684C8.32619 10.9017 7.33244 11.2812 6.25 11.2812C3.74746 11.2812 1.71875 9.25256 1.71875 6.75C1.71875 4.24746 3.74746 2.21875 6.25 2.21875ZM6.25 3.15625C4.26522 3.15625 2.65625 4.76522 2.65625 6.75C2.65625 8.73475 4.26522 10.3438 6.25 10.3438C8.23475 10.3438 9.84375 8.73475 9.84375 6.75C9.84375 4.76522 8.23475 3.15625 6.25 3.15625Z"
                        fill="#212121"
                    />
                </svg>

                <input
                    class="search-bar__search"
                    type="text"
                    name="search"
                    placeholder="search"
                    v-model="search"
                    @change="updateSearch"
                />
            </div>
        </div>

        <div class="table-controls__display f-body-5">
            <span>{{ $t('Show') }}</span>
            <select
                class="table-control-per-page"
                name="perPage"
                v-model="perPage"
                @change="changePerPage"
            >
                <option value="5">5</option>
                <option selected="selected" value="10">10</option>
                <option value="15">15</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <span>{{ $t('entries per page') }}</span>
        </div>
    </div>
</template>

<script>
import AdminTableFilters from './AdminTableFilters.vue';

export default {
    name: 'AdminTableControls',
    props: {
        meta: Array,
        filters: Array,
        hideSearch: Boolean,
        hideFilters: Boolean,
        title: String,
    },
    emits: ['changePerPage', 'updateFilters', 'updateSearch'],
    components: {
        AdminTableFilters,
    },
    data() {
        return {
            perPage: 10,
            search: '',
        };
    },
    methods: {
        changePerPage() {
            this.$emit('changePerPage', this.perPage);
        },
        updateSearch() {
            this.$emit('updateSearch', this.search);
        },
        updateFilters(filters) {
            this.$emit('updateFilters', filters);
        },
    },
};
</script>
