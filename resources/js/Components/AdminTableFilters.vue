<template>
    <div class="table-controls__filters" ref="tableFilters">
        <div class="table-filter table-filter--clear" @click="clearFilters">
            Clear filter
        </div>
        <div class="table-filter-wrapper" v-for="filter in meta">
            <div class="table-filter" @click="openFilter(filter)">
                <span>{{ filter.name }}</span>
                <svg
                    width="10"
                    height="10"
                    viewBox="0 0 10 10"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    style="margin-left: 1rem"
                >
                    <path
                        d="M2.5426 3.33337C2.09512 3.33337 1.85596 3.86042 2.15063 4.19717L4.45137 6.82662C4.74191 7.15862 5.25837 7.15862 5.54891 6.82662L7.84966 4.19717C8.14433 3.86042 7.90516 3.33337 7.45771 3.33337H2.5426Z"
                        fill="#212121"
                    />
                </svg>
            </div>
            <ul
                class="table-filter__dropdown"
                :class="{ active: localFilters.includes(filter.name) }"
            >
                <li
                    class="table-filter-item"
                    tabindex="0"
                    v-for="value in filter.filters"
                >
                    <SingleCheckBox
                        :small="true"
                        inputName="valueActive"
                        :label="value.value"
                        :value="isChecked(filter, value.id)"
                        @valueChanged="valueChanged(filter, value.id)"
                    />
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import SingleCheckBox from '../Shared/Inputs/SingleCheckbox.vue';

export default {
    name: 'AdminTableFilters',
    props: {
        meta: Array,
    },
    emits: ['updateFilters'],
    components: {
        SingleCheckBox,
    },
    mounted() {
        this.localFilters = this.meta;
        document.body.addEventListener('click', (e) => {
            // if click not within filters, close filters
            if (!e.target.closest('.table-controls__filters')) {
                this.localFilters = [];
            }
        });
    },
    data() {
        return {
            localFilters: [],
            filterValues: {},
        };
    },
    methods: {
        valueChanged(filter, value) {
            // if filters is null, make it an object to accept keys
            if (this.filterValues == null) {
                this.filterValues = {};
            }

            let key = 'id';
            if (filter?.reference) {
                key = 'reference';
            }

            this.filterValues[filter[key]] =
                this.filterValues[filter[key]] || [];

            // if value is being filtered, remove
            if (this.filterValues[filter[key]].includes(value)) {
                this.filterValues[filter[key]] = this.filterValues[
                    filter[key]
                ].filter((f) => f != value);
            } else {
                this.filterValues[filter[key]].push(value);
            }

            this.$emit('updateFilters', this.filterValues);
        },
        getFilterById(id) {
            return this.localFilters.filter((filter) => filter.id === id)[0];
        },
        clearFilters() {
            this.filterValues = null;
            this.$emit('updateFilters', this.filterValues);
        },
        openFilter(filter) {
            if (this.localFilters.includes(filter.name)) {
                this.localFilters.splice(
                    this.localFilters.indexOf(filter.name),
                    1
                );
            } else {
                this.localFilters = [];
                this.localFilters.push(filter.name);
            }
        },
        isChecked(filter, value) {
            if (Array.isArray(this.filterValues[filter.id])) {
                return this.filterValues[filter.id].filter((f) => f == value)
                    .length;
            }
        },
    },
};
</script>
