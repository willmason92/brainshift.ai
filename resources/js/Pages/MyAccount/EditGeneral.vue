<template>
    <layout-default>
        <div class="farmers-filters-header">
            <Link href="/my-account" class="popup__back button-reset">
                {{ $t('My Account') }}
            </Link>
        </div>
        <h1 class="h4 f-sans">
            {{ $t('My account') }}
        </h1>
        <h2 class="h1--extra-margin">
            {{ $t('My account details') }}
        </h2>
        <p class="margin-bottom-2">
            {{ $t('updateDetails') }}
        </p>
        <div class="card">
            <form class="form-page" @submit.prevent="submitForm()">
                <div class="form-row">
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Name') }}
                        </span>
                        <TextInput
                            inputName="generalName"
                            :value="user.name"
                            :error="errors.generalName"
                            @valueChanged="valueChanged"
                        ></TextInput>
                    </label>
                </div>
                <div class="form-row">
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Email address') }}
                        </span>
                        <EmailInput
                            inputName="generalEmail"
                            :value="user.email"
                            :error="errors.generalEmail"
                            @valueChanged="valueChanged"
                        ></EmailInput>
                    </label>
                </div>

                <div class="form-row">
                    <p>
                        <strong>
                            {{ $t('Note:') }}
                        </strong>
                        {{ $t('requestChangeEmail') }}
                    </p>
                </div>
                <div class="form-row">
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Phone Number') }}
                        </span>
                        <IntlPhoneInput
                            inputName="generalPhone"
                            :value="user.phone"
                            :error="errors.generalPhone"
                            @valueChanged="valueChanged"
                        />
                    </label>
                </div>
                <div class="form-row">
                    <button
                        class="button button--clear button--span button-reset"
                        @click="deleteUserPopup(userId)"
                    >
                        {{ $t('Delete account') }}
                    </button>
                </div>

                <ContinueButton
                    :text="$t('Request changes')"
                    :isEnabled="isEnabled"
                    @continue="requestChangePopup"
                />
            </form>
        </div>

        <AlertBox
            v-if="confirmPopup"
            :active="confirmPopup"
            @active-state="closePopup"
        >
            <img
                class="login-alert__icon"
                src="../../../images/alert-outline.svg"
                alt=""
                width="60"
                height="60"
            />
            <h3 class="alert-box__title">
                {{ confirmPopupMode === 'change' ? $t('Are you sure you want to change your email?') : $t('Are you sure you want to delete your account?') }}
            </h3>
            <p v-if="confirmPopupMode === 'change'" class="m-top">
                {{
                    $t(
                        'You will be logged out and unable to log back in until the changes have been validated by Etex, which may take up to 2 business days.'
                    )
                }}
            </p>
            <button
                type="button"
                class="button button--fill button--span button--thin button-reset alert-box__cta"
                @click="submitForm(true)"
            >
                {{ confirmPopupMode === 'change' ? $t('Save changes') : $t('Delete account') }}
            </button>
            <button
                type="button"
                class="button button--span button--thin button-reset alert-box__cta"
                @click="closePopup"
            >
                {{ $t('Cancel') }}
            </button>
        </AlertBox>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { useForm } from '@inertiajs/inertia-vue3';
import Popup from '../../Shared/Popup.vue';
import LayoutDefault from '../../Layout/Base.vue';
import TextInput from '../../Shared/Inputs/TextInput.vue';
import EmailInput from '../../Shared/Inputs/EmailInput.vue';
import PhoneInput from '../../Shared/Inputs/PhoneInput.vue';
import Switch from '../../Shared/Inputs/Switch.vue';
import IntlPhoneInput from '../../Shared/Inputs/IntlPhoneInput.vue';
import ContinueButton from '../../Shared/Inputs/ContinueButton.vue';
import AlertBox from '../../Shared/AlertBox.vue';

export default {
    components: {
        LayoutDefault,
        Link,
        Popup,
        TextInput,
        EmailInput,
        PhoneInput,
        Switch,
        IntlPhoneInput,
        ContinueButton,
        AlertBox,
    },
    props: {
        user: Object,
        userId: Number,
        errors: Object,
        delete_key: String,
    },
    data() {
        return {
            hasChanged: false,
            isEnabled: false,
            confirmPopup: false,
            confirmPopupMode: 'change',
            currentEmail: this.user?.email,
        };
    },
    setup(props) {
        const form = useForm({
            generalName: props.user.name,
            generalEmail: props.user.email,
            generalPhone: props.user.phone,
        });

        return { form };
    },
    computed: {
        isLoggedIn: function () {
            return this.user !== null;
        },
        isInstaller: function () {
            return this.isLoggedIn && this.user.roles.includes('installer');
        },
        isFarmer: function () {
            return this.isLoggedIn && this.user.roles.includes('farmer');
        },
    },
    methods: {
        valueChanged(change) {
            this.form = {
                ...this.form,
                [change.input]: change.value,
            };
            this.hasChanged = true;

            this.isEnabled = Boolean(
                this.hasChanged &&
                    this.form.generalEmail &&
                    this.form.generalPhone
            );
        },
        submitForm(isConfirmed) {
            if (this.confirmPopupMode === 'change'){
                    if (
                    this.confirmPopup ||
                    this.form.generalEmail == this.currentEmail
                ) {
                    this.form.post('/my-account/edit-general', {
                        preserveScroll: true,
                        preserveState: true,
                        replace: true,
                    });
                    this.closePopup();
                } else {
                    this.confirmPopupMode = 'change';
                    this.confirmPopup = true;
                }
            } else {
                // is delete request
                if (this.confirmPopup && isConfirmed) {
                    this.deleteUser(this.userId);
                    this.closePopup();
                } else {
                    this.confirmPopupMode = 'delete';
                    this.confirmPopup = true;
                }
            }


        },
        deleteUser(id) {
            this.form
                .transform((data) => ({
                    ...data,
                    _deleteKey: this.delete_key,
                }))
                .post('/my-account/delete-user');
        },
        deleteUserPopup() {
            this.confirmPopupMode = 'delete';
            this.submitForm();
        },
        requestChangePopup(){
            this.confirmPopupMode = 'change';
            this.submitForm();
        },
        closePopup() {
            this.confirmPopup = false;
            this.confirmPopupMode = 'change';
        },
    },
};
</script>
