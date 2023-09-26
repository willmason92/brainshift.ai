<template>
    <div>
        <h1 class="h2">
            {{ $t('Would you like to attach one your project') }}
        </h1>
        <p class="f-body-1 m-bot--4 m-top--tiny">
            {{
                $t(
                    `By attaching a project the ${expertType} will have a lot of the information they need to help you.`
                )
            }}
        </p>

        <p class="info-message" v-if="loginWarning">
            {{ $t('You must be logged in to attach a project. ') }}
            <a href="/login" class="login-alert-trigger">{{
                $t('Click here to login or register')
            }}</a>
        </p>

        <SelectSingle
            :options="options"
            :inputName="isAttached"
            :error="error"
            @valueChanged="valueChanged"
            @nextPage="nextPage"
        />

        <ContinueButton
            :text="$t('Continue')"
            :isFixed="true"
            :isEnabled="
                isAttached != null && !(loginWarning && isAttached === true)
            "
            @continue="goToPath"
        />
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { usePage } from '@inertiajs/inertia-vue3';
import SelectSingle from '../../../Shared/Inputs/SelectSingle.vue';
import ContinueButton from '../../../Shared/Inputs/ContinueButton.vue';

export default {
    mounted() {
        if (usePage().props.value.user == null) {
            this.loginWarning = true;
        }
    },
    emits: ['valueChanged'],
    components: {
        Link,
        SelectSingle,
        ContinueButton,
    },
    props: {
        expert: Object,
    },
    data() {
        return {
            options: [
                {
                    id: true,
                    name: this.$t('Yes, I want to attach a project'),
                    disabled: usePage().props.value.user == null,
                },
                {
                    id: false,
                    name: this.$t("No, I don't want to attach a project"),
                },
                {
                    id: 'noProject',
                    name: this.$t(
                        "I don't have a project yet but I would like to create one"
                    ),
                },
            ],
            error: '',
            wrapperClass: 'project',
            isAttached: null,
            loginWarning: false,
        };
    },
    methods: {
        valueChanged(change) {
            this.isAttached = change.value;
        },
        goToPath() {
            if (this.isAttached == 'noProject') {
                if (usePage().props.value.user == null) {
                    this.showLoginPrompt = true;
                } else {
                    this.$inertia.visit('/shed-solution');
                }
            } else {
                this.$emit('valueChanged', {
                    input: 'isAttached',
                    value: this.isAttached,
                });
            }
        },
    },

    computed: {
        expertType() {
            return this.expert.expert_type.name || '';
        },
    },
};
</script>
