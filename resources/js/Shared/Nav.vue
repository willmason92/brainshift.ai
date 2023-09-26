<template>
    <header class="site-header">
        <div class="site-header__inner">
            <Link href="/" class="site-logo">
                <img src="../../images/logo.svg" :alt="$t('logoAlt')" />
            </Link>
            <button
                class="site-nav-toggle button-reset"
                :class="{ 'is-active': navActive }"
                @click="toggleNav()"
                type="button"
            >
                <span></span>
            </button>
        </div>
        <nav class="site-nav" :class="{ 'is-active': navActive }">
            <div class="site-nav__inner">
                <ul class="menu">
                    <li class="site-nav__item">
                        <Link
                            href="/"
                            class="site-nav__link site-nav__link--bold"
                        >
                            {{ $t('Home') }}
                        </Link>
                    </li>
                    <li
                        v-if="isInstaller"
                        class="site-nav__item"
                        :class="{
                            'card--disabled': profileCompleted === false,
                        }"
                    >
                        <Link
                            href="/requests"
                            class="site-nav__link site-nav__link--bold"
                        >
                            {{ $t('Requests') }}
                            <span class="site-nav__pill pill" v-if="newRequests"
                                >{{ newRequests }} {{ $t('new') }}</span
                            >
                        </Link>
                    </li>
                    <li class="site-nav__item">
                        <Link
                            href="/farmers-library"
                            class="site-nav__link site-nav__link--bold"
                        >
                            {{ $t('Farmers Library') }}
                        </Link>
                    </li>
                    <li class="site-nav__item">
                        <a
                            @click.prevent="visitShedSolution"
                            href="/shed-solution"
                            class="site-nav__link site-nav__link--bold"
                        >
                            {{ $t('Your new agricultural building') }}
                        </a>
                    </li>
                    <li class="site-nav__item">
                        <Link
                            href="/find-an-expert"
                            class="site-nav__link site-nav__link--bold"
                        >
                            {{ $t('Find an expert') }}
                        </Link>
                    </li>
                    <li v-if="isLoggedIn" class="site-nav__item">
                        <Link href="/my-account" class="site-nav__link">
                            {{ $t('My Account') }}
                        </Link>
                    </li>
                    <li v-if="isFarmer" class="site-nav__item">
                        <Link
                            href="/my-account/shed-solutions"
                            class="site-nav__link"
                        >
                            {{ $t('Saved Projects') }}
                        </Link>
                    </li>
                    <li class="site-nav__item">
                        <a
                            :href="contactUrl"
                            target="_blank"
                            class="site-nav__link"
                        >
                            {{ $t('Contact') }} {{ $t('app.company') }}
                        </a>
                    </li>
                </ul>
                <div class="site-nav__buttons">
                    <div v-if="!isLoggedIn">
                        <a href="/login" class="button button--fill">
                            {{ $t('Sign in') }}
                        </a>
                    </div>
                    <div v-if="!isLoggedIn">
                        <a href="/login" class="button">
                            {{ $t('Sign up now') }}
                        </a>
                    </div>

                    <div v-if="isLoggedIn">
                        <a href="/logout" class="button button--clear">
                            {{ $t('Sign Out') }}
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <LoginAlert :active="showLoginPrompt" @activeState="hideLoginPrompt" />
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { usePage } from '@inertiajs/inertia-vue3';
import LoginAlert from '../Shared/LoginAlert.vue';

export default {
    components: {
        Link,
        LoginAlert,
    },
    data: function () {
        return {
            navActive: false,
            contactUrl: usePage().props.value.company.contact.url,
            user: usePage().props.value.user,
            newRequests: usePage().props.value.installer.new_request_count,
            profileCompleted: usePage().props.value.installer.profile_complete,
            showLoginPrompt: false,
        };
    },
    computed: {
        isLoggedIn: function () {
            return this.user !== null;
        },
        isInstaller: function () {
            return this.isLoggedIn && this.user.roles.includes('installer');
        },
        isFarmer: function () {
            return this.isLoggedIn && this.user.roles.includes('farmer');
        },
    },
    methods: {
        toggleNav() {
            this.navActive = !this.navActive;
        },
        hideLoginPrompt() {
            this.showLoginPrompt = false;
        },
        visitShedSolution() {
            if (this.user) {
                this.$inertia.visit('/shed-solution');
            } else {
                this.showLoginPrompt = true;
            }
        },
    },
};
</script>
