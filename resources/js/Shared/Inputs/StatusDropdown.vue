<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>
    <div class="status-dropdown" :class="{ active: isOpen }">
        <button
            class="status-dropdown__trigger"
            :class="activeOption?.color || defaultOption?.color || 'grey'"
            type="button"
            @click="toggleDropdown"
        >
            <span>
                {{
                    activeOption?.value ||
                    defaultOption?.value ||
                    options.filter((opt) => opt.id == defaultOption.id).value
                }}
            </span>

            <svg
                width="19"
                height="19"
                viewBox="0 0 19 19"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M15.1429 8.26505L14.0599 7.20105L9.53793 11.818L4.92093 7.29605L3.85693 8.39805L9.57593 13.965L15.1429 8.26505Z"
                    fill="white"
                />
            </svg>
        </button>
        <ul class="status-dropdown__list">
            <li
                class="status-dropdown-item"
                v-for="option in options.filter(
                    (option) => option.id != activeOption?.id
                )"
                @click="setValue(option)"
            >
                {{ option.value }}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    emits: ['valueChanged', 'checkStatus'],
    props: {
        // takes in an array of objects
        // with id, value, color
        inputName: String,
        defaultOption: Object,
        options: Array,
        // null if disabled, id if enabled
        isEnabled: Number,
        skipEnabledCheck: {
            type: Boolean,
            default: true,
        },
    },
    mounted() {
        // if default value set, select it
        if (this.defaultOption != null) {
            this.activeOption = this.options.filter(
                (opt) => opt.id == this.defaultOption.id
            )[0];
        }

        document.addEventListener('click', (e) => {
            if (this.isOpen && !e.target.closest('.status-dropdown')) {
                this.toggleDropdown();
            }
        });
    },
    data() {
        return {
            activeOption: null,
            isOpen: false,
            initialised: false,
        };
    },
    methods: {
        setValue(option) {
            this.$emit('valueChanged', {
                input: this.inputName,
                value: option,
            });

            if (this.isEnabled != null || this.skipEnabledCheck) {
                this.activeOption = option;
                this.isOpen = false;
            }
        },
        toggleDropdown() {
            this.isOpen = !this.isOpen;
        },
    },
    watch: {
        defaultOption: {
            deep: true,
            handler: function () {
                this.activeOption = this.options.filter(
                    (opt) => opt.id == this.defaultOption.id
                )[0];
                if (this.initialised) {
                    this.toggleDropdown();
                }
            },
        },
    },
};
</script>
