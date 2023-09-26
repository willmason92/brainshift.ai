<template>
    <div class="material-picker">
        <p class="error-message" v-if="error">
            {{ error }}
        </p>

        <ul
            class="material-picker__selection"
            v-if="isAdmin && materials.length"
        >
            <li
                class="material-selection"
                v-for="material in materials.filter((material) =>
                    values.includes(material.id)
                )"
            >
                <img
                    class="material-selection__image"
                    :src="material.image"
                    width="60"
                    height="60"
                    :alt="material.name"
                />
                <h4 class="material-selection__name">{{ material.name }}</h4>
            </li>
        </ul>

        <div
            class="material-picker__button"
            v-if="isAdmin"
            @click.prevent="this.isPopupActive = !this.isPopupActive"
        >
            <div class="material-picker__button-inner">
                <svg
                    width="22"
                    height="22"
                    viewBox="0 0 22 22"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M11.418 0.0830078C10.5895 0.0830078 9.91797 0.754595 9.91797 1.58301V9.08301H2.41797C1.58956 9.08301 0.917969 9.75458 0.917969 10.583C0.917969 11.4115 1.58956 12.083 2.41797 12.083H9.91797V19.583C9.91797 20.4114 10.5895 21.083 11.418 21.083C12.2464 21.083 12.918 20.4114 12.918 19.583V12.083H20.418C21.2464 12.083 21.918 11.4115 21.918 10.583C21.918 9.75458 21.2464 9.08301 20.418 9.08301H12.918V1.58301C12.918 0.754595 12.2464 0.0830078 11.418 0.0830078Z"
                        fill="white"
                    />
                </svg>
            </div>
        </div>

        <button
            v-if="!isAdmin"
            class="select-popup input-element"
            @click.prevent="this.isPopupActive = !this.isPopupActive"
        >
            {{ outputText ? outputText : $t('Choose your materials') }}
        </button>

        <Popup
            :active="isPopupActive && !isAdmin"
            @active-state="closePopup"
            v-if="!isAdmin"
        >
            <slot></slot>
            <br /><br />
            <SelectSingle
                :title="title"
                :inputName="inputName"
                :options="materials"
                :error="error"
                :multiple="multiple"
                :defaultOption="values"
                @valueChanged="toggleValue"
            ></SelectSingle>

            <ContinueButton
                text="Confirm"
                :isFixed="true"
                :isEnabled="this.values.length > 0"
                @continue="closePopup"
            >
            </ContinueButton>
        </Popup>

        <AlertBox
            :active="isPopupActive && isAdmin"
            @active-state="closePopup"
            title="Add product"
        >
            <div class="admin-material-picker">
                <div class="input-wrapper">
                    <div class="material-picker__search-icon">
                        <svg
                            width="12"
                            height="12"
                            viewBox="0 0 12 12"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M5.25 0.21875C7.75256 0.21875 9.78125 2.24746 9.78125 4.75C9.78125 5.83244 9.40169 6.82619 8.76844 7.6055L11.8314 10.6686C12.0145 10.8516 12.0145 11.1484 11.8314 11.3314C11.6651 11.4979 11.4046 11.513 11.2211 11.3768L11.1686 11.3314L8.1055 8.26844C7.32619 8.90169 6.33244 9.28125 5.25 9.28125C2.74746 9.28125 0.71875 7.25256 0.71875 4.75C0.71875 2.24746 2.74746 0.21875 5.25 0.21875ZM5.25 1.15625C3.26522 1.15625 1.65625 2.76522 1.65625 4.75C1.65625 6.73475 3.26522 8.34375 5.25 8.34375C7.23475 8.34375 8.84375 6.73475 8.84375 4.75C8.84375 2.76522 7.23475 1.15625 5.25 1.15625Z"
                                fill="#212121"
                            />
                        </svg>
                    </div>
                    <input
                        class="materials-picker__search text-input input-element"
                        type="text"
                        name="filterMaterials"
                        v-model="materialFilter"
                    />
                </div>

                <SelectSingle
                    :title="title"
                    :inputName="inputName"
                    :options="filteredMaterials"
                    :error="error"
                    :multiple="multiple"
                    :defaultOption="values"
                    @valueChanged="toggleValue"
                ></SelectSingle>

                <div class="admin-material-picker__continue">
                    <button
                        class="button button--fill button--span button--thin"
                        @click="closePopup"
                    >
                        {{ $t('Done') }}
                    </button>
                </div>
            </div>
        </AlertBox>
    </div>
</template>

<script>
import Popup from '../Popup.vue';
import SelectSingle from './SelectSingle.vue';
import ContinueButton from './ContinueButton.vue';
import { usePage } from '@inertiajs/inertia-vue3';
import AlertBox from '../AlertBox.vue';

export default {
    emits: ['valueChanged'],
    components: {
        Popup,
        SelectSingle,
        ContinueButton,
        AlertBox,
    },
    props: {
        inputName: String,
        text: String,
        title: String,
        error: String,
        multiple: Boolean,
        options: Array,
        selectedOption: Object,
        isAdmin: Boolean,
    },
    data() {
        return {
            materials: usePage().props.value.materials,
            materialFilter: null,
            values: [],
            valuesText: null,
            isPopupActive: false,
            selectedOptionObj: this.selectedOption
                ? this.selectedOption.id
                : null,
        };
    },
    mounted() {
        let selectedOption = {
            input: this.inputName,
            value: [],
        };

        this.materials.forEach((material, index) => {
            this.materials[index]['image'] = material.file.url;
        });

        if (this.selectedOption) {
            this.selectedOptionId = this.selectedOption.id;
            this.selectedOption.forEach((option) => {
                selectedOption['value'].push(option.id);
                this.toggleValue(selectedOption);
            });
        }
    },
    methods: {
        toggleValue(obj) {
            this.values = [];
            this.valuesText = null;
            if (this.multiple) {
                // @todo sort this to handle objects
                this.valuesText = [];
                obj.value.forEach((value) => {
                    const selectedOption = this.materials.filter(
                        (material) => material.id == value
                    );
                    this.values.push(selectedOption[0].id);
                    this.valuesText.push(selectedOption[0].name);
                });
                this.valuesText = this.valuesText.join(', ');
            } else if (obj.value) {
                const selectedOption = this.materials.filter(
                    (material) => material.id == obj.value
                );

                this.values[0] = selectedOption[0].id;
                this.valuesText = selectedOption[0].name;
            }

            this.$emit('valueChanged', {
                input: this.inputName,
                value: this.values,
            });
        },
        closePopup() {
            console.log('closing popup');
            this.isPopupActive = false;
        },
    },
    computed: {
        outputText() {
            if (this.valuesText) {
                return this.valuesText;
            } else if (this.text) {
                return this.text;
            }
            return null;
        },
        filteredMaterials() {
            if (this.materialFilter) {
                return this.materials.filter((material) =>
                    String(material.name)
                        .toLowerCase()
                        .includes(this.materialFilter.toLowerCase())
                );
            } else {
                return this.materials;
            }
        },
    },
};
</script>
