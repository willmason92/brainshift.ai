<template>
    <layout-default>
        <div class="admin-back">
            <Link
                href="/admin/manage-installers"
                class="popup__back button-reset"
            >
                {{ $t('Back to overview') }}
            </Link>
            <ContinueButton
                v-if="isEditing"
                :text="$t('Save changes')"
                :isEnabled="isLoaded"
                :isLoading="!isLoaded"
                wrapperClass="button--thin button--no-margin"
                @click="editUser"
            />
        </div>

        <h1 class="admin-title">{{ $t('Installer profile') }}</h1>

        <p class="error-message" v-if="errors?.id">
            {{ errors?.id }}
        </p>

        <UserProfile
            :user="data"
            userType="installer"
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
            {{ $t('Delete installer') }}
        </button>
        <button
            v-if="!isEditing"
            class="button button--fill button--slim"
            :class="{ disabled: !isLoaded }"
            type="button"
            @click="initEdit"
        >
            {{ $t('Edit installer') }}
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
                {{ $t('Are you sure you want to delete this installer?') }}
            </h3>
            <button
                type="button"
                class="button button--fill button--span button--thin button-reset alert-box__cta"
                @click="deleteUser()"
            >
                {{ $t('Delete installer') }}
            </button>
        </AlertBox>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { usePage } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../../Layout/Admin.vue';
import UserProfile from '../../../Pages/Admin/UserProfile.vue';
import ContinueButton from '../../../Shared/Inputs/ContinueButton.vue';
import AlertBox from '../../../Shared/AlertBox.vue';
import useUserRole from '../../../Composables/useUserRole';

export default {
    setup() {
        const { hasRole } = useUserRole();
        return { hasRole };
    },
    mounted() {
        if (this.edit) {
            this.isEditing = this.edit;
        }
        const queryString = window.location.search;
        if (queryString) {
            const urlParams = new URLSearchParams(queryString);
            this.isEditing = Boolean(urlParams.get('edit'));
        }
    },
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
    data() {
        return {
            isEditing: false,
            deletePopup: false,
            form: null,
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
            console.log(this.form);
            this.$inertia.post(
                `/admin/manage-installers/${this.data.id}/edit-installer`,
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
                this.$inertia.post(
                    `/admin/manage-installers/${this.data.id}/delete-installer`,
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
