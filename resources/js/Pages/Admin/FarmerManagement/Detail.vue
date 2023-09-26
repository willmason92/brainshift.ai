<template>
    <layout-default>
        <div class="admin-back">
            <Link
                href="/admin/manage-farmers"
                class="popup__back button-reset admin-back"
            >
                {{ $t('Back to overview') }}
            </Link>
            <ContinueButton
                v-if="isEditing"
                text="Save changes"
                :isEnabled="isLoaded"
                :isLoading="!isLoaded"
                wrapperClass="button--thin button--no-margin"
                @click="editUser"
            />
        </div>

        <h1 class="admin-title">{{ $t('Farmer profile') }}</h1>

        <p class="error-message" v-if="errors?.id">
            {{ errors?.id }}
        </p>

        <UserProfile
            :user="data"
            userType="farmer"
            :edit="isEditing"
            :errors="errors"
            @valueChanged="valueChanged"
        />

        <button
            v-if="hasRole('superadmin')"
            class="button button--fill button--slim"
            :class="{ disabled: !isLoaded }"
            type="button"
            @click="deleteUser"
        >
            {{ $t('Delete farmer') }}
        </button>
        <button
            v-if="!isEditing && hasRole('superadmin')"
            class="button button--fill button--slim"
            :class="{ disabled: !isLoaded }"
            type="button"
            @click="initEdit"
        >
            {{ $t('Edit farmer') }}
        </button>
        <AlertBox
            v-if="deletePopup && hasRole('superadmin')"
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
                @click="deleteUser()"
            >
                {{ $t('Delete farmer') }}
            </button>
        </AlertBox>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { usePage } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../../Layout/Admin.vue';
import UserProfile from '../UserProfile.vue';
import ContinueButton from '../../../Shared/Inputs/ContinueButton.vue';
import AlertBox from '../../../Shared/AlertBox.vue';
import useUserRole from '../../../Composables/useUserRole';

export default {
    components: {
        LayoutDefault,
        Link,
        UserProfile,
        ContinueButton,
        AlertBox,
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
    },
    setup() {
        const { hasRole } = useUserRole();
        return { hasRole };
    },
    mounted() {
        if (this.edit) {
            this.isEditing = this.edit;
        }
        const queryString = window.location.search;
        if (queryString && hasRole('superadmin')) {
            const urlParams = new URLSearchParams(queryString);
            this.isEditing = Boolean(urlParams.get('edit'));
        }
    },
    data() {
        return {
            isEditing: false,
            form: null,
            deletePopup: false,
            user: usePage().props?.value?.user,
            isLoaded: true,
        };
    },
    methods: {
        valueChanged(form) {
            this.form = form;
        },
        editUser() {
            // inertia call here
            this.$inertia.post(
                `/admin/manage-farmers/${this.data.id}/edit-farmer`,
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
        deleteUser() {
            if (!this.deletePopup) {
                this.openDeletePopup();
            } else {
                // inertia call here
                this.$inertia.post(
                    `/admin/manage-farmers/${this.data.id}/delete-farmer`,
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
