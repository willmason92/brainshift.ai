<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>
    <div class="dropdown-input-wrapper" :class="`dropdown-input-${inputName}`">
        <div class="dropdown-input input-element" @click="toggleDropdown">
            <span>{{ currentValue || placeholder || 'None selected' }}</span>
            <svg
                width="10"
                height="10"
                viewBox="0 0 10 10"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                style="margin-left: 1rem"
            >
                <path
                    d="M2.5426 3.33337C2.09512 3.33337 1.85596 3.86042 2.15063 4.19717L4.45137 6.82662C4.74191 7.15862 5.25837 7.15862 5.54891 6.82662L7.84966 4.19717C8.14433 3.86042 7.90516 3.33337 7.45771 3.33337H2.5426Z"
                    fill="#212121"
                />
            </svg>
        </div>
        <ul class="dropdown-input__dropdown" v-show="dropdownOpen">
            <li
                class="dropdown-input-item"
                tabindex="0"
                v-for="value in options"
                :key="value.id"
            >
                <SingleCheckbox
                    :small="true"
                    inputName="activeOption"
                    :label="value.text"
                    :value="value.id == option"
                    @valueChanged="valueChanged(value)"
                />
            </li>
        </ul>
    </div>
</template>
<script>
import SingleCheckbox from './SingleCheckbox.vue';
export default {
    emits: ['valueChanged'],
    components: {
        SingleCheckbox,
    },
    props: {
        inputName: String,
        options: Array,
        defaultOption: Array,
        placeholder: String,
        error: String,
    },
    mounted() {
        this.option = this.defaultOption;
        document.addEventListener('click', (e) => {
            // if click not within filters, close filters
            if (!e.target.closest(`.dropdown-input-${this.inputName}`)) {
                this.dropdownOpen = false;
            }
        });
    },
    data() {
        return {
            option: null,
            dropdownOpen: false,
        };
    },
    methods: {
        toggleDropdown() {
            this.dropdownOpen = !this.dropdownOpen;
            console.log('trying to open dropdown', this.dropdownOpen);
        },
        closeDropdown() {
            this.dropdownOpen = false;
        },
        valueChanged(value) {
            // if value is being filtered, remove
            if (this.option == value.id) {
                this.option = null;
            } else {
                this.option = value.id;
            }
            this.$emit('valueChanged', {
                input: this.inputName,
                value: this.option,
            });
        },
        isChecked(value) {
            return value == this.option;
        },
    },
    computed: {
        currentValue() {
            return (
                this.options.filter((option) => option.id == this.option)[0]
                    ?.text || 'No value selected'
            );
        },
    },
};
</script>
