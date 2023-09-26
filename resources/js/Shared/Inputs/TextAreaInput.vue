<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>
    <textarea
        class="text-area-input input-element"
        :name="inputName"
        :id="inputName"
        rows="4"
        :placeholder="placeholder"
        v-model="message"
        :maxlength="max"
        @input="valueChanged"
    ></textarea>
</template>

<script>
export default {
    emits: ['valueChanged'],
    props: {
        inputName: String,
        placeholder: String,
        error: String,
        value: String,
        max: Number,
    },
    data() {
        return {
            message: this.value,
        };
    },
    watch: {
        message: {
            handler: function () {
                this.$emit('valueChanged', {
                    input: this.inputName,
                    value: this.message,
                });
            },
        },
    },
};
</script>
