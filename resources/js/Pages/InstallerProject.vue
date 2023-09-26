<template>
  <layout-default>
    <div class="article-detail">
        <div class="title-with-button">
            <button
                class="popup__back popup__back--margin button-reset"
                type="button"
                @click="goBack()"
            >
                {{ $t('Back') }}
            </button>
        </div>
        <h2 class="h2 article-detail__title">{{ project.post_title }}</h2>
        <Gallery :post="project" />
        <CaseStudy :post="project" />
        <div
            v-if="project.post_content"
            class="rich-text rich-text--first-large"
            v-html="project.post_content"
        ></div>
    </div>
  </layout-default>
</template>

<script>

import { Link } from '@inertiajs/inertia-vue3'
import LayoutDefault from '../Layout/Base.vue'
import Gallery from '../Components/Gallery.vue'
import CaseStudy from '../PostTypes/CaseStudy.vue'

export default {
    components: {
        LayoutDefault,
        Link,
        Gallery,
        CaseStudy,
    },
    props: {
        project: Object,
        admin: Boolean,
        errors: Object,
    },
    methods: {
        goBack() {
            window.history.back()
        },
    }
};
</script>
