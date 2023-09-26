<template>
    <layout-default>
        <div class="admin-back">
            <Link
                href="/admin/manage-static-pages"
                class="popup__back button-reset"
            >
                {{ $t('Back to static page management') }}
            </Link>
            <ContinueButton
                :text="$t('Save changes')"
                :isEnabled="isLoaded"
                :isLoading="!isLoaded"
                wrapperClass="button--thin"
                @continue="savePage"
            />
        </div>

        <h1 class="admin-title">{{ pageTitle }}</h1>

        <div class="card admin-details-wrapper">
            <h2 class="admin-subtitle">{{ $t('Static page') }}</h2>

            <div class="admin-label">
                <h3 class="admin-label__text">{{ $t('Content') }}</h3>
            </div>

            <!-- wysiwyg here -->
            <WysiwygInput
                :inputName="'value'"
                :error="errors?.value"
                :value="form?.value || ''"
                @valueChanged="valueChanged"
                height="400px"
                :resize="true"
            ></WysiwygInput>
        </div>
    </layout-default>
</template>

<script>
import { Link, useForm } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../../Layout/Admin.vue';
import WysiwygInput from '../../../Shared/Inputs/WysiwygInput.vue';
import ContinueButton from '../../../Shared/Inputs/ContinueButton.vue';

export default {
    components: {
        LayoutDefault,
        Link,
        WysiwygInput,
        ContinueButton,
    },
    props: {
        data: Object,
        errors: Object,
    },
    setup(props) {
        const form = useForm({
            value: props?.data?.value,
        });
        return { form };
    },
    data() {
        return {
            isLoaded: true,
        };
    },
    methods: {
        valueChanged(change) {
            this.form.value = change.value;
        },
        savePage() {
            this.$inertia.post(
                `/admin/manage-static-pages/${this.data.id}/edit-page`,
                this.form,
                {
                    onBefore: () => {
                        this.isLoaded = false;
                    },
                    onFinish: () => {
                        this.isLoaded = true;
                    },
                }
            );
        },
    },
    computed: {
        pageTitle() {
            return (
                this.data.value.match(/(?<=\>)(?!\<)(.*)(?=\<)(?<!\>)/)?.[0] ||
                'No title'
            );
        },
    },
};
</script>
