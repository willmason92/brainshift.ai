<template>
    <layout-default>
        <div class="admin-back">
            <Link href="/admin/" class="popup__back button-reset">
                {{ $t('Back to dashboard') }}
            </Link>
        </div>
        <h1 class="admin-title">{{ $t('Request Overview') }}</h1>

        <div class="admin-request__stats">
            <div class="admin-stat-box card">
                <h2 class="admin-stat-box__title">
                    {{ $t('Total Requests') }}
                </h2>
                <p class="admin-stat-box__value">{{ numRequests }}</p>
            </div>
            <div class="admin-stat-box card">
                <h2 class="admin-stat-box__title">{{ $t('New Requests') }}</h2>
                <p class="admin-stat-box__value">{{ numNew }}</p>
            </div>
            <div class="admin-stat-box card">
                <h2 class="admin-stat-box__title">
                    {{ $t('Contacted Requests') }}
                </h2>
                <p class="admin-stat-box__value">{{ numContacted }}</p>
            </div>
        </div>

        <AdminTable
            :meta="meta"
            :data="localData"
            :totalPages="5"
            :hideSearch="true"
            :hideFilters="true"
            title="Requests"
            :isLoading="!isLoaded"
            @cellClicked="cellClicked"
            @dropdownChanged="dropdownChanged"
            @deleteRow="deleteRow"
            @editRow="editRow"
        />

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
import _ from 'lodash';
import LayoutDefault from '../../../Layout/Admin.vue';
import AdminTable from '../../../Components/AdminTable.vue';
import AlertBox from '../../../Shared/AlertBox.vue';

export default {
    components: {
        LayoutDefault,
        Link,
        AdminTable,
        AlertBox,
    },
    props: {
        data: Array,
        numRequests: {
            type: Number,
            default: 0,
        },
        numNew: {
            type: Number,
            default: 0,
        },
        numContacted: {
            type: Number,
            default: 0,
        },
    },
    mounted() {
        this.updateData();
    },
    data() {
        return {
            localData: [],
            meta: [
                {
                    id: 0,
                    column: 'id',
                    name: 'ID',
                    type: 'id',
                    filterable: false,
                    sortable: false,
                    searchable: false,
                },
                {
                    id: 1,
                    column: 'shed_solution.title',
                    reference: 'title',
                    default: 'Untitled project',
                    name: 'Subject',
                    type: 'link',
                    filterable: false,
                    sortable: true,
                    searchable: false,
                },
                {
                    id: 2,
                    column: ['project_type', 'shed_solution.project_type_id'],
                    reference: 'project_type',
                    useEnum: {
                        0: 'New build',
                        1: 'Refurbishment',
                    },
                    name: 'Type',
                    type: 'string',
                    filterable: true,
                    filters: [0, 1],
                    sortable: true,
                    searchable: false,
                },
                {
                    id: 3,
                    column: 'installer.user.name',
                    reference: 'name',
                    name: 'Installer',
                    type: 'string',
                    filterable: true,
                    sortable: true,
                    searchable: false,
                },
                {
                    id: 4,
                    column: 'created_at',
                    reference: 'created_at',
                    name: 'Date received',
                    type: 'date',
                    filterable: false,
                    sortable: true,
                    searchable: false,
                },
                {
                    id: 5,
                    column: 'request_status',
                    reference: 'request_status',
                    name: 'Status',
                    filterable: false,
                    sortable: true,
                    searchable: false,
                    type: 'dropdown',
                    tagColors: {
                        0: 'green',
                        1: 'light-green',
                        2: 'light-green',
                        3: 'faint-green',
                        4: 'grey',
                        5: 'red',
                    },
                    dropdownOptions: [
                        { id: 0, value: 'New request', color: 'green' },
                        { id: 1, value: 'Contacted', color: 'light-green' },
                        { id: 2, value: 'Converted', color: 'light-green' },
                        {
                            id: 3,
                            value: 'Project finished',
                            color: 'faint-green',
                        },
                        { id: 4, value: 'Not converted', color: 'grey' },
                        { id: 5, value: 'Project failed', color: 'red' },
                    ],
                    dropdownTree: {
                        0: [0, 1, 4],
                        1: [1, 2, 4],
                        2: [2, 3, 5],
                        3: [3],
                        4: [4],
                        5: [5],
                    },
                    default: 0,
                },
            ],
            deletePopup: false,
            idToDelete: null,
            isLoaded: true,
        };
    },
    methods: {
        updateData() {
            if (this.data) {
                this.data.forEach((data) => {
                    let entry = [];
                    entry.push(data.id);
                    this.meta.forEach((column) => {
                        if (Array.isArray(column.column)) {
                            for (let potentialKey of column.column) {
                                if (_.get(data, potentialKey) != null) {
                                    let value = _.get(data, potentialKey);

                                    if (column?.useEnum) {
                                        entry.push(column.useEnum[value]);
                                    } else {
                                        entry.push(value);
                                    }
                                    break;
                                }
                            }
                        } else {
                            if (column?.useEnum) {
                                entry.push(
                                    column.useEnum[_.get(data, column.column)]
                                );
                            } else {
                                let potentialValue =
                                    _.get(data, column.column) ||
                                    column.default;
                                entry.push(potentialValue);
                            }
                        }
                    });

                    this.localData.push(entry);
                });
            }
        },
        cellClicked(cell) {
            if (cell.column.id == 1) {
                this.$inertia.visit(`/admin/requests/${cell.row[0]}/view`);
            }
        },
        dropdownChanged({ change, row }) {
            this.$inertia.post(
                `/admin/requests/${row[0]}/update-status`,
                {
                    status: change.value,
                },
                {
                    onBefore: () => {
                        this.isLoaded = false;
                    },
                    onFinish: () => {
                        this.isLoaded = true;
                    },
                }
            );
        },
        editRow(id) {
            this.$inertia.visit(
                `/admin/manage-installers/${id}/view?edit=true`
            );
        },
        deleteRow(id) {
            this.idToDelete = id;
            this.deleteRequest();
        },
        deleteRequest() {
            if (!this.deletePopup) {
                this.openDeletePopup();
            } else if (this.idToDelete) {
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
    computed: {},
};
</script>
