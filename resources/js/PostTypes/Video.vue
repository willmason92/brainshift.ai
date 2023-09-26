<template>
    <div>
        <div class="video" :class="{'is-active': isPlayed }">
            <YouTube class="video__player" :src="post.youtube" ref="youtube" />
            <button @click="onPlay" class="video__button button-reset" v-if="video">
                <img class="video__poster" :src="post.youtube_poster" alt="">
                {{ $t('Play') }}
            </button>
        </div>
    </div>
</template>

<script>
    import YouTube from 'vue3-youtube'
    export default {
        components: {
            YouTube,
        },
        data() {
            return {
                isPlayed: false
            }
        },
        props: {
            post: Object,
            video: Array,
        },
        methods: {
            onPlay() {
                this.isPlayed = true
                this.$refs.youtube.playVideo()
            }
        }
    }
</script>
