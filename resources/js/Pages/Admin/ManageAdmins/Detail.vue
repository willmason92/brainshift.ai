<template>
    <layout-default>
        <div class="admin-back">
            <Link href="/admin/manage-admins" class="popup__back button-reset">
                {{ $t('Back to overview') }}
            </Link>
            <ContinueButton
                :text="admin ? 'Save changes' : 'Add admin'"
                :isEnabled="isLoaded"
                :isLoading="!isLoaded"
                wrapperClass="button--thin button--no-margin admin-title__cta"
                @click="editAdmin"
            />
        </div>
        <h1 class="admin-title">{{ $t('Admin profile') }}</h1>

        <p class="error-message" v-if="errors?.id">
            {{ errors?.id }}
        </p>

        <UserProfile
            :user="admin"
            userType="admin"
            :edit="true"
            :errors="errors"
            @valueChanged="valueChanged"
        />

        <button
            v-if="admin"
            class="button button--fill button--slim"
            :class="{ disabled: !isLoaded }"
            type="button"
            @click="deleteUser"
        >
            {{ $t('Delete admin') }}
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
                {{ $t('Are you sure you want to delete this admin?') }}
            </h3>
            <button
                type="button"
                class="button button--fill button--span button--thin button-reset alert-box__cta"
                @click="deleteUser()"
            >
                {{ $t('Delete admin') }}
            </button>
        </AlertBox>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../../Layout/Admin.vue';
import UserProfile from '../UserProfile.vue';
import ContinueButton from '../../../Shared/Inputs/ContinueButton.vue';
import AlertBox from '../../../Shared/AlertBox.vue';

export default {
    mounted() {
        this.form.name = this.admin?.name;
        this.form.region = this.admin?.regions.id;
        this.form.email = this.admin?.email;
    },
    components: {
        LayoutDefault,
        Link,
        UserProfile,
        ContinueButton,
        AlertBox,
    },
    props: {
        admin: Object,
        regions: Array,
        errors: Object,
    },
    data() {
        return {
            deletePopup: false,
            form: {},
            isLoaded: true,
        };
    },
    methods: {
        valueChanged(form) {
            this.form = form;
        },
        editAdmin() {
            if (this.admin) {
                this.$inertia.post(
                    `/admin/manage-admins/${this.admin.id}/edit-admin`,
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
                    `/admin/manage-admins/add-admin`,
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
                this.$inertia.post(
                    `/admin/manage-admins/${this.admin.id}/delete-admin`,
                    this.admin.id,
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
