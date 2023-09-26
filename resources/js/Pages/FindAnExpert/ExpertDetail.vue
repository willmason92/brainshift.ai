<template>
    <layout-default>
        <div class="expert-navigation">
            <a
                href="#"
                class="expert-navigation-control button button--small button--no-bg"
                @click="openPopup('showContentFilters')"
            >
                <span class="expert-navigation-arrow"> </span>
                <span>{{ $t('Experts') }}</span>
            </a>
        </div>

        <div class="expert-detail">
            <div class="expert-row expert-detail__title">
                <div class="expert-detail__image">
                    <img :src="expert.image" />
                </div>
                <div class="expert-detail__name">
                    <h1 class="expert-name h2">{{ expert.name }}</h1>
                    <p class="expert-tagline">{{ expert.expert_type.name }}</p>
                </div>
            </div>

            <div class="expert-column expert-detail__summary">
                <h3 class="expert-title h3">Summary</h3>

                <h3 class="summary-subtitle f-title-2">Expertise</h3>
                <div class="expert-detail__tags">
                    <span
                        class="tag expert-tag"
                        v-for="tag in expert.specialities"
                    >
                        {{ tag.name }}
                    </span>
                </div>
                <h3 class="summary-subtitle f-title-2">Location</h3>
                <div class="expert-detail__location">
                    <span class="location-icon">
                        <svg
                            width="17"
                            height="20"
                            viewBox="0 0 17 20"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M14.5318 2.24753C11.4076 -0.749177 6.34236 -0.749177 3.21819 2.24753C0.341753 5.0066 0.0818875 9.39694 2.61426 12.4506L8.875 20L15.1357 12.4506C17.6681 9.39694 17.4082 5.00661 14.5318 2.24753ZM8.875 10C10.3477 10 11.5417 8.80612 11.5417 7.33331C11.5417 5.86057 10.3477 4.66667 8.875 4.66667C7.40225 4.66667 6.20833 5.86057 6.20833 7.33331C6.20833 8.80612 7.40225 10 8.875 10Z"
                                fill="#E60000"
                            />
                        </svg>
                    </span>
                    <div class="expert-detail-location">
                        <p>{{ expert.location.line1 }}</p>
                        <p>{{ expert.location.line2 }}</p>
                    </div>
                </div>
            </div>

            <div class="expert-column expert-detail__about">
                <h2 class="expert-title">About</h2>

                <p>{{ expert.about }}</p>
            </div>

            <div
                class="expert-column expert-detail__projects"
                v-if="expert.project"
            >
                <h2 class="expert-title">Projects</h2>
                <div class="expert-projects">
                    <ProjectSummary
                        v-for="project in projects"
                        :project="project"
                    ></ProjectSummary>
                </div>
            </div>
        </div>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../Layout/Base.vue';
import ProjectSummary from '../FindAnExpert/ProjectSummary.vue';

export default {
    components: {
        LayoutDefault,
        Link,
        ProjectSummary,
    },
    props: {
        expert: Object,
    },
    data() {
        return {
            projects: [
                {
                    id: 0,
                    title: 'Project title here',
                    image: '',
                    tags: ['tag 1', 'tag 2', 'tag 3'],
                },
            ],
        };
    },
};
</script>
