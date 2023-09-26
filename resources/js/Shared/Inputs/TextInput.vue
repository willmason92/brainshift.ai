<template>
    <div class="input-column">
        <p
            class="error-message"
            v-if="error || (localError && textValue.length > 3)"
        >
            {{ error || localError }}
        </p>

        <p v-if="format == 'url'" class="info-message">
            {{ $t("Please ensure the link starts with 'https://'") }}.
        </p>

        <input
            :readonly="readOnly"
            class="text-input input-element"
            type="text"
            :name="inputName"
            :placeholder="placeholder"
            v-model="textValue"
            :maxlength="max"
            @change="validateInput"
            @input="localError = ''"
        />
    </div>
</template>

<script>
export default {
    emits: ['valueChanged'],
    props: {
        inputName: String,
        placeholder: String,
        error: String,
        value: String,
        max: Number,
        format: String,
        readOnly: Boolean,
    },
    data() {
        return {
            textValue: this.value,
            localError: null,
        };
    },
    methods: {
        validateInput() {
            if (this.format) {
                // test the input against a regex
                switch (this.format) {
                    case 'url':
                        let urlRegex =
                            /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/;
                        if (this.textValue.match(urlRegex)) {
                            this.localError = null;
                        } else {
                            this.localError = 'Please enter a valid URL.';
                        }
                }
            }
        },
    },
    methods: {
        validateInput() {
            if (this.format) {
                // test the input against a regex
                switch (this.format) {
                    case 'url':
                        let urlRegex =
                            /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/;
                        if (this.textValue.match(urlRegex)) {
                            this.localError = null;
                        } else {
                            this.localError = 'Please enter a valid URL.';
                        }
                }
            }
        },
    },
    watch: {
        textValue: {
            handler: function () {
                this.$emit('valueChanged', {
                    input: this.inputName,
                    value: this.textValue,
                });
            },
        },
    },
};
</script>
