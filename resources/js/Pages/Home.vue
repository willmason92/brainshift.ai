<template>
    <layout-default v-bind:user="user">
        <h1>{{ $t('Welcome to BrainShift') }}</h1>
        <div
            v-if="isInstaller && profileCompleted == false"
            class="card card--center"
        >
            <p>
                Finish up setting up your account to use the full potential of
                the platform and to receive contact requests
            </p>
            <Link
                href="/my-account/profile"
                class="button button-reset button--fill button--full"
            >
                Set up your profile
            </Link>
        </div>
        <div
            v-if="isInstaller"
            class="card"
            :class="{ 'card--disabled': profileCompleted == false }"
        >
            <Link href="/requests" class="card__link">
                <span class="card__pill pill" v-if="newRequests"
                    >{{ newRequests }} {{ $t('new') }}</span
                >
                <img class="card__icon" src="../../images/requests-icon.svg" />
                <h2>{{ $t('Requests') }}</h2>
                <p>{{ $t('Projects to follow up on') }}</p>
            </Link>
        </div>
        <div
            v-if="isInstaller"
            class="card"
            :class="{ 'card--disabled': profileCompleted == false }"
        >
            <Link href="/my-account/profile" class="card__link">
                <img
                    class="card__icon"
                    src="../../images/installer-profile-icon.svg"
                />
                <h2>{{ $t('Your installer profile') }}</h2>
                <p>{{ $t('Keep it up to date') }}</p>
            </Link>
        </div>
        <div class="card">
            <Link href="/farmers-library" class="card__link">
                <img
                    class="card__icon"
                    src="../../images/farmers-library-icon.svg"
                />
                <h2>{{ $t('Farmers Library') }}</h2>
                <p>{{ $t('Fresh finds from the field') }}</p>
            </Link>
        </div>
        <p class="info-message" v-if="!isLoggedIn">
            {{
                $t(
                    'You must be logged in to use our shed solution configurator.'
                )
            }}
            <a href="/login" class="login-alert-trigger">{{
                $t('Click here to login or register')
            }}</a
            >.
        </p>
        <div class="card" :class="{ 'card--disabled': !isLoggedIn }">
            <Link href="/shed-solution" class="card__link">
                <img
                    class="card__icon"
                    src="../../images/agricultural-building-icon.svg"
                />
                <h2>{{ $t('Your new agricultural building') }}</h2>
                <p>{{ $t('Bring your building to life') }}</p>
            </Link>
        </div>
        <div class="card">
            <Link href="/find-an-expert" class="card__link">
                <img
                    class="card__icon"
                    src="../../images/find-expert-icon.svg"
                />
                <h2>{{ $t('Find an expert') }}</h2>
                <p>{{ $t('Talk to the right people') }}</p>
            </Link>
        </div>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { usePage } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../Layout/Base.vue';

export default {
    components: {
        LayoutDefault,
        Link,
    },
    props: {
        user: Object,
    },
    data() {
        return {
            newRequests: usePage().props.value.installer.new_request_count,
            profileCompleted: usePage().props.value.installer.profile_complete,
        };
    },
    computed: {
        isInstaller: function () {
            return this.user !== null && this.user.roles.includes('installer');
        },
        isLoggedIn: function () {
            return this.user !== null;
        },
    },
};
</script>
