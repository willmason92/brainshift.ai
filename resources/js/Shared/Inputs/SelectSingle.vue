<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>
    <div class="select-options input-element" :class="wrapperClass">
        <label
            class="select-option"
            v-for="option in options"
            :class="{
                active: isSelected(option.id),
                disabled: option?.disabled,
            }"
        >
            <div class="select-option__image" v-if="option.image">
                <img :src="option.image" alt="" />
            </div>

            <div class="select-option__pre" v-if="pre">
                <slot name="pre"></slot>
            </div>

            <input
                @click="valueChanged(option)"
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
    emits: ['valueChanged', 'nextPage'],
    props: {
        options: Array,
        inputName: String,
        image: String,
        pre: Boolean,
        error: String,
        wrapperClass: String,
        title: String,
        defaultOption: Object,
        multiple: Boolean,
    },
    data() {
        return {
            selectedOptions: this.multiple ? [] : null,
        };
    },
    methods: {
        valueChanged(option) {
            if (option?.disabled) {
                return;
            }

            if (this.defaultOption) {
                this.selectedOptions = this.defaultOption;
            }
            if (this.multiple) {
                if (
                    this.selectedOptions.length > 0 &&
                    this.selectedOptions.includes(option.id)
                ) {
                    this.selectedOptions = this.selectedOptions.filter(
                        (value) => value != option.id
                    );
                } else {
                    this.selectedOptions.push(option.id);
                }
            } else {
                if (this.selectedOptions == option.id) {
                    this.selectedOptions = null;
                } else {
                    this.selectedOptions = option.id;
                }
            }
            this.$emit('valueChanged', {
                input: this.inputName,
                value: this.selectedOptions,
            });
            this.nextPage();
        },
        isSelected(id) {
            const selectedOption = this.defaultOption
                ? this.defaultOption
                : this.selectedOptions;
            if (selectedOption != null) {
                if (this.multiple) {
                    if (!selectedOption || selectedOption.length == 0) {
                        return false;
                    }
                    return selectedOption.includes(id);
                } else {
                    if (typeof selectedOption == 'object') {
                        return selectedOption[0] === id;
                    } else {
                        return selectedOption === id;
                    }
                }
            }
        },
        nextPage() {
            this.$emit('nextPage');
        },
    },
};
</script>
