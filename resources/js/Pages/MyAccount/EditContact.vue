<template>
    <layout-default :bgColor="'#fff'">
        <div class="farmers-filters-header">
            <Link href="/my-account" class="popup__back button-reset">
                {{ $t('My Account') }}
            </Link>
        </div>
        <h1 class="h4 f-sans">
            {{ $t('My account') }}
        </h1>
        <h2 class="h1--extra-margin">
            {{ $t('Contact Preferences') }}
        </h2>
        <div>
            <form class="form-page" @submit.prevent="submitForm()">
                <div class="form-row">
                    <Switch
                        inputName="canContact"
                        :label="
                            $t('I want to be notified of requests by email')
                        "
                        :defaultValue="user.contact_by_email"
                        wrapperClass="switch--flipped"
                        @valueChanged="valueChanged"
                    ></Switch>
                </div>
                <ContinueButton
                    :text="$t('Save changes')"
                    :isEnabled="hasChanged"
                    @continue="submitForm"
                />
            </form>
        </div>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { useForm } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../Layout/Base.vue';
import Switch from '../../Shared/Inputs/Switch.vue';
import EmailInput from '../../Shared/Inputs/EmailInput.vue';
import PhoneInput from '../../Shared/Inputs/PhoneInput.vue';
import IntlPhoneInput from '../../Shared/Inputs/IntlPhoneInput.vue';
import ContinueButton from '../../Shared/Inputs/ContinueButton.vue';

export default {
    components: {
        LayoutDefault,
        Link,
        Switch,
        EmailInput,
        PhoneInput,
        IntlPhoneInput,
        ContinueButton,
    },
    props: {
        user: Object,
        errors: Object,
    },
    data() {
        return {
            hasChanged: false,
        };
    },
    setup(props) {
        const form = useForm({
            canContact: props.user.canContact,
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
        },
        submitForm() {
            console.log(this.form);
            this.form.post('/my-account/edit-contact', {
                preserveScroll: true,
            });
        },
    },
};
</script>
