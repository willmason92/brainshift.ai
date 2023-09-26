<template>
    <p class="error-message" v-if="error?.length">
        {{ error.length }}
    </p>
    <p class="error-message" v-if="error?.width">
        {{ error.width }}
    </p>
    <p class="error-message" v-if="error?.unit">
        {{ error.unit }}
    </p>

    <div class="form-row form-row--split-2">
        <div class="form-group">
            <NumberInput
                placeholder="0.00"
                inputName="length"
                :label="$t('Length')"
                :min="0"
                :defaultValue="value.length"
                @valueChanged="valueChanged"
            ></NumberInput>
        </div>

        <div class="form-group">
            <NumberInput
                placeholder="0.00"
                inputName="width"
                :label="$t('Width')"
                :min="0"
                :defaultValue="value.width"
                @valueChanged="valueChanged"
            ></NumberInput>
        </div>
    </div>

    <ToggleBox
        inputName="unit"
        :options="units"
        :defaultValue="value.unit"
        @valueChanged="valueChanged"
    ></ToggleBox>
</template>

<script>
import NumberInput from './NumberInput.vue';
import ToggleBox from './ToggleBox.vue';

export default {
    emits: ['valueChanged'],
    components: {
        NumberInput,
        ToggleBox,
    },
    props: {
        inputName: String,
        placeholder: String,
        label: String,
        error: String,
        defaultLength: Number,
        defaultWidth: Number,
        defaultUnit: Number,
    },
    data() {
        return {
            value: {
                length: this.defaultLength ? this.defaultLength : null,
                width: this.defaultWidth ? this.defaultWidth : null,
                unit: this.defaultUnit != null ? this.defaultUnit : null,
            },
            units: [
                {
                    id: 0,
                    name: 'meter',
                },
                {
                    id: 1,
                    name: 'feet',
                },
            ],
            error: {},
        };
    },
    watch: {
        value: {
            deep: true,
            handler: function () {
                this.$emit('valueChanged', {
                    input: this.inputName,
                    value: this.value,
                });
            },
        },
    },
    methods: {
        valueChanged(change) {
            this.error = {};

            this.value = {
                ...this.value,
                [change.input]: change.value,
            };

            if (this.value.length < 0) {
                this.error.length = `${this.$t('Length must be a valid positive number.')}`;
            }
            if (this.value.width < 0) {
                this.error.width = `${this.$t('Width must be a valid positive number.')}`;
            }
        },
    },
};
</script>
