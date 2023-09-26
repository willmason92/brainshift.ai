<template>
    <layout-default>
        <div class="admin-back">
            <Link
                href="/admin/manage-materials"
                class="popup__back button-reset"
            >
                {{ $t('Back to overview') }}
            </Link>
            <ContinueButton
                :text="material ? 'Save material' : 'Add material'"
                :isEnabled="isEnabled && isLoaded"
                :isLoading="!isLoaded"
                wrapperClass="button--thin admin-title__cta"
                @continue="updateMaterial"
            />
        </div>
        <h1 class="admin-title">
            {{ material ? $t('Edit material') : $t('Add material') }}
        </h1>
        <div class="card admin-details-wrapper">
            <h2 class="admin-subtitle">{{ $t('General') }}</h2>
            <div class="add-product-wrapper">
                <label class="admin-label">
                    <h3 class="admin-label__text">{{ $t('Name') }}</h3>
                    <TextInput
                        inputName="name"
                        :max="254"
                        :value="material?.name"
                        :placeholder="$t('Enter the name of the product')"
                        :error="errors.name"
                        @valueChanged="valueChanged"
                    />
                </label>
                <div class="admin-label">
                    <h3 class="admin-label__text">{{ $t('Product line') }}</h3>
                    <DropdownInput
                        inputName="productLine"
                        :options="productLines"
                        :defaultOption="material?.product_line_id || null"
                        :error="errors.productLine"
                        @valueChanged="valueChanged"
                    />
                </div>
                <label class="admin-label">
                    <h3 class="admin-label__text">{{ $t('Image') }}</h3>
                    <FileUpload
                        to="/admin/manage-materials/upload-image"
                        :limit="1"
                        inputName="image"
                        accept="image/*"
                        :files="material?.file ? [material.file] : null"
                        :error="errors?.image ? [errors?.image] : null"
                        @returnValue="valueChanged"
                    />
                </label>
                <div class="admin-label">
                    <h3 class="admin-label__text">{{ $t('Colour') }}</h3>
                    <DropdownInput
                        inputName="colour"
                        :options="
                            colours.map((colour) => {
                                return { id: colour.id, text: colour.name };
                            })
                        "
                        :defaultOption="material?.colour?.id || null"
                        :error="errors.colour"
                        @valueChanged="valueChanged"
                    />
                </div>
                <label class="admin-label">
                    <h3 class="admin-label__text">{{ $t('Shop URL') }}</h3>
                    <TextInput
                        inputName="url"
                        format="url"
                        :placeholder="$t('Enter a shop URL')"
                        :error="errors.url"
                        :value="material?.shop_url"
                        @valueChanged="valueChanged"
                    />
                </label>
            </div>
        </div>

        <button
            v-if="material"
            class="button button--fill button--slim"
            :class="{ disabled: !isLoaded }"
            type="button"
            @click="deleteMaterial"
        >
            {{ $t('Delete material') }}
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
                {{ $t('Are you sure you want to delete this material?') }}
            </h3>
            <button
                type="button"
                class="button button--fill button--span button--thin button-reset alert-box__cta"
                @click="deleteMaterial()"
            >
                {{ $t('Delete material') }}
            </button>
        </AlertBox>
    </layout-default>
</template>
<script>
import { Link } from '@inertiajs/inertia-vue3';
import { useForm } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../../Layout/Admin.vue';
import TextInput from '../../../Shared/Inputs/TextInput.vue';
import ContinueButton from '../../../Shared/Inputs/ContinueButton.vue';
import TagInput from '../../../Shared/Inputs/TagInput.vue';
import FileUpload from '../../../Shared/Inputs/FileUpload';
import TextAreaInput from '../../../Shared/Inputs/TextAreaInput.vue';
import DropdownInput from '../../../Shared/Inputs/DropdownInput.vue';
import AlertBox from '../../../Shared/AlertBox.vue';

export default {
    components: {
        LayoutDefault,
        Link,
        TextInput,
        ContinueButton,
        TagInput,
        FileUpload,
        TextAreaInput,
        DropdownInput,
        AlertBox,
    },
    props: {
        material: Object,
        colours: Array,
        productLines: Array,
        errors: Object,
    },
    mounted() {
        if (this.material) {
            this.isEnabled = true;
        }
    },
    setup(props) {
        const form = useForm({
            name: props?.material?.name,
            productLine: props?.material?.product_line_id,
            image:
                props?.material?.file.path +
                '/' +
                props?.material?.file.filename,
            colour: props?.material?.colour?.id,
            url: props?.material?.shop_url,
        });
        return { form };
    },
    data() {
        return {
            isEnabled: false,
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

            // check if form is filled out to enable save button
            if (this.material) {
                this.isEnabled = true;
            } else if (
                this.form.name &&
                this.form.productLine != null &&
                // this.form.image &&
                this.form.colour != null &&
                this.form.url
            ) {
                this.isEnabled = true;
            } else {
                this.isEnabled = false;
            }
        },
        updateMaterial() {
            if (this.material) {
                this.$inertia.post(
                    `/admin/manage-materials/${this.material.id}/edit-material`,
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
            } else {
                this.$inertia.post(
                    `/admin/manage-materials/add-material`,
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
            }
        },

        deleteMaterial() {
            if (!this.deletePopup) {
                this.openDeletePopup();
            } else {
                this.$inertia.post(
                    `/admin/manage-materials/${this.material.id}/delete-material`,
                    this.material.id,
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
        closePopup() {
            this.deletePopup = false;
        },
        openDeletePopup() {
            this.deletePopup = true;
        },
    },
};
</script>
