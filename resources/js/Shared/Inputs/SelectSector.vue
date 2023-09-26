<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>

    <button class="select-popup input-element" @click.prevent="this.isPopupActive = !this.isPopupActive">
        {{ outputText ? outputText : $t('Choose your sector') }}
    </button>

    <Popup :active="isPopupActive" @active-state="closePopup">
        <h1>{{ title }}</h1>
        <SelectSingle
            :title="title"
            :inputName="inputName"
            :options="sectors"
            :error="error"
            :defaultOption="values"
            :multiple="multiple"
            @valueChanged="toggleValue"
        ></SelectSingle>

        <ContinueButton text="Confirm" :isFixed="true" :isEnabled="this.values.length > 0" @continue="closePopup">
        </ContinueButton>
    </Popup>
</template>

<script>
import Popup from '../Popup.vue'
import SelectSingle from './SelectSingle.vue'
import ContinueButton from './ContinueButton.vue'
import { usePage } from '@inertiajs/inertia-vue3'

export default {
    emits: ['valueChanged'],
    components: {
        Popup,
        SelectSingle,
        ContinueButton,
    },
    props: {
        inputName: String,
        text: String,
        title:  String,
        error: String,
        multiple: Boolean,
        selectedSectors: Object,
    },
    data() {
        return {
            values: [],
            valuesText: [],
            isPopupActive: false,
            sectors: usePage().props.value.sectors,
            selectedSectorsObj: [],
        }
    },
    mounted() {
        let selectedSectors = {
            value: []
        }

        if (this.selectedSectors) {
            if (this.multiple) {
                this.selectedSectors.forEach(selectedSector => {
                    selectedSectors['value'].push(selectedSector.id)
                })
                this.toggleValue(selectedSectors)
                this.values = selectedSectors.value
                let selectedOption = this.sectors.filter((sector) => sector['id'] === selectedSectors)[0]

                if (selectedOption) {
                    this.valuesText.push(selectedOption.name)
                }
            } else {
                this.values = [this.selectedSectors]
                let selectedOption = this.sectors.filter((sector) => sector['id'] === this.selectedSectors)[0]

                if (selectedOption) {
                    this.valuesText.push(selectedOption.name)
                }
            }
        }
        this.sectors.forEach((sector, i) => {
            this.sectors[i]['image'] = require(`../../../images/sector-icons/icon-${sector.name.toLowerCase() }.svg`).default
        })
    },
    methods: {
        valueChanged() {
            if (!this.multiple || this.multiple === false) {
                this.$emit('valueChanged', {
                    input: this.inputName,
                    value: this.values[0],
                })
            } else {
                this.$emit('valueChanged', {
                    input: this.inputName,
                    value: this.values,
                })
            }
        },
        toggleValue(obj) {
            // const selectedSector = this.sectors.filter((sector) => sector['id'] === obj['value']).pop();

            this.values = []
            this.valuesText = []

            if (obj.value) {
                if (this.multiple) {
                    obj.value.forEach(value => {
                        if (!this.values.includes(value)) {
                            this.values.push(value)
                            this.valuesText.push(this.sectors.filter((sector) => sector['id'] === value)[0].name)
                        }
                    })
                } else {
                    if (!this.values.includes(obj.value)) {
                        this.values.push(obj.value)
                        let selectedOption = this.sectors.filter((sector) => sector['id'] === obj.value)[0]

                        if (selectedOption) {
                            this.valuesText.push(selectedOption.name)
                        }
                    }

                }
            }
            this.valueChanged()
        },
        closePopup() {
            this.isPopupActive = false
        },
    },
    computed: {
        outputText() {
            if (this.valuesText.length > 0) {
                return this.valuesText.join(', ')
            } else if (this.text) {
                return this.text
            }
            return null
        }
    }
}
</script>
