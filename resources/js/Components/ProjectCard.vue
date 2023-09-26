<template>
    <div
        class="card card--project project-card"
        :class="{ 'is-selected': project == selected }"
    >
        <div class="project-card__image">
            <img
                v-if="project.project_file_id"
                :src="project.project_file.url"
            />
        </div>
        <div class="project-card__content">
            <p class="project-card__title">
                {{ project.title }}
            </p>
            <button
                v-if="target == 'return'"
                type="button"
                class="button button--thin button--full"
                @click="returnProject(project)"
            >
                {{ project == selected ? 'Unselect' : 'Select' }}
            </button>
            <div class="project-card__buttons" v-if="target == 'view'">
                <button
                    @click="this.$emit('projectSelected', project)"
                    type="button"
                    class="button button--thin"
                >
                    {{ $t('Send') }}
                </button>
                <Link
                    :href="`/my-account/shed-solutions/${project.id}/view`"
                    class="button button--thin button--fill"
                >
                    {{ $t('Open') }}
                </Link>
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';

export default {
    emits: ['returnClick', 'projectSelected'],
    components: {
        Link,
    },
    props: {
        project: Object,
        selected: Object,
        target: String,
    },
    methods: {
        returnProject(project) {
            this.$emit('returnClick', project);
        },
    },
};
</script>
