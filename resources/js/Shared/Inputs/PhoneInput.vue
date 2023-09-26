<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>
    <input
        class="phone-input f-body-1 input-element"
        :class="wrapperClass"
        type="text"
        :name="inputName"
        :placeholder="placeholder"
        v-model="phoneNumber"
    />
</template>

<script>
export default {
    emits: ['valueChanged'],
    props: {
        inputName: String,
        placeholder: String,
        error: String,
        wrapperClass: String,
        value: String,
    },
    data() {
        return {
          phoneNumber: this.value
        };
    },
    watch: {
        phoneNumber: {
            handler: function () {
                this.$emit('valueChanged', {
                    input: this.inputName,
                    value: this.phoneNumber,
                });
            },
        },
    },
};
</script>
