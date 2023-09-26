<template>
    <layout-default>
        <div class="admin-back">
            <Link href="/admin/requests" class="popup__back button-reset">
                {{ $t('Back to overview') }}
            </Link>
        </div>

        <div class="admin-title-wrapper">
            <h1 class="admin-title">
                <template v-if="request?.shed_solution">
                    {{ $t('Request') }}: {{ request.shed_solution.title }}
                </template>
                <template v-else>
                    {{ $t('Request from') }}
                    {{ request?.user?.name || $t('deleted user') }}
                </template>
            </h1>
            <StatusDropdown
                inputName="status"
                :defaultOption="{
                    id: request.request_status,
                    value: getStatus(request.request_status).value,
                    color: getStatus(request.request_status).color,
                }"
                :options="formattedStatuses"
                @valueChanged="updateStatus"
            />
        </div>

        <div class="card admin-details">
            <h2 class="admin-subtitle">
                {{ $t('Farmer request') }}
                <span class="admin-subtitle__after">
                    {{ formattedRequestDate }}
                </span>
            </h2>
            <div class="admin-detail">
                <div class="col admin-label">
                    <h3 class="admin-label__text">{{ $t('Name') }}</h3>
                    {{ request?.user?.name || $t('Deleted user') }}
                </div>
                <div class="col admin-label" v-if="request?.user">
                    <h3 class="admin-label__text">{{ $t('Location') }}</h3>
                    <template v-if="request?.user?.location">
                        {{ request?.user?.location?.street }},
                        {{ request?.user?.location?.postcode }}
                    </template>
                    <template v-else> {{ $t('Unknown location') }} </template>
                </div>
            </div>
            <div class="admin-detail" v-if="request?.user">
                <div class="col admin-label">
                    <h3 class="admin-label__text">
                        {{ $t('Email address') }}
                    </h3>
                    <a :href="'mailto:' + request?.user?.email">
                        {{ request.user.email }}
                    </a>
                </div>
                <div class="col admin-label">
                    <h3 class="admin-label__text">
                        {{ $t('Telephone number') }}
                    </h3>
                    {{ request.user.phone }}
                </div>
            </div>
            <div class="admin-detail">
                <div class="col admin-label">
                    <h3 class="admin-label__text">
                        {{ $t('Project type') }}
                    </h3>
                    {{
                        request.project_type == 0
                            ? 'New Build'
                            : 'Refurbishment'
                    }}
                </div>
                <div class="col admin-label">
                    <h3 class="admin-label__text">
                        {{ $t('Sector') }}
                    </h3>
                    {{
                        request?.sector?.name ||
                        request?.shed_solution?.sector.name
                    }}
                </div>
            </div>
            <div class="admin-detail" v-if="request?.shed_solution">
                <div class="col admin-label">
                    <h3 class="admin-label__text">
                        {{ $t('Project size') }}
                    </h3>
                    {{ request.shed_solution.width }} x
                    {{ request.shed_solution.length }}
                    {{ request.shed_solution.size_type }}
                </div>
                <div
                    class="col admin-label"
                    v-if="request?.shed_solution?.frame_type"
                >
                    <h3 class="admin-label__text">
                        {{ $t('Frame type') }}
                    </h3>
                    {{ frameType }}
                </div>
            </div>
            <div class="admin-detail" v-if="request?.shed_solution">
                <div
                    class="col admin-label"
                    v-if="request?.shed_solution?.goals?.length"
                >
                    <h3 class="admin-label__text">{{ $t('Goals') }}</h3>
                    <div class="admin-request__goals">
                        <div
                            class="active-filter pill"
                            v-for="goal in request?.shed_solution?.goals"
                        >
                            {{ goal.name }}
                        </div>
                    </div>
                </div>
                <div
                    class="col admin-label"
                    v-if="request?.shed_solution?.reasons?.length"
                >
                    <h3 class="admin-label__text">{{ $t('Reasons') }}</h3>
                    <div class="admin-request__goals">
                        <div
                            class="active-filter pill"
                            v-for="reason in request.shed_solution.reasons"
                        >
                            {{ reason.name }}
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="admin-detail"
                v-if="request?.shed_solution?.responsibility"
            >
                <div class="col admin-label">
                    <h3 class="admin-label__text">
                        {{ $t('Responsibility') }}
                    </h3>
                    {{ request.shed_solution.responsibility }}
                </div>
            </div>
            <div class="admin-detail">
                <div class="col admin-label full">
                    <h3 class="admin-label__text">{{ $t('Message') }}</h3>
                    <p class="f-body-2">
                        {{ request.message }}
                    </p>
                </div>
            </div>
            <div class="admin-detail" v-if="request?.shed_solution">
                <div class="col admin-label full">
                    <h3 class="admin-label__text">{{ $t('Project file') }}</h3>
                    <div class="admin-request__file">
                        <div class="admin-request-file__image">
                            <img
                                :src="request.shed_solution.project_file.url"
                                :alt="request.shed_solution.title"
                            />
                        </div>
                        <a
                            class="admin-request-file__title"
                            :href="request.shed_solution.project_file.url"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="17"
                                fill="none"
                            >
                                <path
                                    fill="#E60000"
                                    d="m12.042 9.136 1.024-1.023a2.366 2.366 0 0 0 0-3.342 2.366 2.366 0 0 0-3.343 0L7.152 7.34l-.01.01-1.276 1.276a.912.912 0 0 0 0 1.287.912.912 0 0 0 1.286 0l3.086-3.086h-.001a.549.549 0 0 1 .782-.011.544.544 0 0 1-.01.782l-3.086 3.086a1.993 1.993 0 0 1-1.414.584c-.53.002-1.04-.209-1.415-.584a2.003 2.003 0 0 1 0-2.83L8.18 4.772l.771-.772a3.458 3.458 0 0 1 4.886 0 3.458 3.458 0 0 1 0 4.885l-2.571 2.572-.013.013-1.787 1.787a4.893 4.893 0 0 1-3.471 1.435 4.893 4.893 0 0 1-3.472-1.436 4.873 4.873 0 0 1-1.432-3.47c0-1.314.508-2.548 1.432-3.471l4.115-4.115a.546.546 0 0 1 .771.772L3.295 7.085a3.792 3.792 0 0 0-1.113 2.7 3.79 3.79 0 0 0 1.113 2.7 3.791 3.791 0 0 0 2.7 1.113 3.792 3.792 0 0 0 2.7-1.113l3.343-3.343.005-.005Z"
                                />
                            </svg>

                            <span>{{ request.shed_solution.title }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card admin-details">
            <div class="admin-request_installer-title">
                <h2 class="admin-subtitle">
                    {{ $t('Installer') }}
                </h2>
                <Link
                    class="admin-request__details-btn button--admin"
                    :href="`/admin/manage-installers/${request.installer?.user_id}/view`"
                    >{{ $t('Details') }}</Link
                >
            </div>

            <div class="admin-detail">
                <div class="col admin-label">
                    <h3 class="admin-label__text">{{ $t('Name') }}</h3>
                    {{ request.installer.user.name }}
                </div>
                <div class="col admin-label">
                    <h3 class="admin-label__text">{{ $t('Location') }}</h3>
                    <template v-if="request.installer?.nationwide">
                        {{ $t('Nationwide') }}
                    </template>
                    <template v-else>
                        {{ request.installer?.user?.location?.street }},
                        {{ request.installer?.user?.location?.postcode }}
                    </template>
                </div>
            </div>
            <div class="admin-detail">
                <div class="col admin-label">
                    <h3 class="admin-label__text">{{ $t('Email address') }}</h3>
                    <a :href="'mailto:' + request.installer.user.email">{{
                        request.installer.user.email
                    }}</a>
                </div>
                <div class="col admin-label">
                    <h3 class="admin-label__text">
                        {{ $t('Telephone number') }}
                    </h3>
                    {{ request.installer.user.phone }}
                </div>
            </div>
            <div
                class="admin-detail"
                v-if="request?.installer?.sectors?.length"
            >
                <div class="col admin-label">
                    <h3 class="admin-label__text">{{ $t('Specialities') }}</h3>
                    <div class="admin-request__tags">
                        <div
                            v-for="sector in request.installer.sectors"
                            class="tag"
                        >
                            {{ sector.name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button
            class="button button--fill button--slim"
            type="button"
            @click="deleteRequest"
        >
            {{ $t('Delete request') }}
        </button>

        <AlertBox
            v-if="deletePopup"
            :active="deletePopup"
            @active-state="closePopup"
        >
            <img
                class="login-alert__icon"
                src="../../../../images/alert-outline.svg"
                alt=""
                width="60"
                height="60"
            />
            <h3 class="alert-box__title">
                {{ $t('Are you sure you want to delete this request?') }}
            </h3>
            <button
                type="button"
                class="button button--fill button--span button--thin button-reset alert-box__cta"
                @click="deleteRequest()"
            >
                {{ $t('Delete request') }}
            </button>
        </AlertBox>
    </layout-default>
</template>
<script>
import { Link } from '@inertiajs/inertia-vue3';
import moment from 'moment';
import LayoutDefault from '../../../Layout/Admin.vue';
import AdminTable from '../../../Components/AdminTable.vue';
import StatusDropdown from '../../../Shared/Inputs/StatusDropdown.vue';
import AlertBox from '../../../Shared/AlertBox.vue';

export default {
    mounted() {
        this.currentStatus = this.request.request_status;
    },
    components: {
        LayoutDefault,
        Link,
        AdminTable,
        StatusDropdown,
        AlertBox,
    },
    props: {
        request: Object,
    },
    data() {
        return {
            requestMeta: [
                {
                    id: 0,
                    column: 'id',
                    name: 'id',
                    type: 'number',
                    filterable: false,
                    sortable: false,
                    searchable: false,
                },
                {
                    id: 1,
                    column: 'subject',
                    name: 'Subject',
                    type: 'string',
                    filterable: false,
                    sortable: true,
                    searchable: false,
                },
                {
                    id: 2,
                    column: 'type',
                    name: 'Type',
                    type: 'string',
                    filterable: false,
                    sortable: true,
                    searchable: false,
                },
                {
                    id: 3,
                    column: 'date_received',
                    name: 'Date received',
                    type: 'string',
                    filterable: false,
                    sortable: true,
                    searchable: false,
                },
                {
                    id: 4,
                    column: 'status',
                    name: 'Status',
                    filterable: false,
                    sortable: true,
                    searchable: false,
                    type: 'tag',
                    tagColors: {
                        'New request': 'green',
                        Contacted: 'light-green',
                        Converted: 'light-green',
                        'Project finished': 'faint-green',
                        'Not converted': 'grey',
                        'Project failed': 'red',
                    },
                },
            ],
            requestStatuses: [
                { id: 0, value: 'New request', color: 'green' },
                { id: 1, value: 'Contacted', color: 'light-green' },
                { id: 2, value: 'Converted', color: 'light-green' },
                { id: 3, value: 'Project finished', color: 'faint-green' },
                { id: 4, value: 'Not converted', color: 'grey' },
                { id: 5, value: 'Project failed', color: 'red' },
            ],
            currentStatus: 0,
            statusTree: [[0, 1, 4], [1, 2, 4], [2, 3, 5], [3], [4], [5]],
            deletePopup: false,
        };
    },
    methods: {
        updateStatus(change) {
            this.currentStatus = change.value.id;
            this.$inertia.post(
                `/admin/requests/${this.request.id}/update-status`,
                {
                    status: change.value.id,
                }
            );
        },
        getStatus(id) {
            if (id != null) {
                switch (id) {
                    case 0:
                        return {
                            value: this.$t('New request'),
                            color: 'green',
                        };
                        break;
                    case 1:
                        return {
                            value: this.$t('Contacted'),
                            color: 'light-green',
                        };
                        break;
                    case 2:
                        return {
                            value: this.$t('Converted'),
                            color: 'light-green',
                        };
                        break;
                    case 3:
                        return {
                            value: this.$t('Project finished'),
                            color: 'faint-green',
                        };
                        break;
                    case 4:
                        return {
                            value: this.$t('Not converted'),
                            color: 'grey',
                        };
                        break;
                    case 5:
                        return {
                            value: this.$t('Project failed'),
                            color: 'red',
                        };
                        break;
                }
            } else {
                return 'Unknown';
            }
        },

        deleteRequest() {
            if (!this.deletePopup) {
                this.openDeletePopup();
            } else {
                // inertia call here
                this.closePopup();
            }
        },
        closePopup() {
            this.deletePopup = false;
        },
        openDeletePopup() {
            this.deletePopup = true;
        },
    },
    computed: {
        frameType() {
            if (this.request?.shed_solution) {
                switch (this.request.shed_solution.frame_type) {
                    case 0:
                        return 'Wooden';
                    case 1:
                        return 'Metal';
                    case 2:
                        return 'Other';
                }
            }
        },
        formattedRequestDate() {
            return moment(this.request.created_at).format('DD/MM/YYYY');
        },
        formattedStatuses() {
            return this.requestStatuses.filter((status) => {
                return this.statusTree[this.currentStatus].includes(status.id);
            });
        },
    },
};
</script>
