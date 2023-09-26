<template>
    <div class="shed-solution-section">
        <h2 class="h4 shed-solution-mb-1">
            {{ $t('Your new agricultural building') }}
        </h2>
        <h1 class="h2">
            {{ $t('What’s the reason you want to refurb your building?') }}
        </h1>
        <p class="f-body-1 shed-solutions-mb-small">
            {{ $t('You can choose more than one') }}
        </p>

        <p class="error-message" v-if="Object.values(this.errors)[0]">
            {{ Object.values(errors)[0] }}
        </p>

        <SelectMultiple
            inputName="reasons"
            :options="reasons"
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
    emits: ['valueChanged'],
    props: {
        reasons: Array,
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
                reasons: [],
            },
            isSubmissionAllowed: false,
        };
    },
    methods: {
        valueChanged(change) {
            this.$emit('valueChanged', change);

            this.formValues = {
                ...this.formValues,
                [change.input]: change.value,
            };

            if (this.formValues.reasons.length > 0) {
                this.isSubmissionAllowed = true;
            } else {
                this.isSubmissionAllowed = false;
            }
        },
    },
};
</script>
