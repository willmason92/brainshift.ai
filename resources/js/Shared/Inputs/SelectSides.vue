<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>
    <div class="select-sides">
        <div class="select-side">
            <ul>
                <li
                    v-for="option in selected"
                    class="select-side-option"
                    :class="{ highlighted: highlighted.includes(option) }"
                    @click="highlightOption(option)"
                >
                    {{ option.outcode }}
                </li>
            </ul>
        </div>

        <div class="select-sides__controls">
            <span
                type="button"
                class="select-side-control button-reset button--no-bg"
                @click="addOptions"
            >
                <img
                    width="25"
                    height="25"
                    src="../../../images/select-sides-add.svg"
                    :alt="$t('Add highlighted options')"
                    :title="$t('Add highlighted options')"
                />
            </span>
            <span
                type="button"
                class="select-side-control button-reset button--no-bg"
                @click="removeOptions"
            >
                <img
                    width="25"
                    height="25"
                    src="../../../images/select-sides-remove.svg"
                    :alt="$t('Remove highlighted options')"
                    :title="$t('Remove highlighted options')"
                />
            </span>
            <span
                type="button"
                class="select-side-control button-reset button--no-bg"
                @click="addAll"
            >
                <img
                    width="25"
                    height="25"
                    src="../../../images/select-sides-add-all.svg"
                    :alt="$t('Add all options')"
                    :title="$t('Add all options')"
                />
            </span>
            <span
                type="button"
                class="select-side-control button-reset button--no-bg"
                @click="removeAll"
            >
                <img
                    width="25"
                    height="25"
                    src="../../../images/select-sides-remove-all.svg"
                    :alt="$t('Remove all options')"
                    :title="$t('Remove all options')"
                />
            </span>
        </div>

        <div class="select-side">
            <ul>
                <li
                    v-for="option in sortedOptions"
                    class="select-side-option"
                    :class="{
                        highlighted: highlighted.includes(option),
                        selected: selected.includes(option),
                    }"
                    @click="highlightOption(option)"
                >
                    {{ option.outcode }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    emits: ['valueChanged'],
    props: {
        inputName: String,
        error: String,
        options: Array,
        defaultOptions: Array,
    },
    mounted() {
        if (this.defaultOptions) {
            this.defaultOptions.forEach((option) => {
                this.selected.push(option);
            });
        } else {
            this.selected = [];
        }
    },
    data() {
        return {
            selected: [],
            highlighted: [],
            initialised: false,
        };
    },
    methods: {
        highlightOption(option) {
            if (this.highlighted.includes(option)) {
                this.highlighted.splice(this.highlighted.indexOf(option), 1);
            } else {
                this.highlighted.push(option);
            }
        },

        addOptions() {
            this.highlighted.forEach((option) => {
                // if already in selected, don't add
                if (this.selected.includes(option)) {
                    return;
                } else {
                    this.selected.push(option);
                }
            });

            this.highlighted = [];
        },

        addAll() {
            this.selected = [...this.options, ...this.defaultOptions];
        },

        removeOptions() {
            this.selected = this.selected.filter(
                (option) => !this.highlighted.includes(option)
            );

            this.highlighted = [];
        },

        removeAll() {
            this.selected = [];
        },
    },
    computed: {
        sortedOptions() {
            let allOptions = [...this.options, ...this.defaultOptions];

            return allOptions.sort((optionA, optionB) => {
                if (
                    optionA.outcode.toLowerCase() <
                    optionB.outcode.toLowerCase()
                ) {
                    return -1;
                }
                if (
                    optionA.outcode.toLowerCase() >
                    optionB.outcode.toLowerCase()
                ) {
                    return 1;
                }
                return 0;
            });
        },
    },
    watch: {
        selected: {
            deep: true,
            handler: function () {
                // default option exists, therefore...
                if (this.defaultOptions?.length) {
                    // ignore first call, as defaults set up
                    if (this.initialised) {
                        this.$emit('valueChanged', {
                            input: this.inputName,
                            value: this.selected,
                        });
                    }
                } else {
                    this.$emit('valueChanged', {
                        input: this.inputName,
                        value: this.selected,
                    });
                }
                this.initialised = true;
            },
        },
    },
};
</script>
