<template>
    <layout-default>
        <div class="farmers-filters-header">
            <Link href="/" class="popup__back button-reset">
                {{ $t('Home') }}
            </Link>
        </div>
        <div>
            <h1 class="h4 f-sans">{{ $t('Farmers library') }}</h1>
        </div>
        <div class="farmers-filters">
            {{ activeType }}
            <div class="farmers-filters__content">
                <h2 class="farmers-filters__title h2">
                    {{ $t("Tell us what information you're looking for") }}:
                </h2>
                <p class="f-title-1">{{ $t('Type of content') }}</p>
                <div class="select-boxes">
                    <div
                        class="filter-group"
                        v-for="category in types"
                        :key="category"
                    >
                        <span class="select-boxes__title">{{
                            filterTitle
                        }}</span>
                        <div class="select-boxes__boxes">
                            <button
                                v-for="filter in category.filters"
                                :key="filter"
                                class="button-reset select-box"
                                :class="{ active: filter.id == activeId }"
                                @click="setTarget(filter.id)"
                            >
                                {{ filter.name }}
                            </button>

                            <button
                                class="button-reset select-box"
                                :class="{ active: activeId == '' }"
                                @click.prevent="setTarget()"
                            >
                                {{ $t('View all') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <Link
                :href="targetLink"
                preserve-state
                :class="{ disabled: !(activeId || activeId == '') }"
                class="button button--fill button--span"
                id="continue-button"
            >
                {{ $t('Continue') }}
            </Link>
        </div>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../Layout/Base.vue';

export default {
    components: {
        Link,
        LayoutDefault,
    },
    props: {
        filters: Array,
        filterTitle: String,
        posttypes: Array,
    },
    data() {
        return {
            targetLink: '/farmers-library/list',
            activeId: null,
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
        let params = new URLSearchParams(document.location.search);
        this.activeId = params.get('activeType');

        if (this.activeId) {
            this.targetLink = `/farmers-library/list?type=${this.activeId}`;
        }
    },
    methods: {
        clearFilters(category) {
            this.$emit('removeCategory', category);
        },

        closeFilters() {
            this.$emit('closePopup');
        },

        addFilter(id, name, category) {
            this.$emit('addFilter', { id, name, category });
        },
        setTarget(id) {
            if (id) {
                this.targetLink = `/farmers-library/list?type=${id}`;
                this.activeId = id;
            } else {
                this.targetLink = '/farmers-library/list';
                this.activeId = '';
            }

            const continueBtn = document.querySelector('#continue-button')
            const btnPosition = continueBtn.getBoundingClientRect()
            document.querySelector('html').classList.add('smooth-scroll')
            setTimeout(() => {
                window.scrollTo(0, window.pageYOffset + btnPosition.top - window.innerHeight + btnPosition.height + 20)
            }, 1)
            setTimeout(() => {
                document.querySelector('html').classList.remove('smooth-scroll')
            }, 400)
        },
    },
};
</script>
