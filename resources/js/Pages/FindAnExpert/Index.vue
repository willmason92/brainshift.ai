<template>
    <layout-default>
        <div class="filter-navigation">
            <button
                class="filter-navigation__content-filters button button--small button--no-bg"
                @click="back()"
            >
                <span class="content-filters-arrow"> </span>
                <span>{{ $t('overview') }}</span>
            </button>

            <button
                class="filter-navigation__library-filters button button--small button--no-bg"
                @click="openPopup('showExpertFilters')"
            >
                {{ $t('Filter') }}
            </button>
        </div>

        <h1 class="h1">{{ $t('Find an Expert') }}</h1>

        <div class="expert-type-bar">
            <h3 class="expert-type-bar__title h3">
                <template v-if="expertType">{{
                    expertType.name || ''
                }}</template>
            </h3>

            <button
                v-if="
                    activeFilters.sector.length ||
                    activeFilters.speciality.length ||
                    userLocation
                "
                class="category-title-bar__reset button-reset"
                type="button"
                @click="resetFilters"
            >
                {{ $t('Reset filters') }}
            </button>
        </div>

        <div class="active-filters">
            <button
                class="active-filter pill button-reset"
                type="button"
                v-for="filter of filterList"
                :key="filter"
                @click="removeFilter(filter, true)"
            >
                <span class="active-filter__name">
                    {{ filter.name || '' }}
                </span>
                <svg
                    class="active-filter__icon"
                    width="12"
                    height="13"
                    viewBox="0 0 12 13"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M7.30141 6.49998L11.7814 1.99998C12.0214 1.75998 12.0214 1.37998 11.7814 1.15998C11.5414 0.91998 11.1614 0.91998 10.9414 1.15998L6.46141 5.65998L1.96141 1.15998C1.72141 0.91998 1.34141 0.91998 1.12141 1.15998C0.881406 1.39998 0.881406 1.77998 1.12141 1.99998L5.60141 6.49998L1.12141 11C0.881406 11.24 0.881406 11.62 1.12141 11.84C1.24141 11.96 1.40141 12.02 1.54141 12.02C1.68141 12.02 1.84141 11.96 1.96141 11.84L6.46141 7.33998L10.9614 11.84C11.0814 11.96 11.2414 12.02 11.3814 12.02C11.5214 12.02 11.6814 11.96 11.8014 11.84C12.0414 11.6 12.0414 11.22 11.8014 11L7.30141 6.49998Z"
                        fill="#010101"
                    />
                </svg>
            </button>
        </div>

        <Popup :active="popupActive" @active-state="closePopup">
            <ExpertFilters
                v-show="showExpertFilters"
                :activeFilters="activeFilters"
                :filterOn="
                    expertType.id == 1
                        ? [
                              { slug: 'speciality', name: 'Specialities' },
                              { slug: 'sector', name: 'Sector' },
                          ]
                        : [{ slug: 'sector', name: 'Sector' }]
                "
                :filters="filters"
                :errors="errors"
                @locationChanged="locationChanged"
                @addFilter="addFilter"
                @clearFilters="removeCategory"
                @removeFilter="removeFilter"
                @closePopup="closePopup"
                @updateResults="updateResults"
            />
        </Popup>

        <list :experts="experts" :expertType="expertType" />
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../Layout/Base.vue';
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';
import Popup from '../../Shared/Popup.vue';
import ExpertFilters from './Popup/ExpertFilters.vue';
import List from './List.vue';

export default {
    components: {
        LayoutDefault,
        Link,
        Popup,
        ExpertFilters,
        List,
    },
    props: {
        experts: Object,
        filters: Object,
        expertType: Object,
        activeType: String,
        activeSpeciality: String,
        activeSector: String,
        sectorOptions: Array,
        currentProjects: Array,
    },
    data() {
        return {
            activeFilters: {
                location: null,
                sector: [],
                speciality: [],
            },
            popupActive: false,
            showExpertFilters: false,
            userLocation: null,
            errors: {},
        };
    },
    methods: {
        openPopup(target) {
            this.popupActive = true;
            if (target == 'showExpertFilters') {
                this.showExpertFilters = true;
            }
        },
        closePopup() {
            this.updateResults();
            this.popupActive = false;
            this.showExpertFilters = false;
        },
        addFilter(filter) {
            console.log(this.activeFilters);
            this.activeFilters[filter.category].push({
                id: filter.id,
                name: filter.name,
                category: filter.category,
            });
        },
        removeFilter(filter, update = false) {
            //filter out matched category and id
            this.activeFilters[filter.category] = this.activeFilters[
                filter.category
            ].filter((activeFilter) => activeFilter.id != filter.id);

            if (update) {
                this.updateResults();
            }
        },
        removeCategory(category) {
            this.activeFilters[category] = [];
        },
        resetFilters() {
            this.activeFilters = {
                location: null,
                sector: [],
                speciality: [],
            };
            this.updateResults();
        },
        updateResults() {
            let submit = {};

            Object.keys(this.activeFilters).forEach((category) => {
                if (category == 'location') {
                    if (this.userLocation) {
                        submit.location = JSON.stringify(this.userLocation);
                    }
                } else {
                    if (typeof submit[category] == 'undefined') {
                        submit[category] = [];
                    }
                    this.activeFilters[category].forEach((filter) => {
                        submit[category].push(filter.id);
                    });
                }
            });

            this.$inertia.get('/find-an-expert/' + this.expertType.id, submit, {
                preserveState: true,
                onError: (errors) => {
                    this.errors = errors;

                    if (errors) {
                        this.popupActive = true;
                        this.showExpertFilters = true;
                        console.log('errors found: ', errors);
                    }
                },
                onSuccess: () => {
                    this.errors = {};
                },
            });
        },
        locationChanged(location) {
            let loc = JSON.parse(location.value);
            this.userLocation = loc;
            this.updateResults();
        },
        back() {
            this.$inertia.visit('/find-an-expert/');
        },
    },
    computed: {
        filterList() {
            return [
                ...this.activeFilters.sector,
                ...this.activeFilters.speciality,
            ];
        },
        hasActiveFilters() {
            //has propeties in active filters
            return Object.keys(this.activeFilters).length > 0;
        },
    },
};
</script>
