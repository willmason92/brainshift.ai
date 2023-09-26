<template>
    <h2 class="h4">
        {{ title }}
    </h2>
    <h1 class="h2 h1--extra-margin">
        {{ $t('Your Eternit case study') }}
    </h1>
    <form @submit.prevent="submitForm()">
        <div class="form-row">
            <div class="form-label">
                <span class="form-label__text">
                    {{ $t('Upload pictures') }}
                </span>
                <FileUpload
                    to="/my-account/profile/upload-gallery"
                    :limit="10"
                    inputName="gallery"
                    accept="image/*"
                    :files="project.gallery"
                    :error="galleryErrors"
                    @returnValue="valueChanged"
                />
            </div>
        </div>
        <div class="form-row">
            <label class="form-label">
                <span class="form-label__text">
                    {{ $t('Title') }}
                </span>
                <TextInput
                    inputName="title"
                    :value="project.post_title"
                    :error="errors.title"
                    @valueChanged="valueChanged"
                ></TextInput>
            </label>
        </div>
        <div class="form-row">
            <label class="form-label">
                <span class="form-label__text">
                    {{ $t('Description (max 1000 signs)') }}
                </span>
                <TextAreaInput
                    inputName="description"
                    :value="project.post_content"
                    :error="errors.description"
                    :max="1000"
                    @valueChanged="valueChanged"
                ></TextAreaInput>
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
                    inputName="sector"
                    :multiple="false"
                    :selectedSectors="project.sector ? project.sector.id : null"
                    @valueChanged="valueChanged"
                >
                </SelectSector>
            </label>
        </div>
        <div class="form-row">
            <label class="form-label">
                <span class="form-label__text">
                    {{ $t('Project Type') }}
                </span>
                <SelectPopup
                    :error="errors?.projectType"
                    :text="$t('Select project type')"
                    :options="projectTypes"
                    inputName="projectType"
                    :selectedValues="project.type ? project.type : null"
                    :multiple="false"
                    @valueChanged="valueChanged"
                >
                    <h2>
                        {{ $t('What is the project type') }}
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
                    {{ $t('Size of the building') }}
                </span>
                <SizeInput
                    inputName="size"
                    :error="{
                        length: errors.sizeLength,
                        width: errors.sizeWidth,
                        unit: errors.sizeUnit,
                    }"
                    :defaultLength="project.length"
                    :defaultWidth="project.width"
                    :defaultUnit="project.size_unit"
                    @valueChanged="valueChanged"
                ></SizeInput>
            </label>
        </div>
        <div class="form-row">
            <label class="form-label">
                <span class="form-label__text">
                    {{ $t('When was the building finished?') }}
                </span>
                <DatePicker
                    inputName="dateComplete"
                    :value="project.completed_on"
                    :error="errors.dateComplete"
                    @valueChanged="valueChanged"
                ></DatePicker>
            </label>
        </div>
        <div class="form-row">
            <Switch
                inputName="includeLocation"
                :label="$t('Include location')"
                :defaultValue="project.location ? 1 : 0"
                :error="errors.includeLocation"
                @valueChanged="valueChanged"
            ></Switch>
        </div>
        <div v-if="showLocation" class="form-row">
            <div class="form-label">
                <LocationInput
                    inputName="location"
                    :defaultLocation="
                        project.location ? project.location.asJson : ''
                    "
                    :error="errors.location"
                    @valueChanged="valueChanged"
                ></LocationInput>
            </div>
        </div>
        <div class="form-row">
            <label class="form-label">
                <span class="form-label__text">
                    {{ $t('What Eternit product was used?') }}
                </span>
                <MaterialsPicker
                    inputName="materials"
                    :error="errors.materials"
                    :multiple="true"
                    :selectedOption="project.materials"
                    @valueChanged="valueChanged"
                >
                    <h2>
                        {{ $t('Which Eternit products were used?') }}
                    </h2>
                    <p>
                        {{ $t('Choose multiple if applicable') }}
                    </p>
                </MaterialsPicker>
            </label>
        </div>
        <div v-if="newProject === false">
            <button
                type="button"
                class="button button--clear button--span button-reset"
                @click.prevent="deleteProjectPopup()"
            >
                {{ $t('Delete project') }}
            </button>
            <Popup :active="deletePopup" @activeState="closePopup">
                <h2 class="farmers-filters__title h2">
                    {{ $t('Are you sure you want to delete this project') }}:
                </h2>
                <a href="#" class="link" @click="deleteProject()">
                    {{ $t('Delete project') }}
                </a>
            </Popup>
        </div>
        <div>
            <button type="submit" class="button button--fill button--span">
                {{ project ? $t('Save changes') : $t('Publish') }}
            </button>
        </div>
    </form>
</template>

<script>
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import FileUpload from '../../Shared/Inputs/FileUpload.vue';
import TextInput from '../../Shared/Inputs/TextInput.vue';
import TextAreaInput from '../../Shared/Inputs/TextAreaInput.vue';
import SelectSector from '../../Shared/Inputs/SelectSector.vue';
import SizeInput from '../../Shared/Inputs/SizeInput.vue';
import DatePicker from '../../Shared/Inputs/DatePicker.vue';
import Switch from '../../Shared/Inputs/Switch.vue';
import LocationInput from '../../Shared/Inputs/LocationInput.vue';
import MaterialsPicker from '../../Shared/Inputs/MaterialsPicker.vue';
import SelectPopup from '../../Shared/Inputs/SelectPopup.vue';
import Popup from '../../Shared/Popup.vue';

export default {
    components: {
        FileUpload,
        TextInput,
        TextAreaInput,
        SelectSector,
        SizeInput,
        DatePicker,
        Switch,
        LocationInput,
        MaterialsPicker,
        SelectPopup,
        Popup,
    },
    emits: ['closePopup'],
    props: {
        title: String,
        project: Object,
        errors: Object,
        newProject: Boolean,
    },
    data() {
        const projectTypes = [
            {
                id: 0,
                name: 'New Build',
            },
            {
                id: 1,
                name: 'Refurbishment',
            },
        ];
        return {
            projectTypes: projectTypes,
            showLocation: this.project.location ? true : false,
            deletePopup: false,
        };
    },
    computed: {
        galleryErrors() {
            let r = [];
            if (this.errors) {
                r = Object.values(
                    Object.keys(this.errors)
                        .filter((key) => key.includes('gallery'))
                        .reduce((cur, key) => {
                            return Object.assign(cur, {
                                [key]: this.errors[key],
                            });
                        }, {})
                );
            }
            return r.length > 0 ? r : null;
        },
    },
    setup(props) {
        let files = [];
        if (props.project.gallery) {
            props.project.gallery.forEach((image) => {
                files.push(image.path + '/' + image.filename);
            });
        }
        const form = useForm({
            id: props.project.id,
            gallery: files,
            title: props.project.post_title,
            description: props.project.post_content,
            sector: props.project.sector?.id,
            projectType: props.project.type,
            sizeLength: props.project.length,
            sizeWidth: props.project.width,
            sizeUnit: props.project.size_units,
            dateComplete: props.project.completed_on,
            includeLocation: props.project.location ? true : false,
            location: props.project.location?.asJson,
            materials: props.project.materials,
        });
        return { form };
    },
    methods: {
        valueChanged(change) {
            if (change.input === 'size') {
                this.form.sizeLength = change.value.length;
                this.form.sizeWidth = change.value.width;
                this.form.sizeUnit = change.value.unit;
            } else {
                this.form = {
                    ...this.form,
                    [change.input]: change.value,
                };
            }
            console.log(this.form);

            this.checkLocation();
        },
        submitForm() {
            console.log(this.form);
            this.form
                .transform((data) => ({ ...data }))
                .post('/my-account/profile/save-project', {
                    preserveScroll: true,
                    preserveState: true,
                    replace: true,
                    onSuccess: () => this.$emit('closePopup'),
                    onError: (errors) => (this.popupActive = true),
                });
        },
        deleteProjectPopup() {
            this.deletePopup = true;
        },
        closePopup() {
            console.log('here');
            this.deletePopup = false;
        },
        deleteProject() {
            this.form
                .transform((data) => ({
                    ...data,
                    _deleteKey: this.project.delete_key,
                }))
                .post('/my-account/profile/save-project', {
                    preserveScroll: true,
                    preserveState: true,
                    replace: true,
                    onSuccess: () => {
                        this.$emit('closePopup');
                        console.log('success');
                    },
                    onError: (errors) => {
                        this.popupActive = true;
                        this.closePopup();
                        console.log('fail');
                    },
                });
        },
        checkLocation() {
            this.showLocation = this.form.includeLocation;
        },
    },
};
</script>
