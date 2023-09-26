<template>
    <div class="shed-solution-section">
        <h2 class="h4 shed-solution-mb-1">
            {{ $t('Your new agricultural building') }}
        </h2>
        <h1 class="h2 shed-solutions-mb-small">
            {{ $t('What type of building frame do you currently have?') }}
        </h1>

        <p class="error-message" v-if="Object.values(this.errors)[0]">
            {{ Object.values(errors)[0] }}
        </p>

        <SelectSingle
            inputName="typeOfBuilding"
            :options="types"
            @valueChanged="valueChanged"
        ></SelectSingle>

        <ContinueButton
            text="Continue"
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
            types: [
                { id: 0, name: this.$t('Wooden frame') },
                { id: 1, name: this.$t('Metal frame') },
                { id: 2, name: this.$t('Other') },
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
