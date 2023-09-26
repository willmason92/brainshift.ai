<template>
    <div class="shed-solution-section">
        <h2 class="h4 shed-solution-mb-1">
            {{ $t('Your new agricultural building') }}
        </h2>
        <h1 class="h2 shed-solutions-mb-small">
            {{ $t("Choose the sector you're in") }}
        </h1>

        <p class="error-message" v-if="Object.values(this.errors)[0]">
            {{ Object.values(errors)[0] }}
        </p>

        <SelectSingle
            inputName="sector"
            :options="sectors"
            overrideText="name"
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
import { Link } from '@inertiajs/inertia-vue3';
import SelectSingle from '../../../../Shared/Inputs/SelectSingle.vue';
import ContinueButton from '../../../../Shared/Inputs/ContinueButton.vue';

export default {
    emits: ['valueChanged'],
    updated() {
        this.sectors.forEach((sector, i) => {
            sector.image = this.sectors[i]['image'] =
                require(`../../../../../images/sector-icons/icon-${sector.name.toLowerCase()}.svg`).default;
        });
    },
    components: {
        Link,
        SelectSingle,
        ContinueButton,
    },
    props: {
        sectors: Array,
        errors: Object,
    },
    data() {
        return {
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
