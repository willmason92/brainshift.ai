<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>
    <div class="select-boxes__boxes input-element">
        <label
            class="select-box"
            v-for="(value, name) in options"
            :key="name"
            :class="{ active: this.values.includes(name) }"
        >
            <input
                type="radio"
                :value="name"
                :name="name"
                @click="toggleValue(name)"
            />
            <span class="checkbox-label">
                {{ value }}
            </span>
        </label>

        <label
            v-if="hasClear"
            class="select-box"
            :key="name"
            :class="{ active: this.values.length == 0 }"
        >
            <input
                type="radio"
                :value="name"
                :name="name"
                @click="clearValues"
            />
            <span class="checkbox-label">
                {{ clearText || 'Clear' }}
            </span>
        </label>
    </div>
</template>

<script>
export default {
    emits: ['valueChanged'],
    props: {
        options: Object,
        inputName: String,
        error: String,
        multiple: Boolean,
        hasClear: Boolean,
        clearText: String,
    },
    data() {
        return {
            values: [],
        };
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
        toggleValue(value) {
            if (!this.values.includes(value)) {
                if (this.multiple) {
                    this.values.push(value);
                } else if (!this.multiple) {
                    this.clearValues();
                    this.values.push(value);
                }
            } else {
                this.values = this.values.filter((val) => val != value);
            }
            this.valueChanged();
        },
        clearValues() {
            this.values = [];
            this.valueChanged();
        },
    },
};
</script>
