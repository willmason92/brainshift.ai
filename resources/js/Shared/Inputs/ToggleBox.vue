<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>
    <div class="toggle-box input-element" :class="wrapperClass">
        <label
            class="toggle-box-option"
            :class="this.selectedOption == option.id ? 'active' : ''"
            v-for="option in options"
            :key="option.id"
        >
            <input
                @click="valueChanged(option.id)"
                type="radio"
                :value="option.id"
            />
            <span class="toggle-box-option__label"> {{ option.name }} </span>
        </label>
    </div>
</template>

<script>
export default {
    emits: ['valueChanged'],
    props: {
        options: Array,
        inputName: String,
        error: String,
        wrapperClass: String,
        defaultValue: Number,
    },
    mounted() {
        this.valueChanged(this.defaultValue);
    },
    data() {
        return {
            selectedOption: null,
        };
    },
    methods: {
        valueChanged(option) {
            this.selectedOption = option;
            this.$emit('valueChanged', {
                input: this.inputName,
                value: this.selectedOption,
            });
        },
    },
};
</script>
