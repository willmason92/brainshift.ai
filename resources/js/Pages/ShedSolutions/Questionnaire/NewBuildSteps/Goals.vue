<template>
    <div class="shed-solution-section">
        <h2 class="h4 shed-solution-mb-1">
            {{ $t('Your new agricultural building') }}
        </h2>
        <h1 class="h2">
            {{ $t('What is your goal you hope to achieve with the build') }}
        </h1>
        <p class="f-body-1 shed-solutions-mb-small">
            {{ $t('You can choose more than one') }}
        </p>

        <p class="error-message" v-if="Object.values(this.errors)[0]">
            {{ Object.values(errors)[0] }}
        </p>

        <SelectMultiple
            inputName="goals"
            :options="goals"
            overrideText="sector_name"
            @valueChanged="valueChanged"
        ></SelectMultiple>

        <ContinueButton
            :text="$t('Continue')"
            :isEnabled="isSubmissionAllowed"
            :isFixed="true"
            @continue="this.$emit('continue')"
        />
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import SelectMultiple from '../../../../Shared/Inputs/SelectMultiple.vue';
import ContinueButton from '../../../../Shared/Inputs/ContinueButton.vue';

export default {
    props: {
        goals: Array,
        errors: Object,
    },
    components: {
        Link,
        SelectMultiple,
        ContinueButton,
    },
    data() {
        return {
            formValues: {
                goals: [],
            },
            isSubmissionAllowed: false,

            // goals: [
            //     { id: 1, name: this.$t('Improving my yield') },
            //     { id: 2, name: this.$t('Modernising the farm') },
            //     { id: 3, name: this.$t('Lower the cost of maintenance') },
            //     { id: 4, name: this.$t('Increase herd size') },
            //     { id: 5, name: this.$t('Improve animal health & welfare') },
            // ],
        };
    },
    methods: {
        valueChanged(change) {
            this.$emit('valueChanged', change);

            this.formValues = {
                ...this.formValues,
                [change.input]: change.value,
            };

            if (this.formValues.goals.length > 0) {
                this.isSubmissionAllowed = true;
            } else {
                this.isSubmissionAllowed = false;
            }
        },
    },
};
</script>
