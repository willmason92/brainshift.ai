<template>
    <layout-default>
        <div class="farmers-filters-header">
            <Link href="/" class="popup__back button-reset">
                {{ $t('Home') }}
            </Link>
        </div>
        <h1 class="h1 h1--extra-margin">
            {{ $t('My Account') }}
        </h1>
        <h2>{{ user.name }}</h2>
        <p>{{ user.email }}<br />&nbsp;</p>
        <ul>
            <li v-if="isLoggedIn" class="site-nav__item site-nav__item--closer">
                <Link
                    href="/my-account/edit-general"
                    class="site-nav__link site-nav__link--arrow site-nav__link-arrow-red"
                >
                    {{ $t('My Account') }} {{ $t('Details') }}
                </Link>
            </li>
            <li v-if="isFarmer" class="site-nav__item site-nav__item--closer">
                <button
                    @click="openPopup()"
                    class="button-reset site-nav__link site-nav__link--arrow site-nav__link-arrow-red"
                >
                    {{ $t('My Farm') }} {{ $t('Details') }}
                </button>
            </li>
            <li
                v-if="isInstaller"
                class="site-nav__item site-nav__item--closer"
            >
                <Link
                    href="/my-account/profile"
                    class="site-nav__link site-nav__link--arrow site-nav__link-arrow-red"
                >
                    {{ $t('Your installer profile') }}
                </Link>
            </li>
            <li
                v-if="isInstaller"
                class="site-nav__item site-nav__item--closer"
            >
                <Link
                    href="/my-account/edit-contact"
                    class="site-nav__link site-nav__link--arrow site-nav__link-arrow-red"
                >
                    {{ $t('Contact Preferences') }}
                </Link>
            </li>
        </ul>
        <Popup v-if="isFarmer" :active="popupActive" @active-state="closePopup">
            <form @submit.prevent="submit()">
                <div class="form-row">
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Farm Name') }}
                        </span>
                        <TextInput
                            inputName="farmName"
                            :error="errors.farmName"
                            :value="this.form.farmName"
                            :max="255"
                            @valueChanged="valueChanged"
                        ></TextInput>
                    </label>
                </div>
                <div class="form-row">
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Farm Location') }}
                        </span>
                        <LocationInput
                            inputName="farmLocation"
                            :error="errors.farmLocation"
                            @valueChanged="valueChanged"
                            :useStreet="
                                farmLocation?.street +
                                ', ' +
                                farmLocation?.postcode
                            "
                        />
                    </label>
                </div>
                <div class="form-row">
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Farm Type') }}
                        </span>
                        <SelectSector
                            :error="errors.farmSector"
                            :title="$t('Choose the sector you\'re in')"
                            :text="$t('Choose Your Sector')"
                            inputName="farmSector"
                            :multiple="false"
                            :selectedSectors="farmSector ? farmSector.id : null"
                            @valueChanged="valueChanged"
                        >
                        </SelectSector>
                    </label>
                </div>
                <div class="form-row">
                    <button
                        type="submit"
                        class="button button--fill button--span"
                    >
                        {{ $t('Continue') }}
                    </button>
                </div>
            </form>
        </Popup>
    </layout-default>
</template>

<script>
import { Link, useForm } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../Layout/Base.vue';
import Popup from '../../Shared/Popup.vue';
import TextInput from '../../Shared/Inputs/TextInput.vue';
import SelectSector from '../../Shared/Inputs/SelectSector.vue';
import Toast from '../../Shared/Toast.vue';
import LocationInput from '../../Shared/Inputs/LocationInput.vue';

export default {
    components: {
        Link,
        LayoutDefault,
        Popup,
        TextInput,
        SelectSector,
        Toast,
        LocationInput,
    },
    props: {
        user: Object,
        errors: Object,
        sectors: Array,
        farmName: String,
        farmLocation: String,
        farmSector: Object,
        flash: Object,
    },
    watch: {
        '$page.props.flash.message': {
            handler() {
                if (
                    this.flash.message !== null &&
                    this.flash.message.type === 'success' &&
                    this.toast === false
                ) {
                    this.toastMessage = this.flash.message.msg;
                    this.toast = true;
                }
            },
        },
    },
    data() {
        return {
            popupActive: false,
            toast: false,
            toastMessage: '',
        };
    },
    setup(props) {
        const form = useForm({
            farmName: props.farmName,
            farmLocation: props.farmLocation,
            farmSector: props.farmSector ? props.farmSector.id : null,
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
        openPopup() {
            this.popupActive = true;
        },
        closePopup() {
            this.popupActive = false;
        },
        submit() {
            this.form.post('/my-account/profile/save', {
                preserveScroll: true,
                preserveState: true,
                replace: true,
                onSuccess: () => (this.popupActive = false),
                onError: (errors) => (this.popupActive = true),
            });
        },
        valueChanged(change) {
            if (change.input == 'farmLocation') {
                let location = JSON.parse(change.value);
                this.form.farmLocation = location.street;
            } else {
                this.form = {
                    ...this.form,
                    [change.input]: change.value,
                };
            }
        },
    },
};
</script>
