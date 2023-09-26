<template>
    <div class="request-status">
        <button
            type="button"
            class="request-status__update button button--fill button--span"
            @click="alertActive = true"
        >
            {{ $t('Update project status') }}
        </button>
    </div>

    <Popup
        :active="alertActive"
        inputName="status"
        wrapperClass="request-status__box"
        @active-state="closePopup"
    >
        <h2 class="request-status__title h2">
            {{ $t('What is the current status of this request?') }}
        </h2>

        <p class="error-message" v-if="error">
            {{ error }}
        </p>

        <div class="select-options input-element" :class="wrapperClass">
            <label
                class="select-option"
                v-for="option in options"
                :class="isSelected(option.id)"
                v-show="showOption(option.id)"
            >
                <div class="select-option__pre">
                    <span
                        :class="`request-status__dot dot--${option.id}`"
                    ></span>
                </div>

                <input
                    @click="valueChanged(option.id)"
                    type="radio"
                    :value="option.id"
                />
                <span class="select-option__label h5"> {{ option.text }} </span>
                <div class="select-option__select"></div>
                <br />
            </label>
        </div>

        <ContinueButton
            :text="$t('Okay')"
            :isEnabled="true"
            @click="submit"
        ></ContinueButton>
    </Popup>
</template>

<script>
import AlertBox from '../../Shared/AlertBox.vue';
import SelectSingle from '../../Shared/Inputs/SelectSingle.vue';
import ContinueButton from '../../Shared/Inputs/ContinueButton.vue';
import Popup from '../../Shared/Popup.vue';

export default {
    watch: {
        error() {
            if (this.error) {
                this.alertActive = true;
            }
        },
    },
    components: {
        AlertBox,
        SelectSingle,
        ContinueButton,
        Popup,
    },
    props: {
        currentStatus: Number,
        error: String,
    },
    data() {
        return {
            alertActive: false,
            selectedOption: this.$props.currentStatus,
            options: [
                {
                    id: 0,
                    text: this.$t('New request'),
                },
                {
                    id: 1,
                    text: this.$t('Contacted'),
                },
                {
                    id: 2,
                    text: this.$t('Converted'),
                },
                {
                    id: 3,
                    text: this.$t('Project finished'),
                },
                {
                    id: 4,
                    text: this.$t('Not converted'),
                },
                {
                    id: 5,
                    text: this.$t('Project failed'),
                },
            ],
            optionTree: [[1, 4], [2, 4], [3, 5], [3], [4], [5]],
        };
    },
    methods: {
        closePopup() {
            this.alertActive = false;
        },
        valueChanged(status) {
            this.selectedOption = status;
            this.$emit('valueChanged', {
                input: this.inputName,
                value: this.selectedOption,
            });
        },
        isSelected(id) {
            return this.selectedOption === id ? 'active' : '';
        },
        showOption(id) {
            let availableOptions = this.optionTree[this.currentStatus];
            return availableOptions.includes(id);
        },
        submit() {
            this.$emit('submitStatusUpdate');
            this.closePopup();
        },
    },
};
</script>
