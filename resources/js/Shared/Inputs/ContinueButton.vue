<template>
    <button
        class="button button--fill button--span"
        :class="additionalClasses"
        :disabled="!isEnabled || isLoading"
        @click="continueClicked"
        type="button"
    >
        {{ text }}
    </button>
</template>

<script>
export default {
    emits: ['continue'],
    props: {
        text: String,
        isEnabled: Boolean,
        isFixed: Boolean,
        isAdmin: Boolean,
        isLoading: Boolean,
        wrapperClass: String,
    },
    methods: {
        continueClicked() {
            if (this.isEnabled) {
                this.$emit('continue');
            }
        },
    },
    computed: {
        additionalClasses() {
            let classBuilder = '';
            if (!this.isEnabled) classBuilder += 'disabled ';
            if (this.wrapperClass) classBuilder += this.wrapperClass;
            if (this.isFixed) classBuilder += ' button--continue ';
            if (this.isAdmin) classBuilder += ' admin ';
            if (this.isLoading) classBuilder += ' loading ';
            return classBuilder;
        },
    },
};
</script>
