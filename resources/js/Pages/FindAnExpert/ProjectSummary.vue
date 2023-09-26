<template>
    <div class="project-summary card">
        <div v-if="project.cover_image" class="project-summary__image">
            <img :src="project.cover_image.url" alt="" />
        </div>

        <div class="project-summary__content">
            <div class="project-summary__tags">
                <span class="tag project-tag" v-if="project.project_published_status == 0">
                    {{ $t('Draft') }}
                </span>
                <span class="tag project-tag" v-else>
                    {{ $t('Published') }}
                </span>
                <span class="tag project-tag" v-for="tag in tags" :key="tag">
                    {{ tag }}
                </span>
            </div>
            <h3 class="project-summary__title h7">{{ project.post_title }}</h3>
        </div>

        <button
            v-if="selectable"
            class="button button--span"
            @click="selectHandler"
        >
            {{ $t(buttontext) }}
        </button>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import SelectBox from '../../Shared/Inputs/SelectBox.vue';

export default {
    components: {
        Link,
        SelectBox,
    },
    data() {
        return {
            isSelected: false,
        };
    },
    props: {
        project: Object,
        selectable: Boolean,
    },
    methods: {
        selectHandler() {
            this.isSelected = !this.isSelected;
            this.$emit('selectedProject', this.project);
        },
    },
    computed: {
        buttontext() {
            return this.isSelected ? 'Unselect' : 'Select';
        },
        tags() {
            const tags = []
            if (this.project.sector) {
                tags.push(this.project.sector.name)
            }
            if (this.project.type_string) {
                tags.push(this.project.type_string)
            }
            return tags
        }
    },
};
</script>
