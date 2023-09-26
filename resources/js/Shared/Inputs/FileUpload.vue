<template>
    <p
        class="error-message"
        v-if="error"
        v-for="(errorMessage, key) in error"
        :key="key"
    >
        {{ errorMessage }}
    </p>
    <div
        class="file-upload-wrapper input-element"
        :class="{ rounded: rounded, small: small }"
    >
        <div class="file-upload__images">
            <label
                @submit.prevent="submit"
                class="file-upload__input"
                v-if="loadedFiles.length < limit"
            >
                <div class="file-upload__inner">
                    <svg
                        width="22"
                        height="22"
                        viewBox="0 0 22 22"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M11.418 0.0830078C10.5895 0.0830078 9.91797 0.754595 9.91797 1.58301V9.08301H2.41797C1.58956 9.08301 0.917969 9.75458 0.917969 10.583C0.917969 11.4115 1.58956 12.083 2.41797 12.083H9.91797V19.583C9.91797 20.4114 10.5895 21.083 11.418 21.083C12.2464 21.083 12.918 20.4114 12.918 19.583V12.083H20.418C21.2464 12.083 21.918 11.4115 21.918 10.583C21.918 9.75458 21.2464 9.08301 20.418 9.08301H12.918V1.58301C12.918 0.754595 12.2464 0.0830078 11.418 0.0830078Z"
                            fill="white"
                        />
                    </svg>
                </div>
                <input
                    type="file"
                    :accept="accept"
                    @change="submit"
                    @input="form[this.inputName] = $event.target.files[0]"
                />
            </label>
            <div
                class="file-upload__image"
                v-for="file in loadedFiles"
                :key="file"
            >
                <div
                    class="file-upload__doc"
                    v-if="accept && accept.includes('doc/*')"
                >
                    <svg
                        class="doc-icon"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                    >
                        <path
                            fill="#444"
                            d="M6,2A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2H6M6,4H13V9H18V20H6V4M8,12V14H16V12H8M8,16V18H13V16H8Z"
                        />
                    </svg>

                    <p class="file-upload__filename" :title="file.filename">
                        {{ file.filename.substring(0, 12)
                        }}{{ file.filename.length > 12 ? '....' : '.'
                        }}{{ getFileExtension(file.filename) }}
                    </p>
                </div>

                <img
                    v-else
                    class="file-upload__image-img"
                    :src="file?.url ? file.url : file?.uri"
                />

                <span
                    class="file-upload__image-del"
                    @click.prevent="deleteImage(file.storage)"
                >
                    <svg
                        width="24"
                        height="24"
                        viewBox="0 0 32 32"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M12.7762 6.5529H19.0777C20.1187 6.5529 20.1198 4.97754 19.0777 4.97754H12.7762C11.7354 4.97754 11.7341 6.5529 12.7762 6.5529ZM6.85149 7.73444H24.9991C26.5495 7.73444 26.5524 10.0975 24.9991 10.0975H23.5117L23.507 25.0503C23.5067 26.1394 22.6237 27.0221 21.5346 27.0221H10.316C9.22687 27.0221 8.34388 26.1391 8.34388 25.05V10.0977H6.85149C5.30082 10.0977 5.29819 7.73444 6.85149 7.73444ZM13.0369 23.5618C13.4722 23.5618 13.8246 23.2092 13.8246 22.7741V13.1721C13.8246 12.737 13.4722 12.3844 13.0369 12.3844C12.6016 12.3844 12.2492 12.737 12.2492 13.1721V22.7741C12.2492 23.2092 12.6016 23.5618 13.0369 23.5618ZM18.8137 23.5618C19.249 23.5618 19.6014 23.2092 19.6014 22.7741V13.1721C19.6014 12.737 19.249 12.3844 18.8137 12.3844C18.3784 12.3844 18.026 12.737 18.026 13.1721V22.7741C18.026 23.2092 18.3784 23.5618 18.8137 23.5618Z"
                            fill="white"
                        />
                    </svg>
                </span>
            </div>
        </div>

        <progress
            v-if="form.progress"
            class="file-upload__progress"
            :value="form.progress.percentage"
            max="100"
        >
            {{ form.progress.percentage }}%
        </progress>
    </div>
</template>

<script>
import { useForm, usePage } from '@inertiajs/inertia-vue3';

export default {
    emits: ['returnValue'],
    props: {
        inputName: String,
        files: [Array, Object],
        parentForm: Object,
        limit: Number,
        rounded: Boolean,
        to: String,
        error: Array,
        accept: String,
        small: Boolean,
    },
    data(props) {
        let ids = [],
            uris = [];
        if (this.files) {
            if (this.limit > 1) {
                this.files.forEach(function (val) {
                    val.storage = val.path + '/' + val.filename;
                    ids.push(val.storage);
                    uris.push(val);
                });
            } else {
                let val = this.files.pop();
                val.storage = val.path + '/' + val.filename;
                ids.push(val.storage);
                uris.push(val);
            }
        }
        return {
            loadedFiles: uris,
            filesIds: ids,
            errors: {},
        };
    },
    setup(props) {
        const form = useForm({
            [props.inputName]: null,
        });
        return { form };
    },
    watch: {
        '$page.props.fileUploads': {
            handler() {
                let upload = usePage().props.value.fileUploads[this.inputName];
                if (upload && upload.storage) {
                    this.loadedFiles.push(upload);
                    this.filesIds.push(upload.storage);
                    this.$emit('returnValue', {
                        input: this.inputName,
                        value:
                            this.limit > 1 ? this.filesIds : this.filesIds[0],
                    });
                }
            },
        },
    },
    methods: {
        submit() {
          console.log(this.to);
            this.form.post(this.to, { preserveScroll: true });
        },
        deleteImage(uri) {
            this.loadedFiles = this.loadedFiles.filter(
                (file) => file.storage !== uri
            );
            this.filesIds = this.filesIds.filter((a) => a !== uri);
            this.$emit('returnValue', {
                input: this.inputName,
                value: this.limit > 1 ? this.filesIds : this.filesIds[0],
            });
        },
        getFileExtension(filename) {
            const extRegex = /(?:\.([^.]+))?$/;
            return extRegex.exec(filename)[1];
        },
    },
};
</script>
