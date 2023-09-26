<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>
    <div>
        <input
            name="check-1"
            type="checkbox"
            v-model="value1"
            value="check-1-value"
        />
        <input
            name="check-2"
            type="checkbox"
            v-model="value2"
            value="check-2-value"
        />
        <input name="check-3" type="text" v-model="value3" />

        <button @click="returnData()">Submit</button>
    </div>
</template>

<script>
export default {
    emits: ['returnData'],
    props: {
        error: String,
    },
    data() {
        return {
            data: [],
            value1: false,
            value2: false,
            value3: null,
        };
    },
    methods: {
        returnData() {
            this.data['value1'] = this.value1;
            this.data['value2'] = this.value2;
            this.data['value3'] = this.value3;
            this.$emit('returnData', this.data);
        },
    },
};
</script>
