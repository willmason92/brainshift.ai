<template>
    <layout-default>
        <div class="admin-back">
            <Link
                href="/admin/manage-blog/"
                class="popup__back button-reset admin-back-button"
            >
                {{ $t('Back to overview') }}
            </Link>

            <ContinueButton
                :text="$t('Publish now')"
                :isEnabled="isLoaded"
                :isLoading="!isLoaded"
                wrapperClass="button--thin button--no-margin admin-title__cta"
                @continue="submitForm"
            />
        </div>

        <div class="admin-title-wrapper">
            <h1 class="admin-title">
                {{ post?.post_title || $t('New blog post') }}
            </h1>
            <StatusDropdown
                inputName="article_type"
                :defaultOption="dropdownPostType"
                :options="[
                    { id: 3, value: 'Case Studies' },
                    { id: 2, value: 'Articles' },
                    { id: 5, value: 'Training Videos' },
                    { id: 4, value: 'Technical Documents' },
                    { id: 6, value: 'Social Media Accounts' },
                    { id: 7, value: 'Animal Welfare' },
                ]"
                :isEnabled="newPostType"
                :skipEnabledCheck="false"
                @valueChanged="(change) => openWarningPopup('type', change)"
            />
        </div>
        <form class="form-page" @submit.prevent="">
            <div class="card card--large flex-col">
                <div class="admin-subtitle">
                    <h2 class="h4 h1--extra-margin">{{ $t('General') }}</h2>
                </div>

                <div
                    class="form-row form-row--max"
                    v-if="propertyMap?.[_postType].includes('title')"
                    :style="`order: ${propertyMap?.[_postType].indexOf(
                        'title'
                    )};`"
                >
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Article title') }}
                        </span>
                        <TextInput
                            inputName="title"
                            :value="form?.title"
                            placeholder="Add a title"
                            :error="errors.title"
                            @valueChanged="valueChanged"
                        ></TextInput>
                    </label>
                </div>
                <div
                    class="form-row form-row--max"
                    v-if="
                        propertyMap?.[_postType].includes('social_media_link')
                    "
                    :style="`order: ${propertyMap?.[_postType].indexOf(
                        'social_media_link'
                    )};`"
                >
                    <div class="form-label">
                        <span class="form-label__text">
                            {{ $t('Social media link') }}
                        </span>
                        <TextInput
                            inputName="social_media_link"
                            :value="form?.social_media_link"
                            placeholder="Add a link to a social media account..."
                            format="url"
                            :error="errors.social_media_link"
                            @valueChanged="valueChanged"
                        ></TextInput>
                    </div>
                </div>
                <div
                    class="form-row form-row--max"
                    v-if="propertyMap?.[_postType].includes('location')"
                    :style="`order: ${propertyMap?.[_postType].indexOf(
                        'location'
                    )};`"
                >
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Location') }}
                        </span>
                        <LocationInput
                            inputName="location"
                            title="Pick a location"
                            :isAdmin="true"
                            :useStreet="
                                form?.location
                                    ? form.location?.street +
                                      ', ' +
                                      form.location.postcode
                                    : null
                            "
                            :error="errors.location"
                            @valueChanged="valueChanged"
                        />
                    </label>
                </div>
                <div
                    class="form-row form-row--max"
                    v-if="propertyMap?.[_postType].includes('coverImage')"
                    :style="`order: ${propertyMap?.[_postType].indexOf(
                        'coverImage'
                    )};`"
                >
                    <div class="form-label">
                        <span class="form-label__text">
                            {{ $t('Cover image') }}
                        </span>
                        <FileUpload
                            to="/admin/manage-blog/upload_cover"
                            :limit="1"
                            :small="true"
                            inputName="coverImage"
                            :files="
                                post?.cover_image ? [post?.cover_image] : null
                            "
                            :error="errors.coverImage"
                            :parentForm="this.form"
                            @returnValue="valueChanged"
                        />
                    </div>
                </div>

                <div
                    class="form-row form-row--max"
                    v-if="propertyMap?.[_postType].includes('sector')"
                    :style="`order: ${propertyMap?.[_postType].indexOf(
                        'sector'
                    )};`"
                >
                    <div class="form-label">
                        <span class="form-label__text">
                            {{ $t('Sectors') }}
                        </span>
                        <TagInput
                            inputName="sector"
                            :error="errors.sector"
                            :limitTags="true"
                            :availableTags="
                                sectors.map((sector) => {
                                    return {
                                        id: sector.id,
                                        text: sector.name,
                                    };
                                })
                            "
                            :defaultTags="postSectors"
                            @valueChanged="valueChanged"
                        ></TagInput>
                    </div>
                </div>
                <div
                    class="form-row form-row--max"
                    v-if="propertyMap?.[_postType].includes('tags')"
                    :style="`order: ${propertyMap?.[_postType].indexOf(
                        'tags'
                    )};`"
                >
                    <div class="form-label">
                        <span class="form-label__text">
                            {{ $t('Tags') }}
                        </span>
                        <TagInput
                            inputName="tags"
                            :error="errors.tags"
                            :limitTags="true"
                            :availableTags="allTags"
                            :defaultTags="postTags"
                            @valueChanged="valueChanged"
                        ></TagInput>
                    </div>
                </div>

                <div
                    class="form-row"
                    v-if="
                        propertyMap?.[_postType].includes('mainContent') &&
                        postType !== 'Case Study'
                    "
                    :style="`order: ${propertyMap?.[_postType].indexOf(
                        'mainContent'
                    )};`"
                >
                    <div class="form-label">
                        <span class="form-label__text">
                            {{ $t('Main Article') }}
                        </span>
                        <WysiwygInput
                            :inputName="'mainContent'"
                            :error="errors.mainContent"
                            :value="form?.mainContent || 'No content provided'"
                            @valueChanged="valueChanged"
                        ></WysiwygInput>
                    </div>
                </div>
            </div>

            <div class="card card--large flex-col" v-if="_postType == 5">
                <h2 class="h4 h1--extra-margin">{{ $t('Video') }}</h2>
                <div class="form-row form-row--max">
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Video selection') }}
                        </span>
                        <VideoUpload
                            inputName="video"
                            :error="errors.video"
                            :defaultUrl="post?.youtube"
                            :defaultImage="
                                post?.youtube_poster?.[0] || post?.cover_image
                            "
                            @valueChanged="valueChanged"
                        />
                    </label>
                </div>
            </div>

            <div class="card card--large flex-col" v-if="_postType == 4">
                <h2 class="h4 h1--extra-margin">
                    {{ $t('Add Document') }}
                </h2>
                <div class="form-row form-row--max">
                    <div class="form-label">
                        <FileUpload
                            to="/admin/manage-blog/upload_document"
                            :limit="1"
                            :small="true"
                            accept="doc/*"
                            inputName="file"
                            :files="post?.document ? [post?.document] : null"
                            :error="errors.file"
                            :parentForm="this.form"
                            @returnValue="valueChanged"
                        />
                    </div>
                </div>
            </div>

            <div class="card card--large flex-col" v-if="_postType == 3">
                <h2 class="h4 h1--extra-margin">
                    {{ $t('Project details') }}
                </h2>
                <div class="col">
                    <div class="form-row form-row--max">
                        <div class="form-label">
                            <span class="form-label__text">
                                {{ $t('Type') }}
                            </span>
                            <ToggleBox
                                inputName="projectType"
                                :defaultValue="form.projectType"
                                :options="[
                                    { id: 0, name: 'New build' },
                                    { id: 1, name: 'Refurbished' },
                                ]"
                                :error="errors.projectType"
                                wrapperClass="toggle-box--full"
                                @valueChanged="valueChanged"
                            />
                        </div>
                    </div>
                    <div class="form-row form-row--max">
                        <div class="form-label">
                            <span class="form-label__text">
                                {{ $t('Project completed on') }}
                            </span>
                            <DatePicker
                                inputName="projectCompletion"
                                :defaultValue="formattedCompletedDate"
                                :error="errors.projectCompletion"
                                @valueChanged="valueChanged"
                            />
                        </div>
                    </div>
                </div>
                <div class="form-row form-row--max">
                    <div class="form-label">
                        <span class="form-label__text">
                            {{ $t('Add products') }}
                        </span>

                        <MaterialsPicker
                            inputName="materials"
                            title="Materials"
                            :multiple="true"
                            :options="products"
                            :selectedOption="form?.materials"
                            :isAdmin="true"
                            :error="errors.materials"
                            @valueChanged="valueChanged"
                        />
                    </div>
                </div>

                <div
                    class="form-row"
                    v-if="propertyMap?.[_postType].includes('gallery')"
                    :style="`order: ${propertyMap?.[_postType].indexOf(
                        'gallery'
                    )};`"
                >
                    <div class="form-label">
                        <span class="form-label__text">
                            {{ $t('Gallery') }}
                        </span>
                        <FileUpload
                            to="/my-account/profile/upload-gallery"
                            :limit="10"
                            :small="false"
                            :inputName="'gallery'"
                            :files="post?.gallery || null"
                            :error="errors.gallery"
                            :parentForm="this.form"
                            @returnValue="valueChanged"
                        />
                    </div>
                </div>
            </div>
        </form>

        <button
            class="button button--fill button--slim"
            :class="{ disabled: !isLoaded }"
            type="button"
            @click="openWarningPopup('delete')"
        >
            {{ $t('Delete post') }}
        </button>
        <AlertBox
            v-if="warningPopupOpen"
            :active="warningPopupOpen"
            @active-state="closeWarningPopup"
        >
            <img
                class="login-alert__icon"
                src="../../../../images/alert-outline.svg"
                alt=""
                width="60"
                height="60"
            />
            <h3 class="alert-box__title">
                {{
                    warningPopupMode == 'type'
                        ? $t('Are you sure you want to change post type?')
                        : $t('Are you sure you want to delete this post?')
                }}
            </h3>
            <p class="m-top" v-if="warningPopupMode == 'type'">
                {{ $t('You will lose any fields currently set.') }}
            </p>
            <button
                type="button"
                class="button button--fill button--span button--thin button-reset alert-box__cta"
                @click="confirmPopup"
            >
                {{
                    warningPopupMode == 'type'
                        ? $t('Change post type')
                        : $t('Delete post')
                }}
            </button>
        </AlertBox>
    </layout-default>
</template>

<script>
import _ from 'lodash';
import moment from 'moment';
import LayoutDefault from '../../../Layout/Admin.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { useForm } from '@inertiajs/inertia-vue3';
import EditLayout from '../Layout/EditLayout.vue';
import TextInput from '../../../Shared/Inputs/TextInput.vue';
import FileUpload from '../../../Shared/Inputs/FileUpload.vue';
import TagInput from '../../../Shared/Inputs/TagInput.vue';
import WysiwygInput from '../../../Shared/Inputs/WysiwygInput.vue';
import TextAreaInput from '../../../Shared/Inputs/TextAreaInput.vue';
import ToggleBox from '../../../Shared/Inputs/ToggleBox.vue';
import DatePicker from '../../../Shared/Inputs/DatePicker.vue';
import SocialMediaSelector from '../../../Shared/Inputs/SocialMediaSelector.vue';
import DropdownInput from '../../../Shared/Inputs/DropdownInput.vue';
import StatusDropdown from '../../../Shared/Inputs/StatusDropdown.vue';
import MaterialsPicker from '../../../Shared/Inputs/MaterialsPicker.vue';
import AlertBox from '../../../Shared/AlertBox.vue';
import LocationInput from '../../../Shared/Inputs/LocationInput.vue';
import VideoUpload from '../../../Shared/Inputs/VideoUpload.vue';
import ContinueButton from '../../../Shared/Inputs/ContinueButton.vue';

export default {
    components: {
        LayoutDefault,
        EditLayout,
        Link,
        TextInput,
        FileUpload,
        TagInput,
        WysiwygInput,
        TextAreaInput,
        ToggleBox,
        DatePicker,
        SocialMediaSelector,
        DropdownInput,
        StatusDropdown,
        MaterialsPicker,
        AlertBox,
        LocationInput,
        VideoUpload,
        ContinueButton,
    },
    props: {
        title: String,
        errors: Object,
        postType: String,
        materials: Array,
        post: Object,
        sectors: Array,
        tags: Array,
        new: Boolean,
        fileUploads: Object,
    },
    mounted() {
        console.log(this.post);

        if (this.postType) {
            this._postType = this.postType;
            this.dropdownPostType = this.postType;
        } else if (this?.post?.post_type?.id) {
            this._postType = this?.post?.post_type?.id;
            this.dropdownPostType = this?.post?.post_type;
        } else {
            this._postType = 2;
        }
    },
    data() {
        return {
            form: null,
            isLoaded: true,
            _postType: 2,
            postTypes: {
                2: 'Articles',
                3: 'Case Studies',
                4: 'Technical Documents',
                5: 'Installation Videos',
                6: 'Social Media Accounts',
                7: 'Animal Welfare',
            },
            dropdownPostType: { id: 2 },
            hasChanged: false,
            propertyMap: {
                2: ['title', 'coverImage', 'sector', 'tags', 'mainContent'],
                5: ['title', 'mainContent', 'sector', 'tags', 'video'],
                4: [
                    'title',
                    'coverImage',
                    'mainContent',
                    'sector',
                    'tags',
                    'file',
                ],
                3: [
                    'title',
                    'location',
                    'coverImage',
                    'sector',
                    'tags',
                    'projectType',
                    'mainContent',
                    'gallery',
                    'projectCompletion',
                    'materials',
                ],
                6: [
                    'title',
                    'coverImage',
                    'tags',
                    'sector',
                    'social_media_link',
                    'mainContent',
                ],
                7: ['title', 'coverImage', 'sector', 'tags', 'mainContent'],
            },
            warningPopupOpen: false,
            warningPopupMode: null,
            newPostType: null,
        };
    },
    setup(props) {
        let postSectors = [];
        let postTags = [];

        let combination = props?.post?.post_tags;
        if (combination?.length) {
            combination.forEach((label) => {
                if (
                    props?.sectors.filter((item) => item.name == label.name)
                        .length
                ) {
                    let sector = props?.sectors.filter(
                        (item) => item.name == label.name
                    )[0];

                    postSectors.push({ id: sector.id, text: sector.name });
                } else if (
                    props?.tags.filter((item) => item.name == label.name).length
                ) {
                    let tag = props?.tags.filter(
                        (item) => item.name == label.name
                    )[0];

                    postTags.push({ id: tag.id, text: tag.name });
                }
            });
        }

        if (props.post?.sector) {
            if (typeof props.post.sector == 'object') {
                postSectors = _.union(postSectors, [
                    {
                        id: props.post.sector?.id,
                        text: props.post.sector?.name,
                    },
                ]);
            } else {
                postSectors = _.union(
                    postSectors,
                    props.post.sector.map((sect) => {
                        return {
                            id: sect.id,
                            text: sect.name,
                        };
                    })
                );
            }
        }

        const form = useForm({
            mainContent: props?.post?.post_content,
            sector: postSectors,
            tags: postTags,
            title: props?.post?.post_title,
            coverImage: props?.post?.cover_image
                ? props?.post?.cover_image?.path +
                  '/' +
                  props?.post?.cover_image?.filename
                : null,
            file: props?.post?.document
                ? props?.post?.document?.path +
                  '/' +
                  props?.post?.document?.filename
                : null,
            gallery: props?.post?.gallery.map((image) => {
                return image.path + '/' + image.filename;
            }),
            location: props?.post?.location,
            materials: props?.post?.materials,
            projectType: props?.post?.type,
            projectCompletion: props?.post?.completed_on,
            social_media_link: props?.post?.social_media_link,
            video: props.post?.youtube_poster?.[0] || {},
        });
        return { form, postSectors, postTags };
    },
    methods: {
        submitForm() {
            console.log(this.form);

            let _form = {};

            for (let [key, value] of Object.entries(this.form)) {
                if (this.propertyMap[this._postType].includes(key)) {
                    let newValue = value;

                    if (key == 'sector') {
                        newValue = value.map((sector) => {
                            return sector.id;
                        });
                    } else if (key == 'tags') {
                        newValue = value.map((tag) => {
                            return tag.id;
                        });
                    } else if (key == 'video') {
                        console.log('video:', value);
                        _form['videoUrl'] = value.url;
                        _form['videoCustomThumbnail'] = value.useCustom;

                        if (value.thumbnail.hasOwnProperty('storage')) {
                            _form['videoThumbnail'] = value.thumbnail.storage;
                        } else {
                            _form['videoThumbnail'] = value.thumbnail;
                        }
                        continue;
                    }

                    _form[key] = newValue;
                }
            }

            _form['post_type'] = this._postType;

            console.log('form with only relevant fields:', _form);

            if (this.post) {
                // inertia call to edit
                this.$inertia.post(
                    `/admin/manage-blog/${this.post.id}/store-blog`,
                    _form,
                    {
                        onBefore: () => {
                            this.isLoaded = false;
                        },
                        onFinish: () => {
                            this.isLoaded = true;
                        },
                    }
                );
            } else {
                // inertia call to create
                this.$inertia.post(`/admin/manage-blog/add-blog`, _form, {
                    onBefore: () => {
                        this.isLoaded = false;
                    },
                    onFinish: () => {
                        this.isLoaded = true;
                    },
                });
            }
        },
        valueChanged(change) {
            if (change.input == 'document') {
                console.log('files: ', change.value);
                this.form['file'] = change.value;
            } else {
                this.form = {
                    ...this.form,
                    [change.input]: change.value,
                };
            }

            this.hasChanged = true;
        },
        changePostType(id) {
            console.log('changing post type to:', id);
            this._postType = id;
            this.dropdownPostType = { id, value: this.postTypes[id] };
            console.log('post type:', this._postType);
        },
        openWarningPopup(mode, change) {
            if (mode == 'type') {
                console.log('change:', change);
                this.newPostType = change.value.id;
                console.log('post type change:', this.newPostType);
            }

            this.warningPopupMode = mode;
            this.warningPopupOpen = true;
        },
        closeWarningPopup() {
            this.warningPopupMode = null;
            this.warningPopupOpen = false;
            this.newPostType = null;
        },
        confirmPopup(data) {
            console.log('trying to confirm:', data);

            if (this.warningPopupMode == 'delete') {
                console.log('deleting', this.post.id);
                // inertia call to delete post
                this.$inertia.post(
                    `/admin/manage-blog/${this?.post?.id}/delete`,
                    this?.post?.id,
                    {
                        onBefore: () => {
                            this.isLoaded = false;
                        },
                        onFinish: () => {
                            this.isLoaded = true;
                        },
                    }
                );
            } else if (this.warningPopupMode == 'type') {
                console.log('changing type');
                console.log(this.newPostType);
                this.changePostType(this.newPostType);
            }
            this.closeWarningPopup();
        },
    },
    computed: {
        allTags() {
            return this.tags.map((tag) => {
                return { id: tag.id, text: tag.name };
            });
        },
        formattedCompletedDate() {
            return moment(this?.post?.completed_on).format('DD/MM/YYYY');
        },
    },
};
</script>
