<template>
    <div class="share-project">
        <h2 class="h2 share-project__title">
            {{ $t('Want to share your project with one of our experts?') }}
        </h2>

        <div class="share-project__cta cta cta--padded">
            <h2 class="h2 share-project__subtitle">
                {{ $t('Talk to an expert') }}
            </h2>
            <p class="f-body-1">
                {{
                    $t(
                        'Would you like to share your project with one of our experts?'
                    )
                }}
            </p>
            <Link
                href="/find-an-expert"
                class="button button--fill button--span button--thin"
            >
                {{ $t('Find an expert') }}
            </Link>
        </div>

        <div class="share-project__send card">
            <h2 class="h2 share-project__subtitle">
                {{ $t('Send your saved project') }}
            </h2>
            <p class="f-body-1 m-bot">{{ $t('By email') }}</p>
            <p class="f-title-1 m-bot--tiny">{{ $t('Email address') }}</p>
            <form>
                <div class="m-bot--2">
                    <p class="error-message" v-if="error">
                        {{ error }}
                    </p>
                    <EmailInput
                        inputName="email"
                        placeholder="john.smith@mail.com"
                        @valueChanged="valueChanged"
                    ></EmailInput>
                </div>
                <button
                    type="button"
                    @click="submit"
                    class="button button--span button--no-bg button--thin"
                >
                    {{ $t('Send') }}
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import EmailInput from '../../Shared/Inputs/EmailInput.vue';
import { Inertia } from '@inertiajs/inertia';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
    setup(props) {
        const formValues = useForm({
            email: null,
            projectId: null,
        });
        return { formValues };
    },
    components: {
        Link,
        EmailInput,
    },
    props: {
        project: Object,
    },
    data() {
        return {
            error: null,
        };
    },
    methods: {
        valueChanged(change) {
            this.formValues.email = change.value;
        },
        submit() {
            // submit the form here
            this.formValues.projectId = this.project.id;

            console.log('posting: ', this.formValues);

            Inertia.post('/my-account/shed-solutions/send', this.formValues, {
                onSuccess: () => {
                    this.$emit('closePopup');
                },
                onError: (error) => {
                    this.error = error.email;
                },
            });
        },
    },
};
</script>
