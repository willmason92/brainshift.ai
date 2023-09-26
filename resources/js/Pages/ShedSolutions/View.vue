<template>
    <layout-default>
        <a
            v-bind:href="'/my-account/shed-solutions'"
            class="popup__back popup__back--margin"
        >
            {{ $t('Saved projects') }}
        </a>

        <div class="project-detail">
            <div class="project-detail__header">
                <h2 class="project-detail__title h2">
                    {{ $t('Project') }}: {{ shedSolution.title }}
                </h2>
                <button
                    class="button project-detail__share"
                    type="button"
                    @click="openPopup"
                >
                    {{ $t('Share') }}
                </button>
            </div>
            <div class="project-detail__tabs">
                <span
                    class="project-detail-tab h4"
                    :class="{ active: tab == 0 }"
                    @click="setTab(0)"
                    >{{ $t('Details') }}</span
                >
                <span
                    class="project-detail-tab h4"
                    :class="{ active: tab == 1 }"
                    @click="setTab(1)"
                    >{{ $t('Shared with') }}</span
                >
            </div>
            <div class="project-detail__tab-content" v-show="tab == 0">
                <div
                    class="project-detail__image"
                    v-if="shedSolution.project_file.id"
                >
                    <img :src="shedSolution.project_file.url" />
                </div>

                <div class="card project-detail__spec">
                    <h2 class="h2 project-detail__title">
                        {{ $t('Project details') }}
                    </h2>
                    <table class="project-detail-table">
                        <tbody>
                            <tr v-if="this.shedSolution?.location">
                                <td class="project-detail-key f-title-1">
                                    {{ $t('Project location') }}
                                </td>
                                <td class="project-detail-value f-body-2">
                                    {{
                                        shedSolution.location ||
                                        'Unknown location'
                                    }}
                                </td>
                            </tr>
                            <tr v-if="this.project?.project_type">
                                <td class="project-detail-key f-title-1">
                                    {{ $t('Type of farm') }}
                                </td>
                                <td class="project-detail-value f-body-2">
                                    {{ shedSolution.project_type }}
                                </td>
                            </tr>
                            <tr v-if="this.shedSolution?.sector">
                                <td class="project-detail-key f-title-1">
                                    {{ $t('Sector') }}
                                </td>
                                <td class="project-detail-value f-body-2">
                                    {{ shedSolution.sector.name }}
                                </td>
                            </tr>
                            <tr v-if="this.project?.frame_type">
                                <td class="project-detail-key f-title-1">
                                    {{ $t('Frame type') }}
                                </td>
                                <td class="project-detail-value f-body-2">
                                    {{ getFrameType(shedSolution.frame_type) }}
                                </td>
                            </tr>
                            <tr v-if="this.shedSolution?.age">
                                <td class="project-detail-key f-title-1">
                                    {{ $t('Building age') }}
                                </td>
                                <td class="project-detail-value f-body-2">
                                    {{
                                        `${shedSolution.age} ${
                                            shedSolution.age == 1
                                                ? 'year'
                                                : 'years'
                                        }`
                                    }}
                                </td>
                            </tr>
                            <tr v-if="this.shedSolution?.width">
                                <td class="project-detail-key f-title-1">
                                    {{ $t('Building width') }}
                                </td>
                                <td class="project-detail-value f-body-2">
                                    {{
                                        shedSolution.width +
                                        ' ' +
                                        shedSolution.size_type
                                    }}
                                </td>
                            </tr>
                            <tr v-if="this.shedSolution?.length">
                                <td class="project-detail-key f-title-1">
                                    {{ $t('Building length') }}
                                </td>
                                <td class="project-detail-value f-body-2">
                                    {{
                                        shedSolution.length +
                                        ' ' +
                                        shedSolution.size_type
                                    }}
                                </td>
                            </tr>
                            <tr v-if="shedSolution?.goals.length > 0">
                                <td class="project-detail-key f-title-1">
                                    {{ $t('Project goals') }}
                                </td>
                                <td class="project-detail-value f-body-2">
                                    <p v-for="goal in shedSolution.goals">
                                        {{ goal.name }}
                                    </p>
                                </td>
                            </tr>
                            <tr v-if="shedSolution?.reasons.length > 0">
                                <td class="project-detail-key f-title-1">
                                    {{ $t('Project reasons') }}
                                </td>
                                <td class="project-detail-value f-body-2">
                                    <p v-for="reason in shedSolution.reasons">
                                        {{ reason.name }}
                                    </p>
                                </td>
                            </tr>
                            <tr v-if="shedSolution?.responsibility">
                                <td class="project-detail-key f-title-1">
                                    {{ $t('Responsibility') }}
                                </td>
                                <td class="project-detail-value f-body-2">
                                    {{ shedSolution.responsibility }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="project-detail__tab-content" v-show="tab == 1">
                <div class="project-detail__shared">
                    <div class="shared-experts" v-if="sharedExperts.length">
                        <ExpertSummary
                            v-for="expert in sharedExperts"
                            :expert="expert"
                        ></ExpertSummary>
                    </div>
                    <div class="shared-emails" v-if="sharedEmails.length">
                        <h4 class="h4 shared-emails__title">
                            {{ $t('Shared through email') }}
                        </h4>

                        <div class="shared-emails__list card">
                            <div class="shared-email__header">
                                <span class="expert-summary__name h7">{{
                                    $t('Email')
                                }}</span>
                                <span class="expert-summary__name h7">{{
                                    $t('Date shared')
                                }}</span>
                            </div>
                            <div
                                class="shared-email"
                                v-for="expert in sharedEmails"
                            >
                                <span class="expert-summary__name f-body-2">{{
                                    expert.email
                                }}</span>
                                <span class="expert-summary__name f-title-1">{{
                                    formatDate(expert.created_at)
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="project-detail__controls">
                <button
                    v-if="!shedSolution.share_metrics.length"
                    type="button"
                    class="button-reset link"
                    @click.prevent="openDeletePopup()"
                >
                    {{ $t('Delete project') }}
                </button>
                <Link
                    v-if="!shedSolution.share_metrics.length"
                    :href="`/shed-solution/${shedSolution.id}/render`"
                    class="button button--span button--fill button--thin"
                >
                    {{ $t('Edit Project') }}
                </Link>
            </div>

            <AlertBox
                v-if="deletePopup"
                :active="deletePopup"
                @active-state="closePopup"
            >
                <img
                    class="login-alert__icon"
                    src="../../../images/alert-outline.svg"
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
                    @click="deleteProject()"
                >
                    {{ $t('Delete project') }}
                </button>
            </AlertBox>
        </div>

        <div class="share-project-popup">
            <Popup :active="popupActive" @active-state="closePopup">
                <template #backButton>
                    <h4 class="h4">{{ $t('Send project') }}</h4>
                </template>
                <ShareProject
                    :filterTitle="
                        $t('Want to share a project with one of our experts?')
                    "
                    :project="shedSolution"
                    @closePopup="closePopup"
                ></ShareProject>
            </Popup>
        </div>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../Layout/Base.vue';
import ExpertSummary from '../FindAnExpert/ExpertSummary.vue';
import ArticleList from '../../Shared/ArticleList.vue';
import Popup from '../../Shared/Popup.vue';
import ShareProject from './ShareProject.vue';
import AlertBox from '../../Shared/AlertBox.vue';
import moment from 'moment';

export default {
    components: {
        Link,
        LayoutDefault,
        ExpertSummary,
        ArticleList,
        Popup,
        ShareProject,
        AlertBox,
    },
    props: {
        shedSolution: Object,
        sharedExperts: Object,
        sharedEmails: Object,
        ShareProject,
    },
    data() {
        return {
            tab: 0,
            attachedFiles: [],
            popupActive: false,
            deletePopup: false,
        };
    },
    methods: {
        projectSelected(project) {
            this.selectedProject = project;
            this.popupActive = true;
        },
        setTab(num) {
            this.tab = num;
        },
        getFrameType(id) {
            switch (id) {
                case 0:
                    return 'Wooden';
                case 1:
                    return 'Metal';
                case 2:
                    return 'Other';
            }
        },
        openPopup() {
            this.popupActive = true;
        },
        closePopup() {
            this.popupActive = false;
            this.deletePopup = false;
        },
        openDeletePopup() {
            this.deletePopup = true;
        },
        async deleteProject() {
            this.$inertia.post(
                `/my-account/shed-solutions/${this.shedSolution.id}/delete`
            );
        },
        formatDate(date) {
            return moment(date).format('DD/MM/YYYY');
        },
    },
};
</script>
