<template>
    <layout-default>
        <div class="filter-navigation">
            <Link
                href="/farmers-library"
                class="filter-navigation__content-filters button button--small button--no-bg"
                :data="{ activeType: activeType }"
            >
                <span class="content-filters-arrow"> </span>
                <span>{{ $t('overview') }}</span>
            </Link>

            <button
                class="filter-navigation__library-filters button button--small button--no-bg"
                @click="openPopup('showLibraryFilters')"
            >
                {{ $t('Filter') }}
            </button>
        </div>

        <h1>{{ $t('Farmers Library') }}</h1>

        <div class="category-title-bar" v-if="activeFilters.type">
            <h2 class="category-title-bar__title">
                {{ postTypeName }}
            </h2>
            <button
                class="category-title-bar__reset button-reset"
                type="button"
                v-if="filterListWithoutType.length > 0"
                @click="resetFilters"
            >
                {{ $t('Reset filters') }}
            </button>
        </div>

        <div class="active-filters">
            <button
                class="active-filter pill button-reset"
                type="button"
                v-for="filter of filterListWithoutType"
                :key="filter"
                @click="removeFilter(filter)"
            >
                <span class="active-filter__name">
                    {{ filter.name }}
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
            <FarmersFilters
                v-if="showLibraryFilters"
                :activeFilters="activeFilters"
                :activeFilterList="activeFilterList"
                @addFilter="addFilter"
                @removeCategory="removeCategory"
                @closePopup="closePopup"
                @updatePosts="updatePosts"
                :filters="filters"
            ></FarmersFilters>
        </Popup>

        <ArticleList v-if="activeType" :posts="posts">
            <template #cta v-if="shopUrl">
                <div class="card card--light">
                    <h2>
                        {{ $t('Request a Sample') }}
                    </h2>
                    <div class="large-text">
                        <p>
                            {{
                                $t(
                                    'Would you like to see the texture and color in real life?'
                                )
                            }}
                        </p>
                    </div>
                    <a
                        :href="shopUrl"
                        class="button button--full button--thin button--fill button-reset"
                        target="_blank"
                    >
                        {{ $t('Request a Sample') }}
                    </a>
                </div>
            </template>
        </ArticleList>
        <ViewAll v-else :typePosts="typePosts" />
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import InertiaLink from '@inertiajs/inertia-vue3/src/link';
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';
import LayoutDefault from '../../Layout/Base.vue';
import FarmersFilters from './FarmersFilters.vue';
import Popup from '../../Shared/Popup.vue';
import ArticleList from '../../Shared/ArticleList.vue';
import ViewAll from './ViewAll.vue';

export default {
    components: {
        InertiaLink,
        LayoutDefault,
        Link,
        FarmersFilters,
        Popup,
        ArticleList,
        ViewAll,
    },
    props: {
        posttypes: Array,
        activeType: String,
        posts: Object,
        typePosts: Array,
        shopUrl: String,
        filters: Object,
    },
    data(props) {
        return {
            activeFilterList: {
                tags: [],
            },
            activeFilters: this.activeType ? { type: this.activeType } : {},
            popupActive: false,
            showContentFilters: this.activeType ? false : true,
            showLibraryFilters: false,
            types: [
                {
                    categoryName: this.$t('Type of content'),
                    categorySlug: 'type',
                    filters: this.posttypes,
                },
            ],
        };
    },
    mounted() {
        const currentType = this.posttypes.filter(
            (type) => type.id == this.activeType
        )[0];
        if (currentType) {
            currentType['category'] = 'type';
            this.addFilter(currentType);
        }
        localStorage.farmersUrl = window.location.pathname + window.location.search
    },
    methods: {
        openPopup(target) {
            this.popupActive = true;

            if (target == 'showContentFilters') {
                this.showContentFilters = true;
            }
            if (target == 'showLibraryFilters') {
                this.showLibraryFilters = true;
            }
        },

        closePopup() {
            this.popupActive = false;
            this.showLibraryFilters = false;
            this.showContentFilters = false;
        },

        addFilter(filter) {
            if (typeof this.activeFilterList[filter.category] == 'undefined') {
                this.activeFilterList[filter.category] = [];
            }
            if (filter.category === 'type') {
                this.activeFilterList[filter.category] = {
                    id: filter.id,
                    name: filter.name,
                    category: filter.category,
                };
            } else {
                let isInActiveFilterList = false;
                this.activeFilterList[filter.category].forEach(
                    (activeFilter) => {
                        if (activeFilter.id == filter.id) {
                            isInActiveFilterList = true;
                        }
                    }
                );

                if (!isInActiveFilterList) {
                    this.activeFilterList[filter.category].push({
                        id: filter.id,
                        name: filter.name,
                        category: filter.category,
                    });
                } else {
                    this.removeFilter(filter);
                }
            }
            this.updatePosts();
        },

        removeFilter(filter) {
            this.activeFilterList[filter.category] = this.activeFilterList[
                filter.category
            ].filter((activeFilter) => activeFilter.id != filter.id);

            this.updatePosts();
        },

        removeCategory(category) {
            if (typeof this.activeFilterList[category] != 'undefined') {
                if (category == 'type') {
                    delete this.activeFilterList[category];
                } else {
                    this.activeFilterList[category] = [];
                }
            }
            this.updatePosts();
        },

        resetFilters() {
            this.activeFilterList['tags'] = [];
            this.updatePosts();
        },

        updatePosts() {
            let submit = {};

            Object.keys(this.activeFilterList).forEach((category) => {
                if (category == 'type') {
                    submit.type = this.activeFilterList[category].id;
                } else {
                    if (typeof submit[category] == 'undefined') {
                        submit[category] = [];
                    }
                    this.activeFilterList[category].forEach((filter) => {
                        submit[category].push(filter.id);
                    });
                }
            });

            this.$inertia.get('/farmers-library/list', submit, {
                preserveState: true,
            });

            localStorage.farmersUrl = window.location.pathname + window.location.search
        },
    },
    computed: {
        postTypeName() {
            if (typeof this.activeFilterList.type != 'undefined') {
                return this.activeFilterList.type.name;
            }
            return 'Unknown';
        },
        filterListWithoutType() {
            return this.activeFilterList?.tags;
        },
    },
};
</script>
