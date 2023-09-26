<template>
    <div class="request-summary">
        <table class="request-summary__table">
            <tbody>
                <tr v-if="request?.location">
                    <td class="request-summary-key f-title-1">
                        {{ $t('Project location') }}
                    </td>
                    <td class="request-summary-value f-body-2">
                        {{ request.location }}
                    </td>
                </tr>
                <tr v-if="this.request?.type">
                    <td class="request-summary-key f-title-1">
                        {{ $t('Type of farm') }}
                    </td>
                    <td class="request-summary-value f-body-2">
                        {{ request.type }}
                    </td>
                </tr>
                <tr v-if="request?.location">
                    <td class="request-summary-key f-title-1">
                        {{ $t('Configuration') }}
                    </td>
                    <td class="request-summary-value f-body-2">
                        {{ request.configuration }}
                    </td>
                </tr>
                <tr v-if="request?.goals">
                    <td class="request-summary-key f-title-1">
                        {{ $t('Project goals') }}
                    </td>
                    <td class="request-summary-value f-body-2">
                        <p v-for="goal in request.goals">{{ goal }}</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    watch: {
        request() {
            console.log('summary is ', this.request);
        },
    },
    props: {
        request: Object,
    },
    data() {
        return {};
    },
};
</script>
