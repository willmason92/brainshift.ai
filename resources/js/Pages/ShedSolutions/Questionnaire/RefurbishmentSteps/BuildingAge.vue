<template>
    <div class="shed-solution-section">
        <h2 class="h4 shed-solution-mb-1">
            {{ $t('Your new agricultural building') }}
        </h2>
        <h1 class="h2 shed-solutions-mb-small">
            {{ $t('How old is your current building?') }}
        </h1>

        <p class="error-message" v-if="Object.values(this.errors)[0]">
            {{ Object.values(errors)[0] }}
        </p>

        <div class="form-row">
            <NumberInput
                wrapperClass="number-input--half"
                placeholder="0.00"
                inputName="buildingAge"
                :label="$t('Years')"
                :min="0"
                max="500"
                :error="ageError"
                :disabled="ageDisabled"
                @valueChanged="valueChanged"
            ></NumberInput>
        </div>

        <div class="form-row">
            <SingleCheckbox
                inputName="unknownAge"
                :label="$t('I don\'t know the age of the building')"
                @valueChanged="valueChanged"
            ></SingleCheckbox>
        </div>

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
import NumberInput from '../../../../Shared/Inputs/NumberInput.vue';
import SingleCheckbox from '../../../../Shared/Inputs/SingleCheckbox.vue';
import ContinueButton from '../../../../Shared/Inputs/ContinueButton.vue';

export default {
    emits: ['valueChanged'],
    props: {
        errors: Object,
    },
    components: {
        Link,
        NumberInput,
        SingleCheckbox,
        ContinueButton,
    },
    data() {
        return {
            units: ['meter', 'feet'],
            isSubmissionAllowed: false,
            formValues: {
                buildingAge: null,
                unknownAge: false,
            },
            ageError: null,
            ageDisabled: false,
        };
    },
    methods: {
        valueChanged(change) {
            this.ageError = null;

            this.formValues = {
                ...this.formValues,
                [change.input]: change.value,
            };

            if (this.formValues.unknownAge) {
                this.ageDisabled = true;
            } else {
                this.ageDisabled = false;
            }

            if (
                (this.formValues.buildingAge &&
                    this.formValues.buildingAge > 0 &&
                    this.formValues.buildingAge < 500) ||
                this.formValues.unknownAge
            ) {
                this.isSubmissionAllowed = true;
                this.$emit('valueChanged', {
                    input: 'buildingAge',
                    value: this.formValues.buildingAge,
                });
                this.$emit('valueChanged', {
                    input: 'unknownAge',
                    value: this.formValues.unknownAge,
                });
            } else {
                if (this.formValues.buildingAge < 0) {
                    this.ageError = this.$t(
                        'Please enter a number above zero.'
                    );
                } else if (this.formValues.buildingAge > 500) {
                    this.ageError = this.$t('Please enter a number below 500.');
                }
                this.isSubmissionAllowed = false;
            }
        },
    },
};
</script>
