<template>
    <layout-default>
        <div class="farmers-filters-header">
            <Link href="/" class="popup__back button-reset">
                {{ $t('Home') }}
            </Link>
        </div>
        <div class="title-with-button title-with-button--extra-margin">
            <h2>
                {{ $t('Your profile')}}
            </h2>
            <button @click="activateEditProfile" class="button button--small button-reset">
                {{ $t('Edit') }}
            </button>
        </div>
        <div class="expert-detail">
            <ProfileIntro v-if="user.expert" :profile="user.expert"></ProfileIntro>
            <ProfileSummary v-if="user.expert"
                            :profile="user.expert"
                            :location="user.location"
                            :tags="installerTags"
            ></ProfileSummary>
        </div>
        <div class="expert-column expert-detail__projects">
            <div class="title-with-button">
                <h2 class="expert-title h3">
                    {{ $t('Projects') }}
                </h2>
                <button class="button button--fill button--small" @click="addProject()">
                    {{ $t('Add') }}
                </button>
            </div>
            <div class="expert-projects">
                <div v-for="project in projects" :key="project">
                    <button @click="activateEditProject(project)" type="button" class="full-width button-reset">
                        <ProjectSummary
                            :project="project"
                        ></ProjectSummary>
                    </button>
                </div>
            </div>
        </div>
        <Popup :active="projectPopupActive" @active-state="closePopup">
            <ProjectForm v-if="popupProject || newProject === true"
                :title="newProject ? $t('New project') : $t('Edit project')"
                :errors="errors"
                :project="popupProject"
                :newProject="newProject"
                @close-popup="closePopup"
            ></ProjectForm>
        </Popup>
        <InstallerProfileForm
            :active="editFormPopupActive"
            @closePopup="closePopup"
            @newProject="addProject"
            :user="user"
            :specialities="specialities"
            :firstTime="firstTime"
            :errors="errors"
        ></InstallerProfileForm>
    </layout-default>
</template>

<script>
import LayoutDefault from '../../Layout/Base.vue'
import { Link, usePage } from '@inertiajs/inertia-vue3'
import ProfileIntro from '../../Shared/ProfileIntro.vue'
import ProfileSummary from '../../Shared/ProfileSummary.vue'
import ProjectSummary from '../FindAnExpert/ProjectSummary.vue'
import Popup from '../../Shared/Popup.vue'
import ProjectForm from './ProjectForm.vue'
import InstallerProfileForm from '../../Shared/InstallerProfileForm.vue'

export default {
    components: {
        LayoutDefault,
        Link,
        ProfileIntro,
        ProfileSummary,
        ProjectSummary,
        Popup,
        ProjectForm,
        InstallerProfileForm,
    },
    props: {
        projects: Array,
        expert: Object,
        errors: Object,
        user: Object,
        sectors: Array,
        materials: Array,
        specialities: Array,
        fileUploads: Object,
    },
    computed: {
        installerTags () {
            return [].concat.apply(this.user.expert.specialities, this.user.expert.sectors);
        }
    },
    data() {
        return {
            projectPopupActive: false,
            popupProject: false,
            editFormPopupActive: false,
            blankProject: {
              title: '',
              tags: [],
              description: '',
              coverImage: null,
              size: {},
              images: [],
              dateComplete: null,
              includeLocation: 0,
            },
            newProject: false,
            firstTime: false,
        }
    },
    mounted() {
        if (usePage().props.value.installer.profile_complete == false) {
            this.firstTime = true
            this.editFormPopupActive = true
        }
    },
    methods: {
        activateEditProject(project) {
            this.projectPopupActive = true
            this.popupProject = project
            this.editFormPopupActive = false
        },
        activateEditProfile() {
            this.projectPopupActive = false
            this.editFormPopupActive = true
        },
        closePopup() {
            this.projectPopupActive = false
            this.popupProject = false
            this.editFormPopupActive = false
            this.newProject = false
        },
        addProject() {
            this.projectPopupActive = true
            this.popupProject = this.blankProject
            this.editFormPopupActive = false
            this.newProject = true
        }
    }
}
</script>
