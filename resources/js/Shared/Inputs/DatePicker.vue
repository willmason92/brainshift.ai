<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>
    <Datepicker
        class="date-picker input-element"
        inputClassName="date-picker__input f-body-1"
        menuClassName="date-picker__menu"
        v-model="dateValue"
        :enableTimePicker="false"
        format="dd/MM/yyyy"
        :utc="true"
    ></Datepicker>
</template>

<script>
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { ref } from 'vue';

export default {
    emits: ['valueChanged'],
    // setup(props) {
    //     const date = ref(props.defaultValue);

    //     return {
    //         date,
    //     };
    // },
    mounted() {
        if (this.defaultValue) {
            let dateString = this.defaultValue;
            let dateParts = dateString.split('/');
            let dateObject = new Date(
                +dateParts[2],
                dateParts[1] - 1,
                +dateParts[0]
            );
            this.dateValue = dateObject;
        }
    },
    components: {
        Datepicker,
    },
    props: {
        inputName: String,
        error: String,
        value: String,
        defaultValue: String,
        defaultValue: String,
    },
    data() {
        return {
            dateValue: this.value,
            dateValue: this.value,
        };
    },
    watch: {
        dateValue: {
            handler: function () {
                this.$emit('valueChanged', {
                    input: this.inputName,
                    value: this.dateValue,
                });
            },
        },
    },
};
</script>
