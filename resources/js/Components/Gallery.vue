<template>
    <div v-if="post?.gallery || post?.cover_image">
        <a
            v-if="post?.post_type?.id === 6"
            :href="post.social_media_link"
            target="_blank"
            class="gallery-solo"
            :class="{ 'is-contain': isContained(post) }"
        >
            <div class="gallery__slide-image">
                <img :src="post.cover_image.url" width="300" />
            </div>
        </a>
        <carousel
            v-else-if="post.gallery.length > 1"
            class="gallery"
            :items-to-show="1.3"
            :snap-align="'start'"
        >
            <slide
                class="gallery__slide"
                v-for="(image, index) in post.gallery"
                :key="index"
            >
                <div class="gallery__slide-image" @click="showImage(index)">
                    <img :src="image.url" width="300" />
                </div>
            </slide>
        </carousel>
        <div
            v-else
            class="gallery-solo"
            :class="{ 'is-contain': isContained(post) }"
        >
            <div @click="showImage(index)">
                <div class="gallery__slide-image">
                    <img
                        :src="
                            post.gallery[0]
                                ? post.gallery[0].url
                                : post.cover_image.url
                        "
                        width="300"
                    />
                </div>
            </div>
        </div>
        <VueEasyLightbox
            :visible="visibleLightbox"
            :imgs="lightboxImages"
            :index="indexRef"
            @hide="onHide"
        ></VueEasyLightbox>
    </div>
</template>

<script>
import { Carousel, Slide } from 'vue3-carousel';
import VueEasyLightbox from 'vue-easy-lightbox';

export default {
    components: {
        Carousel,
        Slide,
        VueEasyLightbox,
    },
    props: {
        post: Object,
    },
    data() {
        return {
            lightboxImages: [],
            visibleLightbox: false,
            indexRef: 0,
        };
    },
    mounted() {
        if (this.post.gallery.length > 0) {
            this.post.gallery.forEach((image) => {
                this.lightboxImages.push(image.url);
            });
        } else {
            this.lightboxImages.push(this.post.cover_image.url);
        }
    },
    methods: {
        showImage(index) {
            this.indexRef = index;
            this.visibleLightbox = true;
        },
        onHide() {
            this.visibleLightbox = false;
        },
        isContained(post) {
            if (
                post?.post_type?.id == '4' ||
                post?.post_type?.id == '6' ||
                post?.post_type?.id == '7'
            ) {
                return true;
            }
        },
    },
};
</script>
