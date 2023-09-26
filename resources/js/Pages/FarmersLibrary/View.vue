<template>
    <layout-default>
        <div class="article-detail">
            <Link
                :href="backLink"
                class="popup__back popup__back--margin button-reset"
                type="button"
            >
                {{ $t('Farmers Library') }}
            </Link>
            <h2 class="h2 article-detail__title">{{ post.post_title }}</h2>

            <Gallery :post="post" />
            <CaseStudy v-if="post.post_type.id === 3" :post="post" />

            <div
                v-if="post.post_content && post.post_type !== 2"
                class="rich-text rich-text--first-large"
                v-html="post.post_content"
            ></div>
            <Video v-if="post.post_type.id === 5" :post="post" />
            <Document v-if="post.post_type.id === 4" :post="post" />
            <ExternalLink
                v-if="post.post_type.id === 6"
                :url="post.social_media_link"
            />
            <ExternalLink
                v-if="post.post_type.id === 7"
                :url="post.media_partner"
            />
            <div v-if="relatedPosts" class="article-detail__suggestions">
                <h2 class="suggested-article-title">
                    {{ $t('Other interesting reads') }}
                </h2>
                <ArticleList :posts="relatedPosts"></ArticleList>
            </div>
        </div>
    </layout-default>
</template>

<script>
import LayoutDefault from '../../Layout/Base.vue';
import { Link } from '@inertiajs/inertia-vue3';
import moment from 'moment';
import Popup from '../../Shared/Popup.vue';
import CaseStudy from '../../PostTypes/CaseStudy.vue';
import Document from '../../PostTypes/Document.vue';
import Video from '../../PostTypes/Video.vue';
import ExternalLink from '../../PostTypes/ExternalLink.vue';
import ArticleList from '../../Shared/ArticleList.vue';
import Gallery from '../../Components/Gallery.vue';

export default {
    components: {
        LayoutDefault,
        Link,
        Popup,
        CaseStudy,
        Document,
        Video,
        ExternalLink,
        ArticleList,
        Gallery,
    },
    data() {
        return {
            selectedItems: [],
            moment: moment,
            visibleLightbox: false,
            indexRef: 1,
        };
    },
    props: {
        post: Object,
        relatedPosts: Array,
    },
    methods: {
        showImage(index) {
            this.indexRef = index;
            this.visibleLightbox = true;
        },
        onHide() {
            this.visibleLightbox = false;
        },
        goBack() {
            window.history.back();
        },
    },
    computed: {
        backLink () {
            return localStorage.farmersUrl ? localStorage.farmersUrl : '/farmers-library'
        }
    }
};
</script>
