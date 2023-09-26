<template>
    <layout-default>
        <div class="admin-back">
            <Link href="/admin/" class="popup__back button-reset">
                {{ $t('Back to dashboard') }}
            </Link>
            <Link
                href="/admin/manage-admins/add"
                class="button button--fill button--thin admin-title__cta"
            >
                {{ $t('Add admin') }}
            </Link>
        </div>
        <h1 class="admin-title">{{ $t('Manage Admins') }}</h1>

        <p class="error-message" v-if="errors?.id">
            {{ errors?.id }}
        </p>

        <AdminTable
            :meta="meta"
            :data="localData"
            :totalPages="5"
            :hideSearch="true"
            :hideFilters="true"
            :showDelete="true"
            :showEdit="true"
            :title="$t('Requests')"
            :isLoading="!isLoaded"
            @deleteRow="deleteRow"
            @editRow="editRow"
            @cellClicked="cellClicked"
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
                {{ $t('Are you sure you want to delete this admin?') }}
            </h3>
            <button
                type="button"
                class="button button--fill button--span button--thin button-reset alert-box__cta"
                @click="deleteAdmin()"
            >
                {{ $t('Delete admin') }}
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
    mounted() {
        console.log(this.data);
        this.updateData();
    },
    components: {
        LayoutDefault,
        Link,
        AdminTable,
        AlertBox,
    },
    props: {
        data: Object,
        errors: Object,
    },
    data() {
        return {
            localData: [],
            meta: [
                {
                    id: 0,
                    column: 'id',
                    reference: 'id',
                    name: 'ID',
                    type: 'id',
                    filterable: false,
                    sortable: false,
                    searchable: false,
                },
                {
                    id: 1,
                    column: 'name',
                    reference: 'name',
                    name: 'Name',
                    type: 'link',
                    filterable: false,
                    sortable: true,
                    searchable: false,
                },
                {
                    id: 2,
                    column: 'email',
                    reference: 'email',
                    name: 'Email address',
                    type: 'string',
                    filterable: false,
                    sortable: false,
                    searchable: false,
                },
                {
                    id: 3,
                    column: 'regions.region_name',
                    reference: 'regions.region_name',
                    name: 'Region',
                    default: 'No region',
                    type: 'string',
                    filterable: false,
                    sortable: false,
                    searchable: false,
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
                this.localData = [];
                this.data.forEach((data) => {
                    let entry = [];
                    entry.push(data.id);
                    this.meta.forEach((column) => {
                        if (Array.isArray(column.column)) {
                            for (let potentialKey of column.column) {
                                if (_.get(data, potentialKey)) {
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
                                entry.push(_.get(data, column.column));
                            }
                        }
                    });
                    this.localData.push(entry);
                });
            }
        },
        cellClicked(cell) {
            if (cell.column.id == 1) {
                this.$inertia.visit(`/admin/manage-admins/${cell.row[0]}/view`);
            }
        },
        editRow(id) {
            this.$inertia.visit(`/admin/manage-admins/${id}/view`);
        },
        deleteRow(id) {
            this.idToDelete = id;
            this.deleteAdmin();
        },
        deleteAdmin() {
            if (!this.deletePopup) {
                this.openDeletePopup();
            } else if (this.idToDelete) {
                console.log('deleting!', this.idToDelete);
                this.$inertia.post(
                    `/admin/manage-admins/${this.idToDelete}/delete-admin`,
                    this.idToDelete,
                    {
                        preserveScroll: true,
                        onSuccess: (data) => {
                            this.updateData();
                        },
                        onBefore: () => {
                            this.isLoaded = false;
                        },
                        onFinish: () => {
                            this.isLoaded = true;
                        },
                    }
                );

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
};
</script>
