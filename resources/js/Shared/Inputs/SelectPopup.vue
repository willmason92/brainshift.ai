<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>

    <button
        class="select-popup input-element"
        @click.prevent="this.isPopupActive = !this.isPopupActive"
    >
        {{ outputText }}
    </button>

    <Popup :active="isPopupActive" @active-state="closePopup">
        <template #default>
            <slot></slot>

            <br /><br />

            <div class="select-boxes__boxes input-element">
                <label
                    class="select-box"
                    v-for="option in options"
                    :key="option.id"
                    :class="{ active: this.values.includes(option.id) }"
                >
                    <input
                        type="radio"
                        :value="option.id"
                        :name="option.name"
                        @click="toggleValue(option.id, option.name)"
                    />
                    <span class="checkbox-label">
                        {{ option.name }}
                    </span>
                </label>
            </div>

            <ContinueButton
                text="Confirm"
                :isFixed="true"
                :isEnabled="this.values.length > 0"
                @continue="closePopup"
            ></ContinueButton>
        </template>
    </Popup>
</template>

<script>
import Popup from '../Popup.vue';
import ContinueButton from './ContinueButton.vue';

export default {
    emits: ['valueChanged'],
    components: {
        Popup,
        ContinueButton,
    },
    props: {
        text: String,
        options: Object,
        inputName: String,
        error: String,
        multiple: Boolean,
        selectedValues: Array,
    },
    data() {
        return {
            values: this.selectedValues ? this.selectedValues : [],
            valuesText: [],
            isPopupActive: false,
        };
    },
    mounted() {
        let selectedSector = []

        if (this.selectedValues) {
            if (this.multiple) {
                this.selectedValues.forEach(option => {
                  if ( option !== null ){
                    this.valuesText.push(this.options.filter((val) => val.id == option)[0].name)
                  }
                })
            } else {
              if ( this.selectedValues[0] !== null ){
                this.valuesText.push(this.options.filter((val) => val.id == this.selectedValues)[0].name)
              }
            }
        }
    },
    methods: {
        valueChanged() {
            if (!this.multiple || this.multiple == false) {
                this.$emit('valueChanged', {
                    input: this.inputName,
                    value: this.values[0],
                });
            } else {
                this.$emit('valueChanged', {
                    input: this.inputName,
                    value: this.values,
                });
            }
        },
        toggleValue(name, value) {
            if (!this.values.includes(name)) {
                if (this.multiple) {
                    this.values.push(name);
                    this.valuesText.push(value);
                } else if (!this.multiple) {
                    this.values = [];
                    this.valuesText = [];
                    this.values.push(name);
                    this.valuesText.push(value);
                }
            } else {
                this.values = this.values.filter((val) => val != name);
                this.valuesText = this.valuesText.filter((val) => val != value);
            }
            this.valueChanged();
        },
        closePopup() {
            this.isPopupActive = false;
        },
    },
    computed: {
        outputText() {
            if (this.valuesText.length > 0) {
                return this.valuesText.join(', ')
            } else if (this.text) {
                return this.text
            } else {
                return 'Open'
            }
        }
    }
};
</script>
