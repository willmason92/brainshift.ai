<template>
    <div class="shed-solution-section">
        <h2 class="h4 shed-solution-mb-1">
            {{ $t('Your new agricultural building') }}
        </h2>
        <h1 class="h2 shed-solutions-mb-large">
            {{ $t('What is the size of your project?') }}
        </h1>

        <p
            :class="{
                'info-message': !lengthError,
                'error-message': lengthError,
            }"
        >
            The shed length should be between {{ lengthRange }}
        </p>

        <p
            :class="{
                'info-message': !widthError,
                'error-message': widthError,
            }"
        >
            The shed width should be between {{ widthRange }}
        </p>

        <br />

        <p
            class="error-message"
            v-if="activeErrors"
            v-for="error in activeErrors"
        >
            {{ error }}
        </p>

        <SizeInput
            inputName="size"
            :defaultUnit="0"
            @valueChanged="valueChanged"
        />

        <ContinueButton
            text="Continue"
            :isEnabled="isSubmissionAllowed"
            :isFixed="true"
            @continue="submitValues"
        />
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import NumberInput from '../../../../Shared/Inputs/NumberInput.vue';
import ToggleBox from '../../../../Shared/Inputs/ToggleBox.vue';
import SizeInput from '../../../../Shared/Inputs/SizeInput.vue';
import ContinueButton from '../../../../Shared/Inputs/ContinueButton.vue';

export default {
    emits: ['valueChanged', 'continue'],
    props: {
        errors: Object,
    },
    components: {
        Link,
        NumberInput,
        ToggleBox,
        SizeInput,
        ContinueButton,
    },
    data() {
        return {
            units: [
                { id: 0, name: 'Meters' },
                { id: 1, name: 'Feet' },
            ],
            isSubmissionAllowed: false,
            formValues: {
                length: 0,
                width: 0,
                unit: 'Feet',
            },
            widthError: null,
            lengthError: null,
            widthRange: '17.8 and 60 meters.',
            lengthRange: '20 and 100 meters.',
        };
    },
    methods: {
        valueChanged(change) {
            if (change.value.unit === 1) {
                this.widthRange = '58.4 and 196.65 feet.';
                this.lengthRange = '65.62 and 328.08 feet.';
            } else {
                this.widthRange = '17.8 and 60 meters.';
                this.lengthRange = '20 and 100 meters.';
            }

            this.formValues.length = change.value.length;
            this.formValues.width = change.value.width;
            this.formValues.unit = change.value.unit;
            this.$emit('valueChanged', change);

            this.widthError = false;
            this.lengthError = false;

            if (
                this.formValues.unit == 0 &&
                this.formValues.length &&
                (this.formValues.length < 20 || this.formValues.length > 100)
            ) {
                this.lengthError = true;
            }

            if (
                this.formValues.unit == 0 &&
                this.formValues.width &&
                (this.formValues.width < 17.8 || this.formValues.width > 60)
            ) {
                this.widthError = true;
            }

            if (
                this.formValues.unit == 1 &&
                this.formValues.length &&
                (this.formValues.length < 65.62 ||
                    this.formValues.length > 328.08)
            ) {
                this.lengthError = true;
            }

            if (
                this.formValues.unit == 1 &&
                this.formValues.width &&
                (this.formValues.width < 58.4 || this.formValues.width > 196.65)
            ) {
                this.widthError = true;
            }

            if (
                this.formValues.length &&
                !this.lengthError &&
                this.formValues.width &&
                !this.widthError
            ) {
                this.isSubmissionAllowed = true;
            } else {
                this.isSubmissionAllowed = false;
            }
        },
        submitValues() {
            if (this.isSubmissionAllowed) {
                // set a default value
                this.$emit('valueChanged', {
                    input: 'unit',
                    value: this.formValues.unit,
                });
                this.$emit('continue');
            }
        },
    },
    computed: {
        activeErrors() {
            return Object.values(this.errors).filter((error) => error != null);
        },
    },
};
</script>
