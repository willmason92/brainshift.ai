<template>
    <div class="wrapper">
        <p class="h4 m-bot--1">{{ $t(`Contact ${expertType}`) }}</p>
        <h1 class="h2">{{ $t(`Attach a project`) }}</h1>
        <p class="m-top--tiny m-bot--3">
            {{
                $t(`Choose one of your projects to send to the ${expertType}.`)
            }}
        </p>

        <ProjectCard
            v-for="project in projects"
            :project="project"
            :selected="selectedProject"
            :key="project.id"
            target="return"
            @returnClick="setProject"
        ></ProjectCard>

        <CreateProjectCard
            :noProjects="projects?.length == 0"
        ></CreateProjectCard>

        <ContinueButton
            :isFixed="true"
            :isEnabled="this.selectedProject ? true : false"
            :text="$t('Continue')"
            @continue="continueToContact"
        />
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import ProjectSummary from '..//ProjectSummary.vue';
import ContinueButton from '../../../Shared/Inputs/ContinueButton.vue';
import ProjectCard from '../../../Components/ProjectCard.vue';
import CreateProjectCard from '../../../Components/CreateProjectCard.vue';

export default {
    emits: ['continueToContact', 'closePopup'],
    components: {
        Link,
        ProjectSummary,
        ContinueButton,
        ProjectCard,
        CreateProjectCard,
    },
    props: {
        expert: Object,
        projects: Array,
    },
    data() {
        return {
            selectedProject: [],
        };
    },
    methods: {
        closePopup() {
            this.$emit('closePopup');
        },
        continueToContact() {
            this.$emit('continueToContact', this.selectedProject);
        },
        setProject(project) {
            if (this.selectedProject == project) {
                this.selectedProject = null;
            } else {
                // @todo needs testing
                this.selectedProject = project;
            }
        },
    },
    computed: {
        expertType() {
            return this.expert.expert_type.name || '';
        },
    },
};
</script>
