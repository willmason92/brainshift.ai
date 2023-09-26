<template>
    <div class="number-input-wrapper">
        <p class="error-message" v-if="error">
            {{ error }}
        </p>
        <div class="number-input__label f-title-1" v-if="label">
            {{ label }}
        </div>
        <input
            class="number-input input-element"
            :class="wrapperClass"
            type="number"
            :name="inputName"
            :min="min"
            :max="max"
            v-model="number"
            :placeholder="placeholder"
            :disabled="disabled ? true : false"
        />
    </div>
</template>

<script>
export default {
    emits: ['valueChanged'],
    props: {
        inputName: String,
        placeholder: String,
        label: String,
        error: String,
        wrapperClass: String,
        defaultValue: [Number, String],
        min: [Number, String],
        max: [Number, String],
        disabled: Boolean,
    },
    data() {
        return {
            number: this.defaultValue,
        };
    },
    watch: {
        number: {
            handler: function () {
                this.$emit('valueChanged', {
                    input: this.inputName,
                    value: this.number,
                });
            },
        },
    },
};
</script>
