<template>
    <div class="blog-post" v-for="(post, index) of posts" :key="index">
        <article class="article-item">
            <Link
                v-bind:href="'/farmers-library/' + post.id"
                class="article-item__link"
            >
                <div class="article-item__image" :class="{'is-contain': isContained(post) }">
                    <img :src="post.cover_image.url" :alt="post.post_title" />
                </div>
                <div class="article-item__content">
                    <div class="article-item__keywords" v-if="post.post_tags">
                        <div
                            v-for="tag in post.post_tags"
                            :key="tag.id"
                            class="article-item__keyword"
                        >
                            {{ tag.name }}
                        </div>
                    </div>
                    <h3 class="article-item__title">
                        {{ post.post_title }}
                    </h3>
                    <div class="article-item__date">
                        {{ moment(post.created_at).format('DD/MM/YYYY') }}
                    </div>
                </div>
            </Link>
        </article>
        <slot v-if="posts.length == 1 && index == 0" name="cta"></slot>
        <slot v-else-if="posts.length > 7 && index == 6" name="cta"></slot>
        <slot
            v-else-if="posts.length <= 7 && index == posts.length - 2"
            name="cta"
        ></slot>
    </div>
</template>

<script>
import moment from 'moment';
import { Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
        Link,
    },
    props: {
        posts: Array,
    },
    data() {
        return {
            keywords: '',
            moment: moment,
        };
    },
    methods: {
        isContained(post) {
            if (
                post.post_type.id == '4' ||
                post.post_type.id == '6' ||
                post.post_type.id == '7'
            ) {
                return true
            }
        }
    }
};
</script>
