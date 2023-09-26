<template>
    <layout-default>
        <div class="find-an-expert">
            <Link href="/" class="popup__back button-reset m-bot--2">
                {{ $t('Home') }}
            </Link>

            <h1 class="h4 find-an-expert__subtitle">
                {{ $t('Find an expert') }}
            </h1>
            <h2 class="h2 find-an-expert__title">
                {{ $t("Tell us what information you're looking for") }}:
            </h2>
            <br />
            <h5 class="f-title-1 m-bot">{{ $t('Type of expert') }}</h5>

            <form class="select-boxes">
                <div class="filter-group">
                    <div class="select-boxes__boxes">
                        <label
                            class="select-box"
                            v-for="(filter, index) in filteredExpertTypes"
                            :key="filter.id"
                            :class="
                                this.activeType == filter.id ? 'active' : ''
                            "
                        >
                            <input
                                type="radio"
                                :value="filter.id"
                                v-model="activeType"
                            />
                            <span class="checkbox-label">
                                {{ filter.name }}
                            </span>
                        </label>
                    </div>
                </div>
            </form>

            <ContinueButton
                :text="$t('Continue')"
                :isEnabled="activeType"
                @continue="openDetailsPage"
            >
            </ContinueButton>
        </div>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../Layout/Base.vue';
import InertiaLink from '@inertiajs/inertia-vue3/src/link';
import ContinueButton from '../../Shared/Inputs/ContinueButton.vue';

export default {
    beforeMount() {
        this.filteredExpertTypes = this.expertTypes.filter(
            (expertType) => expertType.id != 6 && expertType.id != 7
        );
    },
    components: {
        LayoutDefault,
        Link,
        ContinueButton,
    },
    props: {
        expertTypes: Object,
    },
    data() {
        return {
            activeType: null,
            filteredExpertTypes: [],
        };
    },

    methods: {
        openDetailsPage() {
            if (this.activeType !== null) {
                this.$inertia.visit('/find-an-expert/' + this.activeType);
            }
        },
    },

    computed: {
        hasActiveType() {
            return this.activeType !== null;
        },
    },
};
</script>
