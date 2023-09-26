<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>
    <input
        class="password-input input-element"
        type="password"
        :placeholder="placeholder"
        :name="inputName"
        v-model="passwordValue"
    />
</template>

<script>
export default {
    emits: ['valueChanged'],
    props: {
        inputName: String,
        placeholder: String,
        error: String,
    },
    data() {
        return {
            passwordValue: '',
        };
    },
    watch: {
        passwordValue: {
            handler: function () {
                this.$emit('valueChanged', {
                    input: this.inputName,
                    value: this.passwordValue,
                });
            },
        },
    },
};
</script>
