<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>
    <div class="single-checkbox input-element" :class="{ wrapperClass, small }">
        <label class="single-checkbox__input">
            <input
                class="single-checkbox__checkbox"
                type="checkbox"
                :name="inputName"
                :checked="value"
                @change="changeValue"
            />
            <span class="single-checkbox__checkmark"></span>
            <span class="single-checkbox__label h7" v-if="label">{{
                label
            }}</span>
        </label>
    </div>
</template>

<script>
export default {
    emits: ['valueChanged'],
    props: {
        option: String,
        inputName: String,
        label: String,
        error: String,
        wrapperClass: String,
        small: Boolean,
        value: Boolean,
    },
    data() {
        return {
            checked: false,
        };
    },
    methods: {
        changeValue() {
            this.checked = !this.checked;
        },
    },
    watch: {
        checked: {
            handler: function () {
                this.$emit('valueChanged', {
                    input: this.inputName,
                    value: this.checked,
                });
            },
        },
    },
};
</script>
