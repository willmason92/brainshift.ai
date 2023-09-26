<template>
    <layout-default>
        <div class="admin-back">
            <Link href="/" class="popup__back button-reset">
                {{ $t('Back to overview') }}
            </Link>
            <ContinueButton
                text="Save"
                :isEnabled="isLoaded"
                :isLoading="!isLoaded"
                wrapperClass="button--thin admin-title__cta"
                @continue="saveSettings"
            />
        </div>
        <h1 class="admin-title">{{ $t('General Settings') }}</h1>

        <div id="settingsContact" class="card admin-details-wrapper">
            <h2 class="admin-subtitle">
                {{ $t('Eternit Settings') }}
            </h2>

            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('Company name') }}
                </h3>
                <TextInput
                    inputName="contact.company_name"
                    :max="254"
                    :placeholder="$t('Enter a company name...')"
                    :value="form?.contact?.company_name"
                    :error="localErrors?.['contact.company_name']"
                    @valueChanged="valueChanged"
                />
            </label>

            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('Company group name') }}
                </h3>
                <TextInput
                    inputName="contact.company_group"
                    :max="254"
                    :placeholder="$t('Enter a company group name...')"
                    :value="form?.contact?.company_group"
                    :error="localErrors?.['contact.company_group']"
                    @valueChanged="valueChanged"
                />
            </label>

            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('Company VAT Number') }}
                </h3>
                <TextInput
                    inputName="contact.vat_number"
                    :max="254"
                    :placeholder="$t('Enter a VAT number...')"
                    :value="form?.contact?.vat_number"
                    :error="localErrors?.['contact.vat_number']"
                    @valueChanged="valueChanged"
                />
            </label>

            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('Contact Phone Number') }}
                </h3>
                <IntlPhoneInput
                    inputName="contact.phone"
                    :placeholder="
                        $t('Enter a valid international phone number...')
                    "
                    :value="form?.contact?.phone"
                    :error="localErrors?.['contact.phone']"
                    @valueChanged="valueChanged"
                />
            </label>
        </div>

        <div id="settingsAddress" class="card admin-details-wrapper">
            <h2 class="admin-subtitle">
                {{ $t('Eternit Address') }}
            </h2>

            <label class="admin-label">
                <h3 class="admin-label__text">{{ $t('Address Line 1') }}</h3>
                <TextInput
                    inputName="contact.address1"
                    :max="254"
                    :placeholder="$t('Enter an address...')"
                    :value="form?.contact?.address1"
                    :error="localErrors?.['contact.address1']"
                    @valueChanged="valueChanged"
                />
            </label>

            <div class="form-row--split-2">
                <div>
                    <label class="admin-label">
                        <h3 class="admin-label__text">{{ $t('City') }}</h3>
                        <TextInput
                            inputName="contact.city"
                            :max="254"
                            :placeholder="$t('Enter a city...')"
                            :value="form?.contact?.city"
                            :error="localErrors?.['contact.city']"
                            @valueChanged="valueChanged"
                        />
                    </label>
                </div>
                <div>
                    <label class="admin-label">
                        <h3 class="admin-label__text">{{ $t('Postcode') }}</h3>
                        <TextInput
                            inputName="contact.postcode"
                            :max="254"
                            :placeholder="$t('Enter a postcode...')"
                            :value="form?.contact?.postcode"
                            :error="localErrors?.['contact.postcode']"
                            @valueChanged="valueChanged"
                        />
                    </label>
                </div>
            </div>

            <label class="admin-label">
                <h3 class="admin-label__text">{{ $t('Country') }}</h3>
                <TextInput
                    inputName="contact.country"
                    :max="254"
                    :placeholder="$t('Enter a country...')"
                    :value="form?.contact?.country"
                    :error="localErrors?.['contact.country']"
                    @valueChanged="valueChanged"
                />
            </label>
        </div>

        <div id="settingsMaterials" class="card admin-details-wrapper">
            <h2 class="admin-subtitle">
                {{ $t('General Settings') }}
            </h2>
            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('General Contact Form URL') }}
                </h3>
                <TextInput
                    inputName="contact.contact_form_url"
                    :max="254"
                    format="url"
                    :placeholder="$t('Enter a contact form URL...')"
                    :value="form?.contact?.contact_form_url"
                    :error="localErrors?.['contact.contact_form_url']"
                    @valueChanged="valueChanged"
                />
            </label>

            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('Materials CTA Link') }}
                </h3>
                <TextInput
                    inputName="shop.materials_cta_link"
                    :max="254"
                    format="url"
                    :placeholder="$t('Enter a URL for the materials CTA...')"
                    :value="form?.shop?.materials_cta_link"
                    :error="localErrors?.['shop.materials_cta_link']"
                    @valueChanged="valueChanged"
                />
            </label>

            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('Copyright Footer Text') }}
                </h3>
                <TextInput
                    inputName="contact.footer_text"
                    :max="254"
                    :placeholder="$t('Enter some footer text...')"
                    :value="form?.contact?.footer_text"
                    :error="localErrors?.['contact.footer_text']"
                    @valueChanged="valueChanged"
                />
            </label>
        </div>

        <div id="settingsEmail" class="card admin-details-wrapper">
            <h2 class="admin-subtitle">
                {{ $t('Email and SMTP Settings') }}
            </h2>
            <p class="info-message">
                {{
                    $t(
                        'These settings are READ ONLY and can not bechanged via administration. They are provided for debugging purposes only.'
                    )
                }}
            </p>
            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('SendGrid Client ID') }}
                </h3>
                <TextInput
                    inputName="email.SENDGRID_API_KEY"
                    :max="254"
                    :value="form?.email?.SENDGRID_API_KEY"
                    :error="localErrors?.['email.SENDGRID_API_KEY']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('"New Installer Request" Message ID') }}
                </h3>
                <TextInput
                    inputName="email.SG_NEW_REQUEST_ID"
                    :max="254"
                    :value="form?.email?.SG_NEW_REQUEST_ID"
                    :error="localErrors?.['email.SG_NEW_REQUEST_ID']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('"Installer Project Updated" Message ID') }}
                </h3>
                <TextInput
                    inputName="email.SG_EDIT_PROJECT_ID"
                    :max="254"
                    :value="form?.email?.SG_EDIT_PROJECT_ID"
                    :error="localErrors?.['email.SG_EDIT_PROJECT_ID']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('"Installer Project Published" Message ID') }}
                </h3>
                <TextInput
                    inputName="email.SG_PUBLISH_PROJECT_ID"
                    :max="254"
                    :value="form?.email?.SG_PUBLISH_PROJECT_ID"
                    :error="localErrors?.['email.SG_PUBLISH_PROJECT_ID']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{
                        $t(
                            '"Farmer \'Shed Solution\' Share via Email" Message ID'
                        )
                    }}
                </h3>
                <TextInput
                    inputName="email.SG_SHARE_SOLUTION_ID"
                    :max="254"
                    :value="form?.email?.SG_SHARE_SOLUTION_ID"
                    :error="localErrors?.['email.SG_SHARE_SOLUTION_ID']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('"User Account Update Request" Message ID') }}
                </h3>
                <TextInput
                    inputName="email.SG_EDIT_USER_ID"
                    :max="254"
                    :value="form?.email?.SG_EDIT_USER_ID"
                    :error="localErrors?.['email.SG_EDIT_USER_ID']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('"Delete User Request" Message ID') }}
                </h3>
                <TextInput
                    inputName="email.SG_DELETE_USER_ID"
                    :max="254"
                    :value="form?.email?.SG_DELETE_USER_ID"
                    :error="localErrors?.['email.SG_DELETE_USER_ID']"
                    :readOnly="true"
                />
            </label>
        </div>

        <div id="settingsADB2C" class="card admin-details-wrapper">
            <h2 class="admin-subtitle">
                {{ $t('ADB2C Connection Settings') }}
            </h2>
            <p class="info-message">
                {{
                    $t(
                        'These settings are READ ONLY and can not bechanged via administration. They are provided for debugging purposes only.'
                    )
                }}
            </p>
            <label class="admin-label">
                <h3 class="admin-label__text">{{ $t('ADB2C Client ID') }}</h3>
                <TextInput
                    inputName="auth.ADB2C_CLIENTID"
                    :max="254"
                    :value="form?.auth?.ADB2C_CLIENTID"
                    :error="localErrors?.['auth.ADB2C_CLIENTID']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('ADB2C Client Secret') }}
                </h3>
                <TextInput
                    inputName="auth.ADB2C_CLIENTSECRET"
                    :max="254"
                    :value="form?.auth?.ADB2C_CLIENTSECRET"
                    :error="localErrors?.['auth.ADB2C_CLIENTSECRET']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">{{ $t('ADB2C Tenant') }}</h3>
                <TextInput
                    inputName="auth.ADB2C_TENANT"
                    :max="254"
                    :value="form?.auth?.ADB2C_TENANT"
                    :error="localErrors?.['auth.ADB2C_TENANT']"
                    :readOnly="true"
                />
            </label>
            <hr class="form-divider" />
            <label class="admin-label">
                <h3 class="admin-label__text">{{ $t('Login Policy') }}</h3>
                <TextInput
                    inputName="auth.ADB2C_POLICY"
                    :max="254"
                    :value="form?.auth?.ADB2C_POLICY"
                    :error="localErrors?.['auth.ADB2C_POLICY']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('Login Redirect URI') }}
                </h3>
                <TextInput
                    inputName="auth.ADB2C_REDIRECTURI"
                    :max="254"
                    :value="form?.auth?.ADB2C_REDIRECTURI"
                    :error="localErrors?.['auth.ADB2C_REDIRECTURI']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('Registration Policy') }}
                </h3>
                <TextInput
                    inputName="auth.ADB2C_REGISTER_POLICY"
                    :max="254"
                    :value="form?.auth?.ADB2C_REGISTER_POLICY"
                    :error="localErrors?.['auth.ADB2C_REGISTER_POLICY']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">
                    {{ $t('Registration Redirect URI') }}
                </h3>
                <TextInput
                    inputName="auth.ADB2C_REGISTER_REDIRECTURI"
                    :max="254"
                    :value="form?.auth?.ADB2C_REGISTER_REDIRECTURI"
                    :error="localErrors?.['auth.ADB2C_REGISTER_REDIRECTURI']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">{{ $t('Logout Policy') }}</h3>
                <TextInput
                    inputName="auth.ADB2C_LOGOUT_POLICY"
                    :max="254"
                    :value="form?.auth?.ADB2C_LOGOUT_POLICY"
                    :error="localErrors?.['auth.ADB2C_LOGOUT_POLICY']"
                    :readOnly="true"
                />
            </label>
            <hr class="form-divider" />
            <label class="admin-label">
                <h3 class="admin-label__text">{{ $t('ADB2C Domain') }}</h3>
                <TextInput
                    inputName="auth.ADB2C_DOMAIN"
                    :max="254"
                    :value="form?.auth?.ADB2C_DOMAIN"
                    :error="localErrors?.['auth.ADB2C_DOMAIN']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">{{ $t('Sales Org') }}</h3>
                <TextInput
                    inputName="auth.ADB2C_SALESORG"
                    :max="254"
                    :value="form?.auth?.ADB2C_SALESORG"
                    :error="localErrors?.['auth.ADB2C_SALESORG']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">{{ $t('EPI Site') }}</h3>
                <TextInput
                    inputName="auth.ADB2C_EPI_SITE"
                    :max="254"
                    :value="form?.auth?.ADB2C_EPI_SITE"
                    :error="localErrors?.['auth.ADB2C_EPI_SITE']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">{{ $t('Scope') }}</h3>
                <TextInput
                    inputName="auth.ADB2C_SCOPE"
                    :max="254"
                    :value="form?.auth?.ADB2C_SCOPE"
                    :error="localErrors?.['auth.ADB2C_SCOPE']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">{{ $t('Response Type') }}</h3>
                <TextInput
                    inputName="auth.ADB2C_RESPONSE_TYPE"
                    :max="254"
                    :value="form?.auth?.ADB2C_RESPONSE_TYPE"
                    :error="localErrors?.['auth.ADB2C_RESPONSE_TYPE']"
                    :readOnly="true"
                />
            </label>
            <label class="admin-label">
                <h3 class="admin-label__text">{{ $t('Response Mode') }}</h3>
                <TextInput
                    inputName="auth.ADB2C_RESPONSE_MODE"
                    :max="254"
                    :value="form?.auth?.ADB2C_RESPONSE_MODE"
                    :error="localErrors?.['auth.ADB2C_RESPONSE_MODE']"
                    :readOnly="true"
                />
            </label>
        </div>
    </layout-default>
</template>
<script>
import { Link } from '@inertiajs/inertia-vue3';
import { useForm } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../../Layout/Admin.vue';
import IntlPhoneInput from '../../../Shared/Inputs/IntlPhoneInput.vue';
import TextInput from '../../../Shared/Inputs/TextInput.vue';
import ContinueButton from '../../../Shared/Inputs/ContinueButton.vue';
import LocationInput from '../../../Shared/Inputs/LocationInput.vue';

export default {
    components: {
        LayoutDefault,
        Link,
        TextInput,
        IntlPhoneInput,
        ContinueButton,
        LocationInput,
    },
    props: {
        data: Array,
        errors: Object,
    },
    setup(props) {
        function getValue(key) {
            return props.data?.[key] || null;
        }

        const form = useForm({
            auth: {
                ADB2C_CLIENTID: getValue('auth.ADB2C_CLIENTID'),
                ADB2C_CLIENTSECRET: getValue('auth.ADB2C_CLIENTSECRET'),
                ADB2C_TENANT: getValue('auth.ADB2C_TENANT'),
                ADB2C_POLICY: getValue('auth.ADB2C_POLICY'),
                ADB2C_REDIRECTURI: getValue('auth.ADB2C_REDIRECTURI'),
                ADB2C_REGISTER_POLICY: getValue('auth.ADB2C_REGISTER_POLICY'),
                ADB2C_REGISTER_REDIRECTURI: getValue(
                    'auth.ADB2C_REGISTER_REDIRECTURI'
                ),
                ADB2C_LOGOUT_POLICY: getValue('auth.ADB2C_LOGOUT_POLICY'),
                ADB2C_DOMAIN: getValue('auth.ADB2C_DOMAIN'),
                ADB2C_SALESORG: getValue('auth.ADB2C_SALESORG'),
                ADB2C_EPI_SITE: getValue('auth.ADB2C_EPI_SITE'),
                ADB2C_SCOPE: getValue('auth.ADB2C_SCOPE'),
                ADB2C_RESPONSE_TYPE: getValue('auth.ADB2C_RESPONSE_TYPE'),
                ADB2C_RESPONSE_MODE: getValue('auth.ADB2C_RESPONSE_MODE'),
            },
            contact: {
                company_name: getValue('contact.company_name'),
                company_group: getValue('contact.company_group'),
                address1: getValue('contact.address1'),
                city: getValue('contact.city'),
                country: getValue('contact.country'),
                postcode: getValue('contact.postcode'),
                vat_number: getValue('contact.vat_number'),
                phone: getValue('contact.phone'),
                footer_text: getValue('contact.footer_text'),
                contact_form_url: getValue('contact.url'),
            },
            email: {
                SENDGRID_API_KEY: getValue('email.SENDGRID_API_KEY'),
                SG_NEW_REQUEST_ID: getValue('email.SG_NEW_REQUEST_ID'),
                SG_EDIT_PROJECT_ID: getValue('email.SG_EDIT_PROJECT_ID'),
                SG_PUBLISH_PROJECT_ID: getValue('email.SG_PUBLISH_PROJECT_ID'),
                SG_SHARE_SOLUTION_ID: getValue('email.SG_SHARE_SOLUTION_ID'),
                SG_EDIT_USER_ID: getValue('email.SG_EDIT_USER_ID'),
                SG_DELETE_USER_ID: getValue('email.SG_DELETE_USER_ID'),
            },
            shop: {
                materials_cta_link: getValue('shop.url'),
            },
        });
        return { form };
    },
    data() {
        return {
            localErrors: {},
            isLoaded: true,
        };
    },
    methods: {
        valueChanged(change) {
            // split by period to put into nested objects
            let inputName = change.input.split('.');
            this.form[inputName[0]][inputName[1]] = change.value;
        },
        saveSettings() {
            this.form
                .transform((data) => {
                    // remove read only values
                    delete data['auth'];
                    delete data['email'];

                    return {
                        ...data,
                    };
                })
                .post('/admin/settings/edit-settings', {
                    preserveScroll: true,
                    onError: () => {
                        if (Object.keys(this.errors).length) {
                            const el = document.querySelector(
                                `[name='${Object.keys(this.errors)[0]}']`
                            );
                            if (el) {
                                el.scrollIntoView({
                                    block: 'start',
                                    behavior: 'smooth',
                                });
                            }
                        }
                    },
                    onBefore: () => {
                        this.isLoaded = false;
                    },
                    onFinish: () => {
                        this.isLoaded = true;
                    },
                });
        },
    },
    watch: {
        errors: {
            deep: true,
            handler: function () {
                // format all error messages to remove nesting
                if (this.errors) {
                    this.localErrors = {};
                    const regex = /\s.+\.(?!$)/gm;
                    for (const [key, value] of Object.entries(this.errors)) {
                        this.localErrors[key] = String(value).replace(
                            regex,
                            ' '
                        );
                    }
                }
            },
        },
    },
};
</script>
