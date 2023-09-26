<template>
    <layout-default>
        <div class="admin-back">
            <Link href="/admin/manage-regions" class="popup__back button-reset">
                {{ $t('Back to overview') }}
            </Link>
            <ContinueButton
                text="Save changes"
                :isEnabled="isLoaded"
                :isLoading="!isLoaded"
                wrapperClass="button--thin button--no-margin admin-title__cta"
                @click="editRegion"
            />
        </div>
        <div class="admin-title-wrapper">
            <h1 class="admin-title">
                {{ $t('Region:') }}
                {{ isNew ? $t('Create new region') : data?.region_name }}
            </h1>
        </div>

        <form class="form-page" @submit.prevent="submitForm()">
            <div class="card card--large flex-col">
                <div class="form-row form-row--max">
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Region name') }}
                        </span>
                        <TextInput
                            inputName="name"
                            :value="data?.region_name"
                            :placeholder="$t('Add a name for the region')"
                            :error="errors.name"
                            @valueChanged="valueChanged"
                        ></TextInput>
                    </label>
                </div>

                <div class="form-row form-row--max">
                    <label class="form-label">
                        <span class="form-label__text">
                            {{ $t('Assigned regions') }}
                        </span>
                        <SelectSides
                            inputName="postcodes"
                            :defaultOptions="selected_postcodes || []"
                            :options="available_postcodes"
                            :error="errors.postcodes"
                            @valueChanged="valueChanged"
                        />
                    </label>
                </div>
            </div>
        </form>
        <button
            v-if="data"
            class="button button--fill button--slim"
            :class="{ disabled: !isLoaded }"
            type="button"
            @click="deleteRegion"
        >
            {{ $t('Delete region') }}
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
                {{ $t('Are you sure you want to delete this region?') }}
            </h3>
            <button
                type="button"
                class="button button--fill button--span button--thin button-reset alert-box__cta"
                @click="deleteRegion()"
            >
                {{ $t('Delete region') }}
            </button>
        </AlertBox>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { useForm } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../../Layout/Admin.vue';
import ContinueButton from '../../../Shared/Inputs/ContinueButton.vue';
import TextInput from '../../../Shared/Inputs/TextInput.vue';
import SelectSides from '../../../Shared/Inputs/SelectSides.vue';
import AlertBox from '../../../Shared/AlertBox.vue';

export default {
    components: {
        LayoutDefault,
        Link,
        ContinueButton,
        TextInput,
        SelectSides,
        AlertBox,
    },
    props: {
        data: Object,
        new: Boolean,
        errors: Object,
        selected_postcodes: Array,
        available_postcodes: Array,
    },
    mounted() {
        if (this.new) {
            this.isNew = this.new;
        }

        const queryString = window.location.search;
        if (queryString) {
            const urlParams = new URLSearchParams(queryString);
            this.isNew = Boolean(urlParams.get('new'));
        }
    },
    data() {
        return {
            isNew: false,
            deletePopup: false,
            isLoaded: true,
        };
    },
    setup(props) {
        const form = useForm({
            name: props?.data?.region_name,
            postcodes: props?.selected_postcodes?.map((postcode) => {
                return postcode.id;
            }),
            oldPostcodes: props?.selected_postcodes?.map((postcode) => {
                return postcode.id;
            }),
        });
        return { form };
    },
    methods: {
        valueChanged(change) {
            if (change.input == 'postcodes') {
                this.form[change.input] = change.value.map((postcode) => {
                    return postcode.id;
                });
            } else {
                this.form[change.input] = change.value;
            }
        },
        editRegion() {
            if (this.isNew) {
                // inertia call to create region
                this.$inertia.post(
                    `/admin/manage-regions/store-region`,
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
                // inertia call to edit region
                this.$inertia.post(
                    `/admin/manage-regions/${this.data.id}/edit-region`,
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
        deleteRegion() {
            if (!this.deletePopup) {
                this.openDeletePopup();
            } else {
                // inertia call to delete region
                this.$inertia.post(
                    `/admin/manage-regions/${this.data.id}/delete-region`,
                    this.data.id,
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
