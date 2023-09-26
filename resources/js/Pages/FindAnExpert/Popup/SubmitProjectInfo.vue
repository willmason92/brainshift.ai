<template>
    <div class="form-page">
        <p class="h4" style="margin-bottom: 1rem">
            {{ $t(`Contact ${expertType}`) }}
        </p>
        <h2 class="h2 expert-contact__title">
            {{
                isAttached
                    ? $t('Info about your project')
                    : $t('Your contact request')
            }}
        </h2>

        <div v-if="isAttached">
            <p class="f-title-1">{{ $t(`Your project`) }}</p>
            <div class="expert-contact__attached">
                <svg
                    class="attached-project__icon"
                    width="19"
                    height="19"
                    viewBox="0 0 19 19"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M14.3019 10.5865L15.5169 9.37151C16.0423 8.84456 16.3377 8.13132 16.3377 7.38696C16.3377 6.64328 16.0423 5.92929 15.5169 5.40241C14.9899 4.87694 14.2767 4.58229 13.5323 4.58229C12.7879 4.58229 12.0747 4.87694 11.5478 5.40241L8.49448 8.45495V8.45569C8.49003 8.4594 8.48632 8.46385 8.48186 8.46756L6.96705 9.98238C6.54698 10.4047 6.54698 11.0875 6.96705 11.5098C7.38936 11.9299 8.07217 11.9299 8.49448 11.5098L12.1586 7.84565L12.1579 7.84491C12.2781 7.718 12.4451 7.64526 12.6195 7.6423C12.7947 7.64007 12.9632 7.70835 13.0864 7.8323C13.2103 7.9555 13.2786 8.12398 13.2764 8.29913C13.2741 8.47428 13.2014 8.64054 13.0737 8.76077L9.4096 12.4257H9.41034C8.96502 12.871 8.36089 13.1211 7.73078 13.1196C7.10066 13.1211 6.49652 12.871 6.05121 12.4257C5.12496 11.4972 5.12496 9.99425 6.05121 9.06572L9.71536 5.40233L10.6312 4.48647C12.2306 2.88631 14.8342 2.88705 16.4329 4.48647C18.0323 6.08589 18.0323 8.68794 16.4329 10.2874L13.3796 13.3414C13.3744 13.3466 13.3692 13.3511 13.364 13.3563L11.2421 15.4789V15.4782C10.1496 16.5722 8.66585 17.186 7.12001 17.183C5.57404 17.186 4.09046 16.5722 2.99796 15.4782C1.90102 14.3813 1.29688 12.9176 1.29688 11.3569C1.29688 9.7961 1.90028 8.33173 2.99796 7.23486L7.88381 2.34901C8.13914 2.11003 8.53768 2.11744 8.78483 2.36386C9.03124 2.61101 9.03791 3.00955 8.79968 3.26488L3.91383 8.15073C3.06327 8.9998 2.58753 10.1547 2.59274 11.357C2.59274 12.5719 3.06181 13.7105 3.91383 14.5625C4.76364 15.4138 5.91776 15.8895 7.12008 15.8843C8.33503 15.8843 9.47361 15.4152 10.3263 14.5632L14.2964 10.5932L14.3031 10.5872L14.3019 10.5865Z"
                        fill="#E60000"
                    />
                </svg>
                <span class="attached-project__title" v-if="projectText">
                    {{ projectText }}
                </span>
            </div>
        </div>

        <div v-if="!isAttached">
            <div class="expert-contact__row">
                <p class="f-title-1 m-bot">{{ $t(`Your project`) }}</p>
                <a
                    class="expert-contact__edit f-body-3"
                    @click.prevent="editProjectType"
                    >{{ $t('Edit') }}</a
                >
            </div>
            <div class="expert-contact__tag">
                <p class="m-bot--2 tag tag--green f-body-4">
                    {{ projectText }}
                </p>
            </div>

            <div class="expert-contact__row">
                <p class="f-title-1 m-bot">{{ $t(`Your Sector`) }}</p>
                <a
                    class="expert-contact__edit f-body-3"
                    @click.prevent="editSector"
                    >{{ $t('Edit') }}</a
                >
            </div>
            <div class="expert-contact__tag">
                <p class="m-bot--2 tag tag--green f-body-4">
                    {{ sectorText }}
                </p>
            </div>
        </div>

        <p class="f-title-1 m-bot">{{ $t(`Message to the ${expertType}`) }}</p>

        <TextAreaInput
            :inputName="'message'"
            :placeholder="$t('Message')"
            :error="messageError"
            @valueChanged="valueChanged"
        />

        <p class="f-title-1 m-bot m-top">
            {{ $t('How would you like to be contacted?') }}
        </p>

        <p class="h7 m-bot--tiny">{{ $t('By email') }}</p>
        <EmailInput
            :inputName="'email'"
            :placeholder="$t('Email')"
            :error="emailError"
            :value="form.email"
            @valueChanged="valueChanged"
        />

        <p class="h7 m-bot--tiny m-top--1">{{ $t('By phone (optional)') }}</p>
        <IntlPhoneInput
            inputName="phone"
            :placeholder="$t('Phone number')"
            :error="phoneError"
            :value="form.phone"
            :optional="true"
            @valueChanged="valueChanged"
        />

        <p class="f-title-1 m-bot m-top--1">{{ $t('Permissions') }}</p>
        <div class="permissions-wrapper m-bot">
            <Switch
                inputName="permission"
                @valueChanged="valueChanged"
            ></Switch>
            <p class="permissions-wrapper__text">
                {{
                    $t(
                        'By clicking the checkbox you are giving Eternit consent to use your data to contact you regarding projects created in this platform and for marketing purposes. You also acknowledge and agree with our privacy policy. Please note that you have the right to withdraw your consent at any time by sending an email to'
                    )
                }}
                <a href="mailto:info@brainshift.ai">info@brainshift.ai</a>
            </p>
        </div>

        <ContinueButton
            :isFixed="true"
            :isEnabled="isSubmittable"
            :text="$t('Continue')"
            @continue="submitRequest"
        />
    </div>
</template>

<script>
import { Link, usePage } from '@inertiajs/inertia-vue3';
import SelectSingle from '../../../Shared/Inputs/SelectSingle.vue';
import TextAreaInput from '../../../Shared/Inputs/TextAreaInput.vue';
import EmailInput from '../../../Shared/Inputs/EmailInput.vue';
import PhoneInput from '../../../Shared/Inputs/PhoneInput.vue';
import SelectBox from '../../../Shared/Inputs/SelectBox.vue';
import ContinueButton from '../../../Shared/Inputs/ContinueButton.vue';
import Switch from '../../../Shared/Inputs/Switch.vue';
import IntlPhoneInput from '../../../Shared/Inputs/IntlPhoneInput.vue';

export default {
    components: {
        Link,
        SelectSingle,
        TextAreaInput,
        EmailInput,
        PhoneInput,
        IntlPhoneInput,
        SelectBox,
        ContinueButton,
        Switch,
    },
    props: {
        expert: Object,
        projectText: String,
        sectorText: String,
        isAttached: Boolean,
        form: Object,
    },
    data() {
        return {
            wrapperClass: 'project',
            isSubmittable: false,
            isPhoneValid: true,
        };
    },
    emits: ['editProjectType', 'editSector', 'closePopup', 'submitRequest'],
    methods: {
        editProjectType() {
            this.$emit('editProjectType');
        },
        editSector() {
            this.$emit('editSector');
        },
        valueChanged(change) {
            this.$emit('valueChanged', change);

            if (change.input == 'phone') {
                this.isPhoneValid = change.valid;
            }

            this.setIsSubmittable();
        },
        submitRequest() {
            this.$emit('submitRequest');
        },
        setIsSubmittable() {
            if (
                this.form.message &&
                this.form.email &&
                this.form.permission &&
                this.isPhoneValid
            ) {
                this.isSubmittable = true;
            } else {
                this.isSubmittable = false;
            }
        },
    },
    computed: {
        expertType() {
            return this.expert.expert_type.name || null;
        },

        messageError() {
            return this.form?.errors?.message?.[0] || null;
        },

        emailError() {
            return this.form?.errors?.email?.[0] || null;
        },

        phoneError() {
            // return this.form?.errors?.phone?.[0] || null;
            return usePage().props.value?.errors?.phone || null;
        },

        permissionError() {
            return this.form?.errors?.permission?.[0] || null;
        },
    },
};
</script>
