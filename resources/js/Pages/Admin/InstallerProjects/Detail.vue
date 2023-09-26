<template>
    <layout-default>
        <div class="admin-back">
            <Link
                href="/admin/installer-projects"
                class="popup__back button-reset"
            >
                {{ $t('Back to overview') }}
            </Link>
            <ContinueButton
                v-if="isEditing"
                :text="$t('Save changes')"
                :isEnabled="isEditing && isLoaded"
                :isLoading="!isLoaded"
                wrapperClass="button--thin"
                @continue="editProject"
            />
            <ContinueButton
                v-else
                :text="
                    isPublished == 0 ? $t('Approve & publish') : $t('Unpublish')
                "
                :isEnabled="isLoaded"
                :isLoading="!isLoaded"
                wrapperClass="button--thin"
                @continue="changePublishStatus"
            />
        </div>

        <h1 class="admin-title">
            {{ $t('Installer project') }}: {{ project.post_title }}
        </h1>

        <p class="error-message" v-if="errors?.id">
            {{ errors?.id }}
        </p>

        <div class="card admin-details-wrapper">
            <h2 class="admin-subtitle">{{ $t('Installer details') }}</h2>
            <div class="admin-project__project-details">
                <div class="col">
                    <div class="col admin-label">
                        <h3 class="admin-label__text">{{ $t('Author') }}</h3>
                        {{ project.author.name }}
                    </div>
                    <div class="col m-top admin-label">
                        <h3 class="admin-label__text">
                            {{ $t('Phone number') }}
                        </h3>
                        {{ project.author.phone || '---' }}
                    </div>
                </div>

                <div class="col">
                    <div class="col admin-label">
                        <h3 class="admin-label__text">
                            {{ $t('Email address') }}
                        </h3>
                        {{ project.author.email || '---' }}
                    </div>
                    <div class="col m-top admin-label">
                        <h3 class="admin-label__text">
                            {{ $t('Allows contact') }}
                        </h3>
                        {{ project.author.contact_by_email ? 'Yes' : 'No' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card admin-details-wrapper" @click="initEdit">
            <h2 class="admin-subtitle">{{ $t('Project details') }}</h2>
            <div class="admin-project__project-details">
                <div class="col">
                    <label class="admin-label">
                        <h3 class="admin-label__text">
                            {{ $t('Project title') }}
                        </h3>
                        <template v-if="isEditing">
                            <TextInput
                                inputName="title"
                                :value="project.post_title"
                                :error="errors.title"
                                :placeholder="$t('Enter a project name')"
                                :max="254"
                                @valueChanged="valueChanged"
                            />
                        </template>
                        <template v-else>
                            {{ project.post_title || 'No title provided' }}
                        </template>
                    </label>

                    <div class="admin-label">
                        <h3 class="admin-label__text">{{ $t('Type') }}</h3>
                        <template v-if="isEditing">
                            <DropdownInput
                                inputName="projectType"
                                :options="[
                                    { id: 0, text: 'New Build' },
                                    { id: 1, text: 'Refurbishment' },
                                ]"
                                :defaultOption="project.type"
                                :error="errors.projectType"
                                @valueChanged="valueChanged"
                            />
                        </template>
                        <template v-else>
                            {{
                                project.type == 0
                                    ? 'New build'
                                    : 'Refurbishment'
                            }}
                        </template>
                    </div>

                    <div class="admin-label">
                        <h3 class="admin-label__text">{{ $t('Sector') }}</h3>
                        <template v-if="isEditing">
                            <DropdownInput
                                inputName="sector"
                                :options="
                                    sectors.map((sector) => {
                                        return {
                                            id: sector.id,
                                            text: sector.name,
                                        };
                                    })
                                "
                                :defaultOption="project.sector.id"
                                :error="errors.sector"
                                @valueChanged="valueChanged"
                            />
                        </template>
                        <template v-else>
                            {{ project.sector.name }}
                        </template>
                    </div>

                    <label class="admin-label">
                        <h3 class="admin-label__text">
                            {{ $t('Project completed on') }}
                        </h3>

                        <template v-if="isEditing">
                            <DatePicker
                                inputName="dateComplete"
                                :error="errors.dateComplete"
                                :defaultValue="formattedCompletedDate"
                                @valueChanged="valueChanged"
                        /></template>
                        <template v-else>
                            {{ formattedCompletedDate }}
                        </template>
                    </label>
                </div>
                <div class="col">
                    <label class="admin-label">
                        <h3 class="admin-label__text">
                            {{ $t('Installed products') }}
                        </h3>
                        <div class="project-details__products">
                            <template v-if="isEditing">
                                <!-- needs a prop with all materials -->
                                <MaterialsPicker
                                    inputName="materials"
                                    title="Materials"
                                    :multiple="true"
                                    :options="materials"
                                    :selectedOption="project.materials"
                                    :isAdmin="true"
                                    :error="errors.materials"
                                    @valueChanged="valueChanged"
                            /></template>
                            <template v-else>
                                <div class="material-picker">
                                    <ul class="material-picker__selection">
                                        <li
                                            class="material-selection"
                                            v-for="material in project.materials"
                                        >
                                            <img
                                                class="material-selection__image"
                                                :src="material.file.url"
                                                :alt="material.name"
                                                width="60"
                                                height="60"
                                            />
                                            <h4
                                                class="material-selection__name"
                                            >
                                                {{ material.name }}
                                            </h4>
                                        </li>
                                    </ul>
                                </div>
                            </template>
                        </div>
                    </label>
                </div>
            </div>
            <div class="admin-project__project-details">
                <div class="col">
                    <label class="admin-label">
                        <h3 class="admin-label__text">
                            {{ $t('Width') }}
                        </h3>
                        <template v-if="isEditing">
                            <NumberInput
                                placeholder="0.00"
                                inputName="sizeWidth"
                                :min="0"
                                :defaultValue="project.width"
                                :error="errors.sizeWidth"
                                @valueChanged="valueChanged"
                            ></NumberInput>
                        </template>
                        <template v-else>
                            {{ project.width }}
                        </template>
                    </label>
                </div>
                <div class="col">
                    <label class="admin-label">
                        <h3 class="admin-label__text">
                            {{ $t('Length') }}
                        </h3>
                        <template v-if="isEditing">
                            <NumberInput
                                placeholder="0.00"
                                inputName="sizeLength"
                                :min="0"
                                :defaultValue="project.length"
                                :error="errors.sizeLength"
                                @valueChanged="valueChanged"
                            ></NumberInput>
                        </template>
                        <template v-else>
                            {{ project.length }}
                        </template>
                    </label>
                </div>
                <div class="col">
                    <label class="admin-label">
                        <h3 class="admin-label__text">
                            {{ $t('Unit') }}
                        </h3>
                        <template v-if="isEditing">
                            <ToggleBox
                                inputName="sizeUnit"
                                :options="[
                                    {
                                        id: 0,
                                        name: 'meter',
                                    },
                                    {
                                        id: 1,
                                        name: 'feet',
                                    },
                                ]"
                                :defaultValue="project.size_unit"
                                @valueChanged="valueChanged"
                                :error="errors.sizeUnit"
                                wrapperClass="toggle-box--start"
                            ></ToggleBox>
                        </template>
                        <template v-else>
                            {{ project.size_unit == 0 ? 'Meters' : 'Feet' }}
                        </template>
                    </label>
                </div>
            </div>

            <template v-if="project?.location || isEditing">
                <h2 class="admin-subtitle m-top--3">
                    {{ $t('Project location') }}
                </h2>
                <div class="admin-project__project-location">
                    <div class="admin-project__project-details">
                        <div class="col">
                            <label class="admin-label">
                                <h3 class="admin-label__text">
                                    {{ $t('Farm location') }}
                                </h3>
                                <template v-if="isEditing">
                                    <LocationInput
                                        inputName="location"
                                        :title="$t('Pick a location')"
                                        :useStreet="
                                            project?.location
                                                ? `${project?.location?.street}, ${project?.location?.postcode}`
                                                : null
                                        "
                                        :isAdmin="true"
                                        :error="errors.location"
                                        @valueChanged="valueChanged"
                                    />
                                </template>
                                <template v-else-if="project?.location">
                                    {{ project?.location?.street }},
                                    {{ project?.location?.postcode }}
                                </template>
                            </label>
                        </div>
                        <div class="col" v-if="!isEditing">
                            <label class="admin-label">
                                <h3 class="admin-label__text">
                                    {{ $t('Country') }}
                                </h3>
                                {{ project?.location?.country || 'Unknown' }}
                            </label>
                        </div>
                    </div>
                </div>
            </template>

            <h2 class="admin-subtitle m-top--3">{{ $t('Project images') }}</h2>
            <div class="admin-project__project-images">
                <div class="admin-project__project-details">
                    <div class="col">
                        <label class="admin-label">
                            <h3 class="admin-label__text">
                                {{ $t('Gallery') }}
                            </h3>
                            <template v-if="isEditing">
                                <FileUpload
                                    to="/my-account/profile/upload-gallery"
                                    :limit="10"
                                    inputName="gallery"
                                    :small="true"
                                    accept="image/*"
                                    :files="project.gallery"
                                    :error="galleryErrors"
                                    @returnValue="valueChanged"
                                />
                            </template>
                            <template v-else>
                                <div class="file-upload-wrapper input-element">
                                    <div class="file-upload__images">
                                        <div
                                            v-for="image in project.gallery"
                                            class="file-upload__image"
                                        >
                                            <img
                                                class="file-upload__image-img"
                                                :src="image.url"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <button
            class="button button--fill button--slim"
            :class="{ disabled: !isLoaded }"
            type="button"
            @click="deleteProject"
        >
            {{ $t('Delete project') }}
        </button>

        <button
            v-if="!isEditing"
            class="button button--fill button--slim"
            :class="{ disabled: !isLoaded }"
            type="button"
            @click="initEdit"
        >
            {{ $t('Edit project') }}
        </button>

        <AlertBox
            v-if="deletePopup"
            :active="deletePopup"
            @active-state="closePopup"
        >
            <img
                class="login-alert__icon"
                src="../../../../images/alert-outline.svg"
                alt=""
                width="60"
                height="60"
            />
            <h3 class="alert-box__title">
                {{ $t('Are you sure you want to delete this project?') }}
            </h3>
            <button
                type="button"
                class="button button--fill button--span button--thin button-reset alert-box__cta"
                @click="deleteProject()"
            >
                {{ $t('Delete project') }}
            </button>
        </AlertBox>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { useForm } from '@inertiajs/inertia-vue3';
import moment from 'moment';
import LayoutDefault from '../../../Layout/Admin.vue';
import TextInput from '../../../Shared/Inputs/TextInput.vue';
import EmailInput from '../../../Shared/Inputs/EmailInput.vue';
import IntlPhoneInput from '../../../Shared/Inputs/IntlPhoneInput.vue';
import MaterialsPicker from '../../../Shared/Inputs/MaterialsPicker.vue';
import TagInput from '../../../Shared/Inputs/TagInput.vue';
import DatePicker from '../../../Shared/Inputs/DatePicker.vue';
import FileUpload from '../../../Shared/Inputs/FileUpload.vue';
import LocationInput from '../../../Shared/Inputs/LocationInput.vue';
import ContinueButton from '../../../Shared/Inputs/ContinueButton.vue';
import NumberInput from '../../../Shared/Inputs/NumberInput.vue';
import ToggleBox from '../../../Shared/Inputs/ToggleBox.vue';
import AlertBox from '../../../Shared/AlertBox.vue';
import DropdownInput from '../../../Shared/Inputs/DropdownInput.vue';

export default {
    components: {
        LayoutDefault,
        Link,
        TextInput,
        EmailInput,
        IntlPhoneInput,
        MaterialsPicker,
        TagInput,
        DatePicker,
        FileUpload,
        LocationInput,
        ContinueButton,
        NumberInput,
        ToggleBox,
        AlertBox,
        DropdownInput,
    },
    props: {
        project: Object,
        edit: Boolean,
        errors: Object,
        sectors: Object,
        errors: [Array, Object],
    },
    setup(props) {
        let files = [];
        if (props.project.gallery) {
            props.project.gallery.forEach((image) => {
                files.push(image.path + '/' + image.filename);
            });
        }
        const form = useForm({
            title: props.project.post_title,
            projectType: props.project.type,
            sector: props.project.sector.id,
            dateComplete: props.project.completed_on,
            materials: props.project.materials,
            sizeWidth: props.project.width,
            sizeLength: props.project.length,
            sizeUnit: props.project.size_unit,
            location: props?.project?.location.asJson,
            gallery: files,
        });
        return { form };
    },
    mounted() {
        this.isPublished = this.project.project_published_status;

        if (this.edit) {
            this.isEditing = this.edit;
        }

        const queryString = window.location.search;
        if (queryString) {
            const urlParams = new URLSearchParams(queryString);
            this.isEditing = Boolean(urlParams.get('edit'));
        }
    },
    data() {
        return {
            isEditing: false,
            isPublished: false,
            deletePopup: false,
            isLoaded: true,
        };
    },
    methods: {
        valueChanged(change) {
            this.form = {
                ...this.form,
                [change.input]: change.value,
            };
        },
        changePublishStatus() {
            this.$inertia.post(
                `/admin/installer-projects/${this.project.id}/update-status`,
                this.project.id,
                {
                    onBefore: () => {
                        this.isLoaded = false;
                    },
                    onSuccess: () => {
                        this.isLoaded = true;
                        this.isPublished = !this.isPublished;
                    },
                    onFinish: () => {
                        this.isLoaded = true;
                    },
                }
            );
        },
        deleteProject() {
            if (!this.deletePopup) {
                this.openDeletePopup();
            } else {
                this.$inertia.post(
                    `/admin/installer-projects/${this.project.id}/delete-project`,
                    this.project.id,
                    {
                        onBefore: () => {
                            this.isLoaded = false;
                        },
                        onFinish: () => {
                            this.isLoaded = true;
                        },
                    }
                );
                this.closePopup();
            }
        },
        editProject() {
            // submit updated form here
            this.$inertia.post(
                `/admin/installer-projects/${this.project.id}/edit-project`,
                this.form,
                {
                    onBefore: () => {
                        this.isLoaded = false;
                    },
                    onFinish: () => {
                        this.isLoaded = true;
                    },
                }
            );
        },
        initEdit() {
            this.isEditing = true;
        },
        closePopup() {
            this.deletePopup = false;
        },
        openDeletePopup() {
            this.deletePopup = true;
        },
    },
    computed: {
        formattedCompletedDate() {
            return moment(this.project.completed_on).format('DD/MM/YYYY');
        },
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
};
</script>
