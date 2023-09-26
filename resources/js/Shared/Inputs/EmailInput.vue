<template>
    <p class="error-message" v-if="error || errorText">
        {{ error ? error : errorText }}
    </p>
    <input
        class="email-input input-element"
        type="email"
        :name="inputName"
        :placeholder="placeholder"
        :disabled="disabled"
        v-model="emailValue"
        @blur="validateEmail(true)"
        @input="resetValidate"
    />
</template>

<script>
export default {
    emits: ['valueChanged'],
    props: {
        inputName: String,
        placeholder: String,
        disabled: Boolean,
        error: String,
        value: String,
    },
    data() {
        return {
            errorText: null,
            emailValue: this.value,
        };
    },
    watch: {
        emailValue: {
            handler: function () {
                if (this.validateEmail()) {
                    this.$emit('valueChanged', {
                        input: this.inputName,
                        value: this.emailValue,
                    });
                } else {
                    this.$emit('valueChanged', {
                        input: this.inputName,
                        value: '',
                    });
                }
            },
        },
    },
    methods: {
        validateEmail: function (showError = false) {
            if (this.emailValue.length > 3) {
                let emailRegex =
                    /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
                if (emailRegex.test(this.emailValue)) {
                    this.errorText = null;
                    return true;
                } else {
                    if (showError) {
                        this.errorText = 'Please enter a valid email!';
                    }
                    return false;
                }
            } else {
                this.errorText = null;
                return false;
            }
        },
        resetValidate: function () {
            this.errorText = null;
        },
    },
};
</script>
