<template>
    <layout-default>
        <div class="admin-back">
            <Link href="/admin/manage-experts" class="popup__back button-reset">
                {{ $t('Back to overview') }}
            </Link>
            <ContinueButton
                v-if="isEditing"
                :text="this?.data ? 'Save changes' : 'Save expert'"
                :isEnabled="isLoaded"
                :isLoading="!isLoaded"
                wrapperClass="button--thin button--no-margin admin-title__cta"
                @click="editUser"
            />
        </div>

        <div class="admin-title-wrapper">
            <h1 class="admin-title">{{ $t('Expert profile') }}</h1>
            <StatusDropdown
                v-if="isEditing"
                inputName="expert_type"
                :defaultOption="{
                    id: data?.expert_type_id || 2,
                    value: data?.expert_type?.name || 'Eternit Sales',
                }"
                :options="expertTypes"
                @valueChanged="updateExpertType"
            />
        </div>

        <p class="error-message" v-if="errors?.id">
            {{ errors?.id }}
        </p>

        <UserProfile
            :user="data"
            userType="expert"
            :edit="isEditing"
            :errors="errors"
            @valueChanged="valueChanged"
        />

        <button
            v-if="data"
            class="button button--fill button--slim"
            :class="{ disabled: !isLoaded }"
            type="button"
            @click="deleteUser"
        >
            {{ $t('Delete expert') }}
        </button>
        <button
            v-if="!isEditing"
            class="button button--fill button--slim"
            :class="{ disabled: !isLoaded }"
            type="button"
            @click="initEdit"
        >
            {{ $t('Edit expert') }}
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
                {{ $t('Are you sure you want to delete this expert?') }}
            </h3>
            <button
                type="button"
                class="button button--fill button--span button--thin button-reset alert-box__cta"
                @click="deleteUser()"
            >
                {{ $t('Delete expert') }}
            </button>
        </AlertBox>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../../Layout/Admin.vue';
import UserProfile from '../../../Pages/Admin/UserProfile.vue';
import ContinueButton from '../../../Shared/Inputs/ContinueButton.vue';
import AlertBox from '../../../Shared/AlertBox.vue';
import StatusDropdown from '../../../Shared/Inputs/StatusDropdown.vue';

export default {
    mounted() {
        if (this.edit || !this.data) {
            this.isEditing = true;
        }

        const queryString = window.location.search;
        if (queryString) {
            const urlParams = new URLSearchParams(queryString);
            this.isEditing = Boolean(urlParams.get('edit'));
        }

        // set default expert type
        this.expertType = this?.data?.expert_type_id || 2;

        // remove installer as an option and format options
        this.expertTypes = this.expert_types
            .filter((type) => type.id !== 1)
            .map((type) => {
                return {
                    id: type.id,
                    value: type.name,
                };
            });
    },
    components: {
        LayoutDefault,
        Link,
        UserProfile,
        ContinueButton,
        AlertBox,
        StatusDropdown,
    },
    props: {
        data: Object,
        edit: {
            type: Boolean,
            default: false,
        },
        sectors: Array,
        specialities: Array,
        contactAllowed: Boolean,
        errors: Object,
        expert_types: Array,
    },
    data() {
        return {
            isEditing: false,
            deletePopup: false,
            form: null,
            expertTypes: [],
            expertType: 2,
            isLoaded: true,
        };
    },
    methods: {
        valueChanged(form) {
            this.form = {
                ...form,
                expert_type: this.expertType,
            };
        },
        updateExpertType(change) {
            // value.id
            this.expertType = change?.value?.id || 2;
        },
        editUser() {
            // add expert type to form if not set
            this.form['expert_type'] = this.expertType;

            // if no previous user object, then it's an add
            if (!this.data) {
                this.$inertia.post(
                    `/admin/manage-experts/add-expert`,
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
                    `/admin/manage-experts/${this.data.id}/edit-expert`,
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

        deleteUser() {
            if (!this.deletePopup) {
                this.openDeletePopup();
            } else {
                // inertia call here
                this.$inertia.post(
                    `/admin/manage-experts/${this.data.id}/delete-expert`,
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
};
</script>
