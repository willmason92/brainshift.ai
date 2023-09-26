<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>

    <div class="form-row form-row--end">
        <div class="form-group">
            <NumberInput
                :placeholder="placeholder"
                inputName="amount"
                :defaultValue="value.amount"
                @valueChanged="valueChanged"
            ></NumberInput>
        </div>

        <div class="form-group form-group--no-shrink">
            <ToggleBox
                inputName="unit"
                :options="units"
                :defaultValue="value.unit"
                @valueChanged="valueChanged"
            ></ToggleBox>
        </div>
    </div>
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
        error: String,
        defaultAmount: Number,
        defaultUnit: Number
    },
    data() {
        return {
            value: {
                amount: this.defaultAmount ? this.defaultAmount : null,
                unit: this.defaultUnit != null ? this.defaultUnit : null,
            },
            units: [
                {
                    id: 0,
                    name: 'km',
                },
                {
                    id: 1,
                    name: 'miles',
                }
            ],
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
            this.value = {
                ...this.value,
                [change.input]: change.value,
            };
        },
    },
};
</script>
