<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>
    <div
        class="tag-input input-element"
        :class="{ empty: !this.tags?.length }"
        @click="this.$refs.input.focus()"
    >
        <span class="tag-item" v-for="tag in tags">
            <span class="tag-item__text">{{ tag.text }}</span>
            <div class="tag-item__delete" @click="removeTag(tag)">
                <svg
                    width="12"
                    height="13"
                    viewBox="0 0 12 13"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M7.29994 6.49998L11.7799 1.99998C12.0199 1.75998 12.0199 1.37998 11.7799 1.15998C11.5399 0.91998 11.1599 0.91998 10.9399 1.15998L6.45994 5.65998L1.95994 1.15998C1.71994 0.91998 1.33994 0.91998 1.11994 1.15998C0.879941 1.39998 0.879941 1.77998 1.11994 1.99998L5.59994 6.49998L1.11994 11C0.879941 11.24 0.879941 11.62 1.11994 11.84C1.23994 11.96 1.39994 12.02 1.53994 12.02C1.67994 12.02 1.83994 11.96 1.95994 11.84L6.45994 7.33998L10.9599 11.84C11.0799 11.96 11.2399 12.02 11.3799 12.02C11.5199 12.02 11.6799 11.96 11.7999 11.84C12.0399 11.6 12.0399 11.22 11.7999 11L7.29994 6.49998Z"
                        fill="#010101"
                    />
                </svg>
            </div>
        </span>
        <div class="new-tag">
            <span v-if="tag" class="tag-input-text f-body-4">{{ tag }}</span>
            <input
                ref="input"
                class="tag-input-input f-body-4"
                type="text"
                :name="inputName"
                :placeholder="activePlaceholder"
                v-model="tag"
                @keypress.enter="addTag(tag)"
                @keydown.delete="backspaceTag"
                @input="onInput"
            />
            <div class="new-tag__suggestions" v-if="tag">
                <div
                    class="new-tag__suggestion f-body-4"
                    v-for="suggestion in suggestions"
                    @click="fillSuggestion(suggestion)"
                >
                    {{ suggestion.text }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    emits: ['valueChanged'],
    props: {
        inputName: String,
        availableTags: Array,
        limitTags: {
            default: false,
            type: Boolean,
        },
        max: Number,
        defaultTags: Array,
        placeholder: String,
        error: String,
    },
    data() {
        return {
            tags: [],
            tag: '',
            suggestions: [],
            activePlaceholder: '',
        };
    },
    mounted() {
        this.tags = this.defaultTags || [];
        if (!this.tags.length) {
            // if not filled, enable the placeholder text
            this.activePlaceholder = this.placeholder;
        }
    },
    watch: {
        tags: {
            deep: true,
            handler: function (newTags) {
                if (this.tag) {
                    this.$refs.input.classList.add('active');
                    this.activePlaceholder = '';
                } else {
                    this.$refs.input.classList.remove('active');
                }

                // if no tags, add back placeholder
                if (!this.tags.length) {
                    this.activePlaceholder = this.placeholder;
                }

                this.$emit('valueChanged', {
                    input: this.inputName,
                    value: newTags,
                });
            },
        },
    },
    methods: {
        addTag(tag) {
            if (this.max && this.tags.length >= this.max) return;

            // typed tag
            if (!tag.id) {
                // return if tag text exists already
                for (const tg of this.tags) {
                    if (tg.text == tag) {
                        return;
                    }
                }

                // if an available tag, insert that
                if (this.getAvailableTagByText(tag)) {
                    let newTag = this.getAvailableTagByText(tag);
                    if (!this.getTag(newTag.id)) {
                        this.tags.push(newTag);
                    } else {
                        return;
                    }
                } else {
                    if (!this.limitTags) {
                        // new tag, need to send to server!
                        // then need to add item with returned ID
                        this.tags.push({
                            text: tag,
                            id: -1,
                        });
                    } else {
                        return;
                    }
                }
            } else {
                // if not an existing tag, or if limited && one of the available
                if (!this.getTag(tag.id)) {
                    if (this.limitTags && !this.getAvailableTag(tag.id)) {
                        return;
                    }
                    this.tags.push(tag);
                } else {
                    this.$refs.input.classList.remove('active');
                }
            }
            this.tag = '';
        },
        removeTag(tag) {
            this.tags = this.tags.filter((tagToCheck) => tagToCheck != tag);
        },
        getTag(id) {
            return this.tags.filter((tag) => tag.id == id)[0] || null;
        },
        getAvailableTag(id) {
            return this.availableTags.filter((tag) => tag.id == id)[0] || null;
        },
        getAvailableTagByText(text) {
            return (
                this.availableTags.filter((tag) => tag.text == text)[0] || null
            );
        },
        backspaceTag(e) {
            if (!this.tag) {
                let previousTag = this.tags[this.tags.length - 1]?.text;
                if (previousTag) {
                    this.tags.pop();
                    this.tag = previousTag;
                    e.preventDefault();
                }
            }
        },
        onInput() {
            // make real input relatively positioned to show placeholder
            if (this.tag) {
                this.$refs.input.classList.add('active');
                this.activePlaceholder = '';
            } else {
                this.$refs.input.classList.remove('active');
                if (!this.tags.length) {
                    this.activePlaceholder = this.placeholder;
                }
            }

            this.suggestions = this.availableTags.filter((availableTag) => {
                return availableTag.text
                    .toLowerCase()
                    .includes(this.tag.toLowerCase());
            });
        },
        fillSuggestion(tag) {
            this.addTag(tag);
            this.tag = '';
        },
    },
};
</script>
