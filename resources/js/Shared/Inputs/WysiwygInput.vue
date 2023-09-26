<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>
    <div
        class="wysiwyg-editor"
        :class="{ 'wysiwyg-editor--resize': resize }"
        :style="{ '--editor-height': height }"
    >
        <QuillEditor
            ref="quill"
            v-model:content="content"
            contentType="html"
            :name="inputName"
            :id="inputName"
            theme="snow"
            :modules="modules"
            :toolbar="[
                { header: [1, 2, 3, 4, 5, 6, false] },
                'bold',
                'italic',
                'underline',
                { list: 'ordered' },
                { list: 'bullet' },
                'link',
                'image',
                'clean',
            ]"
            @update:content="updateImages"
        ></QuillEditor>
    </div>
</template>

<script>
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import ImageUploader from 'quill-image-uploader';
import BlotFormatter from 'quill-blot-formatter';
import sanitizeHtml from 'sanitize-html';
import { useForm, usePage } from '@inertiajs/inertia-vue3';

export default {
    emits: ['valueChanged'],
    components: {
        QuillEditor,
    },
    props: {
        inputName: String,
        placeholder: String,
        error: String,
        value: String,
        max: Number,
        height: String,
        resize: Boolean,
        files: [Array, Object],
    },
    setup(props) {
        const form = useForm({
            image: null,
        });
        return { form };
    },
    data() {
        return {
            images: [],
            imagesToDelete: [],
            imageUrls: [],

            content: this.value,
            modules: [
                {
                    name: 'imageUploader',
                    module: ImageUploader,
                    options: {
                        upload: (file) => {
                            return new Promise((resolve, reject) => {
                                this.form.image = file;
                                // use timeout to push to top of event loop
                                setTimeout(() => {
                                    this.form.post(
                                        '/admin/manage-blog/upload-image',
                                        {
                                            preserveScroll: true,
                                            onSuccess: function (res) {
                                                resolve(
                                                    res.props.fileUploads.image
                                                        .url
                                                );
                                            },
                                        }
                                    );
                                }, 0);
                            });
                        },
                    },
                },
                {
                    name: 'blotFormatter',
                    module: BlotFormatter,
                },
            ],
        };
    },
    methods: {
        updateImages(delta) {
            // get all current images in wysiwyg
            let newImages = this.getImages(delta);

            // compare with old, to work out which have been deleted
            // assumes that all image urls are unique
            this.images
                .filter((image) => {
                    return !newImages.includes(image);
                })
                .forEach((deletedImage) => {
                    this.imagesToDelete.push(deletedImage);
                });

            // check if deleted image path used again- to account for ctrl-z
            this.imagesToDelete = this.imagesToDelete.filter(
                (image) => !newImages.includes(image)
            );

            // update old image array to new images
            this.images = newImages;
        },
        getImages(delta) {
            // takes in html content of wysiwyg editor
            // outputs list of urls
            let memoryDoc = document.createElement('div');
            memoryDoc.innerHTML = delta;

            // convert to array, filter to true img, return urls
            return Array.from(memoryDoc.querySelectorAll('img'))
                .filter((el) => {
                    // ignore if base64 placeholder
                    return !el.src.includes('data:image/png;base64');
                })
                .map((el) => el.src);
        },
    },
    watch: {
        content: {
            handler: function () {
                // basic front-end sanitize html
                const cleanContent = sanitizeHtml(this.content, {
                    allowedTags: sanitizeHtml.defaults.allowedTags.concat([
                        'img',
                    ]),
                    selfClosing: ['img', 'hr', 'br'],
                    allowedAttributes: {
                        a: ['href', 'name', 'target'],
                        img: [
                            'src',
                            'srcset',
                            'alt',
                            'title',
                            'width',
                            'height',
                            'loading',
                            'style',
                        ],
                    },
                    allowedIframeHostnames: [
                        'https://www.brainshift.ai',
                        'https://www.brainshift.ai',
                    ],
                });

                this.$emit('valueChanged', {
                    input: this.inputName,
                    value: {
                        content: cleanContent,
                        images: this.images.map((image) =>
                            image.replace(window.location.origin, '')
                        ),
                    },
                });
            },
        },
    },
};
</script>
