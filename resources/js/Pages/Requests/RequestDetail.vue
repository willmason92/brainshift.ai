<template>
    <layout-default>
        <div class="farmers-filters-header">
            <Link href="/requests" class="popup__back button-reset">
                {{ $t('Requests') }}
            </Link>
        </div>
        <div class="request-detail">
            <h1 class="request-detail__title h2" v-if="project">
                {{ $t('Project') }}:
                {{ project.title || $t('Unnamed project') }}
            </h1>
            <div
                class="request-detail__status-tag"
                v-show="request.request_status != null"
            >
                <span
                    class="tag tag&#45;&#45;large tag&#45;&#45;rounded"
                    :class="requestTag"
                >
                    {{ getStatusText() }}
                </span>
            </div>

            <div class="request-detail__farmer-details">
                <CollapsedBox :title="$t('Farmer details')" :expanded="true">
                    <div class="farmer-details">
                        <p class="f-body-2">
                            <em v-if="!request?.user">{{ $t('Deleted user') }}</em>
                            <span v-else>{{ request?.user?.name }}</span>
                        </p>
                        <p class="f-body-2 farmer-details__email">
                            <a :href="`mailto:${request.contact_email}`">
                                {{ request.contact_email }}
                            </a>
                        </p>
                        <p class="f-body-2 farmer-details__phone">
                            <a :href="`tel:${request.contact_phone}`">
                                {{ request.contact_phone }}
                            </a>
                        </p>
                    </div>
                </CollapsedBox>
            </div>

            <div class="request-detail__request-details">
                <CollapsedBox :title="$t('Summary')" :expanded="true">
                    <p class="request-summary__message">
                        {{ request.message }}
                    </p>

                    <table class="request-summary__table">
                        <tbody>
                            <tr v-if="project?.project_type != null">
                                <td class="request-summary-key f-title-1">
                                    {{ $t('Type of farm') }}
                                </td>
                                <td class="request-summary-value f-body-2">
                                    {{ project.project_type }}
                                </td>
                            </tr>
                            <tr v-else>
                                <td class="request-summary-key f-title-1">
                                    {{ $t('Type of farm') }}
                                </td>
                                <td class="request-summary-value f-body-2">
                                    {{
                                        request.project_type == 0
                                            ? $t('New build')
                                            : $t('Refurbishment')
                                    }}
                                </td>
                            </tr>
                            <tr v-if="project?.sector || request?.sector">
                                <td class="request-summary-key f-title-1">
                                    {{ $t('Sector') }}
                                </td>
                                <td class="request-summary-value f-body-2">
                                    {{
                                        project?.sector?.name ||
                                        request?.sector?.name
                                    }}
                                </td>
                            </tr>
                            <tr v-if="!this.project">
                                <td class="request-summary-key f-title-1">
                                    {{ $t('Sector') }}
                                </td>
                                <td class="request-summary-value f-body-2">
                                    {{ request.sector.name }}
                                </td>
                            </tr>
                            <tr
                                v-if="
                                    project?.length &&
                                    project?.width &&
                                    project?.size_type
                                "
                            >
                                <td class="request-summary-key f-title-1">
                                    {{ $t('Project size') }}
                                </td>
                                <td class="request-summary-value f-body-2">
                                    {{
                                        `${project.width} x ${project.length} ${
                                            project.size_type == 'meter'
                                                ? $t('meters')
                                                : $t('feet')
                                        }`
                                    }}
                                </td>
                            </tr>
                            <tr v-if="project?.frame_type">
                                <td class="request-summary-key f-title-1">
                                    {{ $t('Frame type') }}
                                </td>
                                <td class="request-summary-value f-body-2">
                                    {{ getFrameType(project.frame_type) }}
                                </td>
                            </tr>
                            <tr v-if="project?.age">
                                <td class="request-summary-key f-title-1">
                                    {{ $t('Building age') }}
                                </td>
                                <td class="request-summary-value f-body-2">
                                    {{ project.age }} {{ $t('years') }}
                                </td>
                            </tr>
                            <tr v-if="project?.goals?.length">
                                <td class="request-summary-key f-title-1">
                                    {{ $t('Project goals') }}
                                </td>
                                <td class="request-summary-value f-body-2">
                                    <p v-for="goal in project.goals">
                                        {{ goal.name }}
                                    </p>
                                </td>
                            </tr>
                            <tr v-if="project?.reasons?.length">
                                <td class="request-summary-key f-title-1">
                                    {{ $t('Project reasons') }}
                                </td>
                                <td class="request-summary-value f-body-2">
                                    <p v-for="reason in project.reasons">
                                        {{ reason.name }}
                                    </p>
                                </td>
                            </tr>
                            <tr v-if="project?.responsibility">
                                <td class="request-summary-key f-title-1">
                                    {{ $t('Responsibility') }}
                                </td>
                                <td class="request-summary-value f-body-2">
                                    {{ project.responsibility }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </CollapsedBox>
            </div>

            <div class="request-detail__status" v-if="request.shed_solution">
                <form>
                    <RequestStatus
                        :currentStatus="request.request_status"
                        @valueChanged="valueChanged"
                        @submitStatusUpdate="submitStatusUpdate"
                        :error="errors.status"
                    ></RequestStatus>
                    <img
                        class="request-detail__image"
                        v-if="project && project.project_file_id"
                        :src="project.project_file.url"
                    />
                </form>
            </div>

            <RequestStatus
                v-if="!request.shed_solution"
                :currentStatus="request.request_status"
                @valueChanged="valueChanged"
                @submitStatusUpdate="submitStatusUpdate"
                :error="errors.status"
            ></RequestStatus>
        </div>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { useForm } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../Layout/Base.vue';
import RequestStatus from '../Requests/RequestStatus.vue';
import CollapsedBox from '../../Shared/CollapsedBox.vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    mounted() {
        if (this.$props.request.request_status != null) {
            this.form.status = this.$props.request.request_status;
        } else {
            this.form.status = 0;
        }
    },
    components: {
        LayoutDefault,
        Link,
        RequestStatus,
        CollapsedBox,
    },
    props: {
        status: Number,
        title: String,
        request: Object,
        project: Object,
        errors: Object,
        sector: Number,
    },
    setup(props) {
        const form = useForm({
            status: props.request.request_status,
        });

        return { form };
    },
    methods: {
        valueChanged(change) {
            this.form.status = change.value;
        },
        getStatusText() {
            if (this.form.status != null) {
                switch (this.form.status) {
                    case 0:
                        return this.$t('New request');
                        break;
                    case 1:
                        return this.$t('Contacted');
                        break;
                    case 2:
                        return this.$t('Converted');
                        break;
                    case 3:
                        return this.$t('Project finished');
                        break;
                    case 4:
                        return this.$t('Not converted');
                        break;
                    case 5:
                        return this.$t('Project failed');
                        break;
                }
            }
        },
        submitStatusUpdate() {
            Inertia.post('update-status', this.form);
        },
        getFrameType(frameId) {
            switch (frameId) {
                case 0:
                    return this.$t('Wooden frame');
                    break;
                case 1:
                    return this.$t('Metal frame');
                    break;
                case 2:
                    return this.$t('Other');
                    break;
            }
        },
    },
    computed: {
        requestTag() {
            switch (this.form.status) {
                case 0:
                    return 'tag--green';
                    break;
                case 1:
                    return 'tag--contacted';
                    break;
                case 2:
                    return 'tag--converted';
                    break;
                case 3:
                    return 'tag--finished';
                    break;
                case 4:
                    return 'tag--finished';
                    break;
                case 5:
                    return 'tag--red';
                    break;
            }
        },
    },
};
</script>
