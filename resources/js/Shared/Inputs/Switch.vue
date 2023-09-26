<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>
    <label class="switch input-element" :class="wrapperClass">
        <input
            @change="toggleValue"
            :name="inputName"
            :id="inputName"
            :checked="value"
            type="checkbox"
        />
        <div class="switch__slider"></div>
        <span v-if="label" class="input-label">{{ label }}</span>
    </label>
</template>

<script>
export default {
    emits: ['valueChanged'],
    props: {
        inputName: String,
        label: String,
        error: String,
        wrapperClass: String,
        defaultValue: Number,
    },
    data() {
        return {
            value: this.defaultValue === 1,
        };
    },
    methods: {
        toggleValue() {
            this.value = !this.value;
            this.$emit('valueChanged', {
                input: this.inputName,
                value: this.value,
            });
        },
    },
};
</script>
