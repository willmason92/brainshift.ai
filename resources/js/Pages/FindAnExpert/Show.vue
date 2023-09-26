<template>
    <layout-default>
        <div class="expert-navigation">
            <Link
                :href="`/find-an-expert/${this.expert.expert_type_id}`"
                class="expert-navigation-control button button--small button--no-bg"
            >
                <span class="expert-navigation-arrow"> </span>
                <span>{{ $t('Experts') }}</span>
            </Link>
        </div>
        <div class="expert-detail">
            <ProfileIntro v-if="expert" :profile="expert"></ProfileIntro>
            <ProfileSummary
                v-if="expert"
                :profile="expert"
                :location="expert?.user?.location"
            ></ProfileSummary>

            <div
                class="expert-column expert-detail__projects"
                v-if="expert.installer_projects?.length"
            >
                <h2 class="expert-title">{{ $t('Projects') }}</h2>
                <div class="expert-projects">
                    <div v-for="project in projectsToShow">
                        <Link
                            :href="'/installer-project/' + project.id"
                            class="no-link"
                        >
                            <ProjectSummary
                                :project="project"
                                :selectable="false"
                            ></ProjectSummary>
                        </Link>
                    </div>
                </div>
            </div>

            <button
                class="button button--fill button--span"
                @click.prevent="contactOption(expert)"
            >
                {{ $t('Contact') }}
            </button>
        </div>

        <Contact
            v-if="contactPopupActive"
            :sectors="sectors"
            :currentProjects="userShedSolutions"
            :contactPopupActive="contactPopupActive"
            :expert="expert"
            @closePopup="closePopup"
        />
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { usePage } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../Layout/Base.vue';
import ProjectSummary from '../FindAnExpert/ProjectSummary.vue';
import ContinueButton from '../../Shared/Inputs/ContinueButton.vue';
import Contact from './Popup/Contact.vue';
import ProfileIntro from '../../Shared/ProfileIntro';
import ProfileSummary from '../../Shared/ProfileSummary';

export default {
    components: {
        LayoutDefault,
        Link,
        ProfileIntro,
        ProfileSummary,
        ProjectSummary,
        ContinueButton,
        Contact,
    },
    props: {
        expert: Object,
        projects: Array,
        sectors: Array,
        userShedSolutions: Array,
        contactRequestSent: Boolean,
    },
    mounted() {
        let params = new URLSearchParams(document.location.search);
        if (params.get('contact') && this.expert?.expert_type_id === 1) {
            this.contactPopupActive = params.get('contact');
        }
    },
    data() {
        return {
            contactPopupActive: false,
        };
    },
    methods: {
        closePopup() {
            this.contactPopupActive = false;
        },

        contactOption() {
            if (this.expert.expert_type.name === 'Installers') {
                this.contactPopupActive = true;
                return;
            }

            let url = this.expert.expert_url;
            if (!/^https|https?:\/\//i.test(url)) {
                url = 'http://' + url;
            }

            window.open(url);
        },
    },

    computed: {
        expertType() {
            return this.expert.expert_type.name || '';
        },

        expertLocationStreet() {
            return (
                this.expert?.location?.street ||
                this.expert?.user?.location?.street ||
                ''
            );
        },

        expertLocationCity() {
            return (
                this.expert?.location?.city ||
                this.expert?.user?.location?.city ||
                ''
            );
        },

        expertLocationCountry() {
            return (
                this.expert?.location?.country ||
                this.expert?.user?.location?.country ||
                ''
            );
        },

        expertLocationPostcode() {
            return (
                this.expert?.location?.postcode ||
                this.expert?.user?.location?.postcode ||
                ''
            );
        },

        hasLocation() {
            return (
                this.expert?.location || this.expert?.user?.location || false
            );
        },

        projectsToShow() {
            if (
                usePage().props.value?.user?.id &&
                usePage().props.value.user.id === this.expert.id
            ) {
                return this.expert.installer_projects;
            } else {
                return this.expert.installer_projects.filter(
                    (project) => project.project_published_status === 1
                );
            }
        },
    },

    watch: {
        contactPopupActive: {
            handler: function (newValue) {
                if (newValue) {
                    document.body.classList.add('popup-prevent-scroll');
                } else {
                    document.body.classList.remove('popup-prevent-scroll');
                }
            },
        },
    },
};
</script>
