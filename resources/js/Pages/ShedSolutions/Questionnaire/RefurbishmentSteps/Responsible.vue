<template>
    <div class="shed-solution-section">
        <h2 class="h4 shed-solution-mb-1">
            {{ $t('Your new agricultural building') }}
        </h2>
        <h1 class="h2 shed-solutions-mb-small">
            {{ $t('Who’s going to be responsible for the installation?') }}
        </h1>

        <p class="error-message" v-if="Object.values(this.errors)[0]">
            {{ Object.values(errors)[0] }}
        </p>

        <SelectSingle
            inputName="responsibility"
            :options="responsibilities"
            @valueChanged="valueChanged"
        ></SelectSingle>

        <ContinueButton
            :text="$t('Continue')"
            :isEnabled="isSubmissionAllowed"
            :isFixed="true"
            @continue="this.$emit('continue')"
        />
    </div>
</template>

<script>
import SelectSingle from '../../../../Shared/Inputs/SelectSingle.vue';
import ContinueButton from '../../../../Shared/Inputs/ContinueButton.vue';

export default {
    emits: ['valueChanged'],
    props: {
        errors: Object,
    },
    components: {
        SelectSingle,
        ContinueButton,
    },
    data() {
        return {
            responsibilities: [
                { id: 0, name: this.$t('An installer') },
                { id: 1, name: this.$t("I'll do it myself") },
                { id: 2, name: this.$t("I don't know yet") },
            ],
            isSubmissionAllowed: false,
        };
    },
    methods: {
        valueChanged(change) {
            this.$emit('valueChanged', change);
            if (change.value != null) {
                this.isSubmissionAllowed = true;
            } else {
                this.isSubmissionAllowed = false;
            }
        },
    },
    computed: {},
};
</script>
