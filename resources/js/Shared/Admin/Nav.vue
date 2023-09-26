<template>
    <div>
        <header class="admin-header">
            <a href="/admin" class="site-logo">
                <img src="../../../images/logo.svg" />
            </a>
            <div class="admin-header__profile">
                <button
                    type="button"
                    class="admin-header__profile-button button-reset"
                >
                    {{ username || 'Guest' }}
                </button>
                <div class="admin-header__profile-menu" v-if="isLoggedIn">
                    <ul>
                        <!--<li class="admin-header__profile-item">
                            <a href="/logout" class="admin-header__profile-link">
                                {{ $t('My account') }}
                            </a>
                        </li>-->
                        <li
                            class="admin-header__profile-item"
                            v-if="isLoggedIn"
                        >
                            <a
                                href="/logout"
                                class="admin-header__profile-link"
                            >
                                {{ $t('Sign Out') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
        <nav class="admin-nav">
            <ul>
                <li
                    class="admin-nav__item"
                    v-if="hasRole('admin', 'superadmin')"
                >
                    <Link href="/admin" class="admin-nav__link">
                        {{ $t('Dashboard') }}
                    </Link>
                </li>
                <li
                    class="admin-nav__item"
                    v-if="hasRole('admin', 'superadmin')"
                >
                    <Link href="/admin/manage-farmers" class="admin-nav__link">
                        {{ $t('Manage Farmers') }}
                    </Link>
                </li>
                <li
                    class="admin-nav__item"
                    v-if="hasRole('admin', 'superadmin')"
                >
                    <Link
                        href="/admin/manage-installers"
                        class="admin-nav__link"
                    >
                        {{ $t('Manage Installers') }}
                    </Link>
                </li>
                <li class="admin-nav__item" v-if="hasRole('superadmin')">
                    <Link href="/admin/manage-experts" class="admin-nav__link">
                        {{ $t('Manage Experts') }}
                    </Link>
                </li>
                <li class="admin-nav__item" v-if="hasRole('superadmin')">
                    <Link href="/admin/manage-admins" class="admin-nav__link">
                        {{ $t('Manage Admins') }}
                    </Link>
                </li>
                <li
                    class="admin-nav__item"
                    v-if="hasRole('admin', 'superadmin')"
                >
                    <Link
                        href="/admin/installer-projects"
                        class="admin-nav__link"
                    >
                        {{ $t('Installer Projects') }}
                    </Link>
                </li>
                <li
                    class="admin-nav__item"
                    v-if="hasRole('admin', 'superadmin')"
                >
                    <Link href="/admin/requests" class="admin-nav__link">
                        {{ $t('Installer Requests') }}
                    </Link>
                </li>
                <li
                    class="admin-nav__item"
                    v-if="hasRole('admin', 'superadmin')"
                >
                    <Link href="/admin/manage-blog" class="admin-nav__link">
                        {{ $t('Manage Blog') }}
                    </Link>
                </li>
                <li class="admin-nav__item" v-if="hasRole('superadmin')">
                    <Link
                        href="/admin/manage-materials"
                        class="admin-nav__link"
                    >
                        {{ $t('Materials') }}
                    </Link>
                </li>
                <li class="admin-nav__item" v-if="hasRole('superadmin')">
                    <Link href="/admin/manage-regions/" class="admin-nav__link">
                        {{ $t('Regions') }}
                    </Link>
                </li>
                <li class="admin-nav__item" v-if="hasRole('superadmin')">
                    <Link
                        href="/admin/manage-static-pages/"
                        class="admin-nav__link"
                    >
                        {{ $t('Static Pages') }}
                    </Link>
                </li>
                <li class="admin-nav__item" v-if="hasRole('superadmin')">
                    <Link href="/admin/settings/" class="admin-nav__link">
                        {{ $t('Settings') }}
                    </Link>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { usePage } from '@inertiajs/inertia-vue3';
import useUserRole from '../../Composables/useUserRole';

export default {
    name: 'Nav',
    components: {
        Link,
    },
    setup() {
        const { hasRole } = useUserRole();
        return { hasRole };
    },
    data() {
        return {
            user: usePage().props?.value?.user,
        };
    },
    computed: {
        username() {
            return usePage().props?.value?.user?.name;
        },
        isLoggedIn: function () {
            return usePage().props?.value?.user !== null;
        },
    },
};
</script>
