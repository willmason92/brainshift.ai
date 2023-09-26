<template>
    <layout-default>
        <div class="article-detail">
            <button
                class="popup__back popup__back--margin button-reset"
                type="button"
                @click="goBack()"
            >
                {{ $t('Farmers Library') }}
            </button>
            <h2 class="article-detail__title">
                {{ post.post_title }}
            </h2>

            <CaseStudy v-if="post.post_type === 2" :post="post" :materials="materials" :gallery="gallery"/>
            <Document v-if="post.post_type === 3" :post="post" />
            <Video v-if="post.post_type === 4" :post="post" :video="video" />
            <div
                v-if="post.post_content && post.post_type !== 2"
                class="rich-text rich-text--first-large"
                v-html="post.post_content"
            ></div>
            <ExternalLink
                v-if="post.post_type === 5 || post.post_type === 6"
                :post="post"
            />

            <div v-if="relatedPosts">
                <h2>
                    {{ $t('Other interesting reads') }}
                </h2>
                <ArticleList :posts="relatedPosts"></ArticleList>
            </div>
        </div>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../Layout/Base.vue';
import CaseStudy from '../PostTypes/CaseStudy.vue';
import Document from '../PostTypes/Document.vue';
import Video from '../PostTypes/Video.vue';
import ExternalLink from '../PostTypes/ExternalLink.vue';
import ArticleList from '../Shared/ArticleList.vue';

    export default {
        components: {
            Link,
            LayoutDefault,
            CaseStudy,
            Document,
            Video,
            ExternalLink,
            ArticleList,
        },
        props: {
            post: Object,
            gallery: Array,
            materials: Array,
            video: Object,
            materials: Array,
            relatedPosts: Array,
        },
        methods: {
            goBack() {
                window.history.back()
            }
        }
    }
</script>
