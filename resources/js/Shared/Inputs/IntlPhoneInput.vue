<template>
    <p class="error-message" v-if="error || localError">
        {{ error || localError }}
    </p>
    <p class="info-message">
        {{
            $t(
                'Please enter your number in the international format, for example +44 1908 123 456.'
            )
        }}
    </p>
    <div
        class="intl-phone-input input-element"
        :class="{ wrapperClass, disabled }"
    >
        <span class="intl-phone-input__prefix">+</span>

        <input
            class="f-body-1 intl-phone-input__phone-number"
            type="text"
            :name="inputName"
            :placeholder="localPlaceholder"
            v-model="phoneNumber"
            @input="validateNumber"
            @blur="validateNumber"
            taxindex="0"
            :disabled="disabled"
        />
    </div>
</template>

<script>
import { usePage } from '@inertiajs/inertia-vue3';

export default {
    emits: ['valueChanged'],
    props: {
        inputName: String,
        placeholder: String,
        error: String,
        wrapperClass: String,
        value: String,
        disabled: Boolean,
        optional: Boolean,
    },
    mounted() {
        this.localPlaceholder = this.placeholder ? this.placeholder : '';

        if (this.value) {
            this.phoneNumber =
                this.value[0] == '+'
                    ? this.value.substring(1).trim()
                    : this.value.trim();

            if (!this.placeholder) {
                this.localPlaceholder =
                    this.value[0] == '+'
                        ? this.value.substring(1).trim()
                        : this.value.trim();
            }
        } else {
            if (usePage().props.value?.user?.phone) {
                let phoneNum = usePage().props.value.user.phone;
                if (phoneNum[0] == '+') {
                    phoneNum = String(phoneNum).substring(1);
                }
                this.phoneNumber = phoneNum;
            } else {
            }
        }
    },
    data() {
        return {
            phoneNumber: '',
            localError: '',
            localPlaceholder: '',
        };
    },
    methods: {
        validateNumber() {
            let fullPhoneNumber = null;

            // prepend '+' if missing to match backend validation
            if (this.phoneNumber) {
                fullPhoneNumber =
                    this.phoneNumber[0] == '+'
                        ? this.phoneNumber
                        : `+${this.phoneNumber}`;
            }

            // validate phone with regex
            let regex =
                /\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|4[987654310]|3[9643210]|2[70]|7|1)\W*\d\W*\d\W*\d\W*\d\W*\d\W*\d\W*\d\W*\d\W*(\d{1,2})$/;

            if (regex.test(fullPhoneNumber)) {
                this.localError = null;
                this.$emit('valueChanged', {
                    input: this.inputName,
                    value: fullPhoneNumber,
                    valid: true,
                });
            } else {
                if (
                    !this.optional ||
                    (this.optional &&
                        fullPhoneNumber &&
                        fullPhoneNumber.length > 1)
                ) {
                    this.localError = this.$t(
                        'Please enter a valid international phone number.'
                    );
                } else {
                    this.localError = '';
                }

                // emit a blank to invalidate continue button
                this.$emit('valueChanged', {
                    input: this.inputName,
                    value: '',
                    valid: fullPhoneNumber && fullPhoneNumber.length <= 1,
                });
            }
        },
    },
};
</script>
