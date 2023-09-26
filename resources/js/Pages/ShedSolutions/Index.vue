<template>
    <layout-default>
        <Link href="/my-account" class="popup__back button-reset m-bot--2">
            {{ $t('My Account') }}
        </Link>

        <h2 class="article-detail__title">
            {{ $t('Saved projects') }}
        </h2>

        <div class="project-list">
            <ProjectCard
                v-for="project in shedSolutions"
                :project="project"
                target="view"
                @projectSelected="projectSelected"
            ></ProjectCard>

            <CreateProjectCard
                :noProjects="shedSolutions.length == 0"
            ></CreateProjectCard>
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
                    :project="selectedProject"
                    @closePopup="closePopup"
                ></ShareProject>
            </Popup>
        </div>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../Layout/Base.vue';
import Popup from '../../Shared/Popup.vue';
import ShareProject from './ShareProject';
import Toast from '../../Shared/Toast.vue';
import ProjectCard from '../../Components/ProjectCard.vue';
import CreateProjectCard from '../../Components/CreateProjectCard.vue';

export default {
    components: {
        ShareProject,
        Link,
        LayoutDefault,
        Popup,
        ProjectCard,
        CreateProjectCard,
        Toast,
    },
    data() {
        return {
            popupActive: false,
            selectedProject: null,
            toast: false,
            toastMessage: '',
        };
    },
    props: {
        shedSolutions: Array,
        activeType: String,
        flash: Object,
    },
    methods: {
        openPopup(target) {
            this.popupActive = true;
        },
        closePopup() {
            this.popupActive = false;
        },
        projectSelected(project) {
            this.selectedProject = project;
            this.popupActive = true;
        },
    },
};
</script>
