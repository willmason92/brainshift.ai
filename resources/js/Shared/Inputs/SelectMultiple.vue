<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>
    <div class="select-options input-element" :class="wrapperClass">
        <label
            class="select-option select-option--multiple"
            v-for="option in options"
            :class="isSelected(option.id)"
        >
            <div class="select-option__image" v-if="option.image">
                <img :src="option.image" alt="" />
            </div>

            <input
                @click="toggleOption(option.id)"
                type="radio"
                :value="option.id"
            />
            <span class="select-option__label h5"> {{ option.name }} </span>
            <div class="select-option__select"></div>
            <br />
        </label>
    </div>
</template>

<script>
export default {
    emits: ['valueChanged'],
    props: {
        options: Array,
        inputName: String,
        limit: Boolean,
        min: Number,
        image: null,
        error: String,
        wrapperClass: String,
    },
    data() {
        return {
            selectedOptions: [],
            isSubmissionAllowed: false,
        };
    },
    methods: {
        valueChanged() {
            this.$emit('valueChanged', {
                input: this.inputName,
                value: this.selectedOptions,
            });
        },
        toggleOption(id) {
            if (!this.selectedOptions.includes(id)) {
                if (
                    !this.limit ||
                    (this.limit && this.selectedOptions.length < this.limit)
                ) {
                    this.selectedOptions.push(id);
                }
            } else {
                this.selectedOptions = this.selectedOptions.filter(
                    (value) => value != id
                );
            }
            this.valueChanged();
        },
        isSelected(id) {
            return this.selectedOptions.includes(id) ? 'active' : '';
        },
    },
};
</script>
