<template>
    <div class="expert-filters">
        <div class="expert-filters__content">
            <h2 class="h2">{{ $t('Filter experts') }}:</h2>
            <h3 class="f-body-1 m-bot--2">
                {{ $t('Filter to find what you’re looking for') }}:
            </h3>
            <form class="select-boxes">
                <div class="filter-group">
                    <span class="select-boxes__title">{{
                        $t('Location')
                    }}</span>
                    <p class="error-message" v-if="errors.location">
                        {{ errors.location }}
                    </p>
                    <LocationInput
                        inputName="location"
                        :title="$t('Choose a location')"
                        @valueChanged="locationChanged"
                    />
                </div>

                <div
                    class="filter-group"
                    v-for="category in filterOn"
                    :key="category.slug"
                >
                    <span class="select-boxes__title">{{ category.name }}</span>
                    <div class="select-boxes__boxes">
                        <label
                            class="select-box"
                            v-for="filter in filters[category.slug]"
                            :key="filter"
                            :class="
                                this.activeFilters[category.slug].filter(
                                    (e) => e.id === filter.id
                                ).length
                                    ? 'active'
                                    : ''
                            "
                        >
                            <input
                                type="radio"
                                :value="filter.id"
                                @click="
                                    addFilter(
                                        filter.id,
                                        filter.name,
                                        category.slug
                                    )
                                "
                            />
                            <span class="checkbox-label">
                                {{ filter.name }}
                            </span>
                        </label>

                        <button
                            class="button-reset select-box"
                            :class="
                                this.activeFilters[category.slug].length == 0
                                    ? 'active'
                                    : ''
                            "
                            @click.prevent="clearFilters(category.slug)"
                        >
                            {{ $t('View all') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <ContinueButton
            :text="$t('Apply filter')"
            :isEnabled="isApplyEnabled"
            @continue="closeFilters"
        >
        </ContinueButton>
    </div>
</template>

<script>
import ContinueButton from '../../../Shared/Inputs/ContinueButton.vue';
import LocationInput from '../../../Shared/Inputs/LocationInput.vue';

export default {
    components: {
        ContinueButton,
        LocationInput,
    },
    props: {
        filters: Object,
        activeFilters: Object,
        filterOn: Array,
        errors: Object,
    },
    data() {
        return {
            hasChanged: false,
        };
    },
    methods: {
        closeFilters() {
            this.$emit('closePopup');
            this.hasChanged = false;
        },
        clearFilters(category) {
            this.hasChanged = true;
            this.$emit('clearFilters', category);
        },
        addFilter(id, name, category) {
            this.hasChanged = true;

            if (
                this.activeFilters[category].filter((e) => e.id === id).length
            ) {
                this.$emit('removeFilter', { id, name, category });
            } else {
                this.$emit('addFilter', { id, name, category });
            }
        },
        locationChanged(change) {
            this.$emit('locationChanged', change);
            this.hasChanged = true;
        },
    },
    computed: {
        isApplyEnabled() {
            return this.hasChanged && Object.keys(this.errors).length == 0;
        },
    },
};
</script>
