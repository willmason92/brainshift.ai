<template>
    <p class="error-message" v-if="error || localError">
        {{ error || localError }}
    </p>
    <div class="video-upload">
        <label v-if="title" class="input-label">{{ title }}</label>
        <input
            class="text-input input-element"
            type="text"
            :name="inputName"
            :placeholder="placeholder"
            v-model="videoUrl"
            :maxlength="max"
            @change="updateThumbnail"
        />

        <div class="video-upload__preview" v-if="thumbnailUrl && !useCustom">
            <label class="input-label">{{ $t('Video thumbnail') }}</label>
            <img class="video-upload__thumbnail" :src="thumbnailUrl" />
        </div>
        <div class="video-upload__custom" v-if="useCustom">
            <label class="input-label">{{
                $t('Custom video thumbnail')
            }}</label>
            <FileUpload
                to="/admin/manage-blog/upload_cover"
                :limit="1"
                inputName="coverImage"
                accept="image/*"
                :error="errors?.[0]"
                :files="defaultImage ? [defaultImage] : null"
                @returnValue="valueChanged"
            />
        </div>
        <div class="video-upload__toggle" @click="toggleCustomThumbnail">
            {{ $t('Click to use ') }}
            {{ useCustom ? 'video thumbnail' : 'a custom thumbnail' }}
        </div>
    </div>
</template>

<script>
import FileUpload from '../Inputs/FileUpload.vue';

export default {
    components: {
        FileUpload,
    },
    emits: ['valueChanged'],
    props: {
        inputName: String,
        error: String,
        title: String,
        defaultUrl: String,
        defaultImage: Object,
        errors: Object,
    },
    mounted() {
        if (this.defaultUrl) {
            this.videoUrl = this.defaultUrl;
            if (this.defaultImage) {
                // if default image found, use it's image object
                this.useCustom = true;
                this.thumbnail = this.defaultImage;
            } else {
                // load up thumbnail from url
                this.updateThumbnail();
            }
        }
    },
    data() {
        return {
            localError: '',
            videoUrl: '',
            thumbnailUrl: '',
            thumbnail: '',
            useCustom: false,
        };
    },
    methods: {
        updateForm() {
            console.log('this should come through:', this.thumbnail);

            this.$emit('valueChanged', {
                input: this.inputName,
                value: {
                    url: this.videoUrl,
                    useCustom: this.useCustom,
                    thumbnail: this.thumbnail,
                },
            });
        },

        valueChanged(change) {
            console.log('video change:', change);
            this.thumbnail = change.value;

            this.updateForm();
        },

        updateThumbnail() {
            if (!this.videoUrl) {
                this.thumbnailUrl = '';
                return;
            }

            this.localError = '';
            if (
                this.videoUrl.includes('youtube.') ||
                this.videoUrl.includes('youtu.be')
            ) {
                let youtubeRegex = this.videoUrl.match(
                    /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/
                );
                if (youtubeRegex && youtubeRegex[2].length == 11) {
                    this.thumbnailUrl = `https://img.youtube.com/vi/${youtubeRegex[2]}/hqdefault.jpg`;
                } else {
                    this.localError = this.$t(
                        'Please check you have entered a valid YouTube video link.'
                    );
                }
            } else if (this.videoUrl.includes('vimeo.')) {
                fetch(`https://vimeo.com/api/oembed.json?url=${this.videoUrl}`)
                    .then((res) => res.json())
                    .then((data) => {
                        this.thumbnailUrl = data.thumbnail_url;
                    })
                    .catch((err) => {
                        this.localError = this.$t(
                            'Please check you have entered a valid Vimeo video link.'
                        );
                    });
            }

            this.updateForm();
        },

        toggleCustomThumbnail() {
            this.useCustom = !this.useCustom;
            this.updateForm();
        },
    },
    watch: {
        useCustom() {
            if (this.useCustom == true) {
                this.updateThumbnail();
            }
        },
    },
};
</script>
