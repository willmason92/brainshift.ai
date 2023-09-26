<template>
    <Popup :active="active" id="installer-profile-form">
        <form @submit.prevent="submit()" v-if="currentPage === 1 || currentPage === 2">
            <div v-show="currentPage === 1">
                <h1 class="h4 f-sans">
                    {{ $t('Set up your profile') }}
                </h1>
                <h2 class="m-bot--2">
                    {{ $t('Where do you work?') }}
                </h2>
                <div class="form-row">
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Company Logo') }}
                        </span>
                        <FileUpload
                            to="/my-account/profile/upload-logo"
                            :limit="1"
                            inputName="company_logo"
                            accept="image/*"
                            :files="user?.expert?.expert_logo ? [user.expert.expert_logo] : null"
                            :error="errors?.company_logo ? [errors.company_logo] : null"
                            @returnValue="valueChanged"
                        />
                    </label>
                </div>
                <div class="form-row">
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Company Name') }}
                        </span>
                        <TextInput
                            inputName="company_name"
                            :value="user.expert ? user.expert.company_name : ''"
                            :error="errors?.company_name"
                            :max="255"
                            @valueChanged="valueChanged"
                        ></TextInput>
                    </label>
                </div>
                <div class="form-row">
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Location') }}
                        </span>
                        <LocationInput
                            inputName="location"
                            :error="errors?.location"
                            :defaultLocation="user.location ? user.location.asJson : ''"
                            @valueChanged="valueChanged"
                        ></LocationInput>
                    </label>
                </div>
                <div class="form-row">
                    <Switch
                        inputName="nationwide"
                        :label="$t('I work nationwide')"
                        :defaultValue="user.expert ? user.expert.nationwide : 1"
                        :error="errors?.nationwide"
                        @valueChanged="valueChanged"
                    ></Switch>
                </div>
                <div class="form-row" v-if="showRadius">
                    <RadiusInput
                        inputName="radius"
                        :defaultAmount="user.location ? user.location.sizeradius : 0"
                        :defaultUnit="user.location ? user.location.sizeradiustype : 0"
                        placeholder="0.00"
                        :error="errors?.radius"
                        @valueChanged="valueChanged"
                    ></RadiusInput>
                </div>
                <button type="button" @click="goToPage(2)" class="button button--fill button--span">
                    {{ $t('Continue') }}
                </button>
            </div>
            <div v-show="currentPage === 2">
                <h1 class="h4 f-sans">
                    {{ $t('Set up your profile') }}
                </h1>
                <h2>
                    {{ $t('Expertise') }}
                </h2>
                <p>
                    {{ $t('Define your company expertise') }}
                </p>
                <div class="form-row">
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Sector') }}
                        </span>
                        <SelectSector
                            :error="errors?.sector"
                            :title="$t('Choose the sector you\'re in')"
                            :text="$t('Choose Your Sector')"
                            inputName="sector"
                            :selectedSectors="user.expert ? user.expert.sectors : []"
                            :multiple="true"
                            @valueChanged="valueChanged"
                        >
                        </SelectSector>
                    </label>
                </div>
                <div class="form-row">
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Speciality') }}
                        </span>
                        <SelectPopup
                            :error="errors?.specialities"
                            :text="$t('Choose your speciality')"
                            :options="specialities"
                            :inputName="'specialities'"
                            :selectedValues="selectedSpecialities"
                            :multiple="true"
                            @valueChanged="valueChanged"
                        >
                            <h2>
                                {{ $t('What is your speciality') }}
                            </h2>
                            <p>
                                {{ $t('Choose multiple if applicable') }}
                            </p>
                        </SelectPopup>
                    </label>
                </div>
                <div class="form-row">
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Description') }}
                        </span>
                        <TextAreaInput
                            inputName="description"
                            :placeholder="$t('Tell us a bit about your business, what makes you stand out from the crowd?')"
                            :value="user.expert ? user.expert.description : ''"
                            :error="errors?.description"
                            :max="1000"
                            @valueChanged="valueChanged"
                        ></TextAreaInput>
                    </label>
                </div>
                <button type="button" @click="submit()" class="button button--fill button--span">
                    {{ $t('Continue') }}
                </button>
            </div>
        </form>
        <div v-if="currentPage === 3">
            <div class="m-bot--large">
                <div class="m-bot--large">
                    <h1 class="h4 f-sans">
                        {{ $t('Set up your profile') }}
                    </h1>
                </div>
                <img class="m-bot--2" src="../../images/success-thumb.svg">
                <h2>
                    {{ $t('Your profile has been successfully set up') }}
                </h2>
            </div>
            <div class="large-text">
                <p class="m-bot">
                    {{ $t('From now on you will be visible in the "Find an Expert" section and available to receive contact requests') }}
                </p>
                <p>
                    {{ $t('You can always update and change your profile information in your account settings.') }}
                </p>
            </div>
            <div>
                <button type="button" @click="closePopup()" class="button button--clear button--span">
                    {{ $t('Skip') }}
                </button>
                <button type="button" @click="newProject()" class="button button--fill button--span">
                    {{ $t('Add a case study') }}
                </button>
            </div>
        </div>
        <template #backButton>
            <Link
                v-if="firstTime && currentPage == 1"
                href="/"
                class="popup__back button-reset"
            >
                {{ $t('Back') }}
            </Link>
            <button v-else type="button" class="popup__back button-reset" @click="previousStage">
                {{ $t('Back') }}
            </button>
        </template>
        <template #closeButton>
            <Link
                v-if="firstTime"
                href="/"
                class="popup__close button-reset"
            >
                {{ $t('Close') }}
            </Link>
            <button
                v-else
                type="button"
                class="popup__close button-reset"
                @click="closePopup()"
            >
                {{ $t('Close') }}
            </button>
        </template>
    </Popup>
</template>

<script>
import {useForm, usePage, Link} from '@inertiajs/inertia-vue3'
import Popup from './Popup.vue'
import FileUpload from './Inputs/FileUpload.vue'
import TextInput from './Inputs/TextInput.vue'
import LocationInput from './Inputs/LocationInput.vue'
import Switch from './Inputs/Switch.vue'
import RadiusInput from './Inputs/RadiusInput.vue'
import SelectSector from './Inputs/SelectSector.vue'
import SelectPopup from './Inputs/SelectPopup.vue'
import TextAreaInput from './Inputs/TextAreaInput.vue'

export default {
    components: {
        Link,
        Popup,
        FileUpload,
        TextInput,
        LocationInput,
        Switch,
        RadiusInput,
        SelectSector,
        SelectPopup,
        TextAreaInput,
    },
    props: {
        active: Boolean,
        user: Object,
        sectors: Array,
        specialities: Array,
        logo: Array,
        firstTime: Boolean,
    },
    data() {
        let specIds = [];
        if ( this.user.expert ){
            this.user.expert.specialities.forEach(function (item) {
                specIds.push(item.id)
            })
        }
        return {
            currentPage: 1,
            errors: usePage().props.value.errors,
            showRadius: this.user.expert ? !this.user.expert.nationwide : false,
            selectedSpecialities: specIds,
            firstTime: !usePage().props.value.installer.profile_complete
        }
    },
    computed: {
        getLogo() {
            let r = null;
            if ( this.user.expert ){
              r = this.user.expert.expert_logo.path + '/' + this.user.expert.expert_logo.filename;
            }
            return r;
        },
    },
    setup(props) {
        let initialLogo = null;
        if ( props.user.expert ){
          initialLogo = props.user.expert.expert_logo.path + '/' + props.user.expert.expert_logo.filename;
        }
        const form = useForm({
            company_logo: initialLogo,
            company_name: props.user.expert ? props.user.expert.company_name : '',
            location: props.user.expert ? props.user.location  : null,
            nationwide: props.user.expert ? props.user.expert.nationwide : 1,
            radius: props.user.location ? props.user.location.sizeradius : false,
            radiusType: props.user.location ? props.user.location.sizeradiustype : false,
            sector: props.user.expert ? props.user.expert.sectors.map(({id}) => id) : null,
            specialities: props.user.expert ? props.user.expert.specialities.map(({id}) => id) : null,
            description: props.user.expert ? props.user.expert.description : '',
        })
        return { form }
    },
    methods: {
        valueChanged(change) {
            if( change.input === "radius" ){
                this.form = {
                    ...this.form,
                    ['radiusType']: change.value.unit,
                    ['radius']: change.value.amount,
                }
            }else{
                this.form = {
                    ...this.form,
                    [change.input]: change.value,
                }
            }
            this.checkNationwide()
        },
        submit() {
            this.form.transform((data) => ({
                ...data,
                firstTime: this.firstTime,
            })).post('/my-account/profile/save', {
                preserveScroll: true,
                preserveState: true,
                replace: true,
                onSuccess: () => {
                    this.form.company_logo = this.user.expert.expert_logo.path + '/' + this.user.expert.expert_logo.filename
                    if ( this.firstTime ){
                        this.firstTime = false;
                        this.goToPage(3);
                    } else {
                        this.closePopup();
                    }
                },
                onError: (errors) => {
                    this.popupActive = true
                    if (
                        errors.company_logo ||
                        errors.company_name ||
                        errors.location ||
                        errors.nationwide ||
                        errors.radius
                    ) {
                        this.currentPage = 1
                        this.errors = errors
                    }
                }
            });



        },
        checkNationwide() {
            this.showRadius = !this.form.nationwide
        },
        goToPage(page) {
            this.currentPage = page
            this.$emit('formPage', this.currentPage)

            document.querySelector('#installer-profile-form .popup__inner').scrollTop = 0
        },
        previousStage() {
            if (this.currentPage > 1) {
                this.currentPage = this.currentPage - 1
                document.querySelector('#installer-profile-form .popup__inner').scrollTop = 0
            } else {
                this.closePopup()
            }
        },
        closePopup() {
            this.$emit('closePopup')
            this.goToPage(1);
        },
        newProject() {
            this.$emit('closePopup')
            this.$emit('newProject')
        }
    }
}
</script>
