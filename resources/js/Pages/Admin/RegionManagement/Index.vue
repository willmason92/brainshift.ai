<template>
    <layout-default>
        <div class="admin-back">
            <Link href="/admin/" class="popup__back button-reset">
                {{ $t('Back to dashboard') }}
            </Link>
            <Link
                href="/admin/manage-regions/add"
                class="button button--fill button--thin admin-title__cta"
            >
                {{ $t('Add region') }}
            </Link>
        </div>
        <h1 class="admin-title">{{ $t('Manage Regions') }}</h1>

        <!-- @todo link up region column, replace with dropdown -->
        <AdminTable
            :meta="meta"
            :data="localData"
            :totalPages="5"
            :hideSearch="true"
            :hideFilters="true"
            :showEdit="true"
            :showDelete="true"
            :title="$t('Regions')"
            :isLoading="!isLoaded"
            @cellClicked="cellClicked"
            @editRow="editRow"
            @deleteRow="deleteRegion"
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
                {{ $t('Are you sure you want to delete this region?') }}
            </h3>
            <button
                type="button"
                class="button button--fill button--span button--thin button-reset alert-box__cta"
                @click="deleteRegion()"
            >
                {{ $t('Delete region') }}
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
                    column: 'region_name',
                    reference: 'region_name',
                    name: 'Region name',
                    type: 'link',
                    filterable: false,
                    sortable: true,
                    searchable: false,
                },
                {
                    id: 2,
                    column: 'user.name',
                    reference: 'user.name',
                    name: 'Admin name',
                    type: 'string',
                    filterable: false,
                    sortable: false,
                    searchable: false,
                },
                {
                    id: 3,
                    column: 'user.email',
                    reference: 'user.email',
                    name: 'Admin contact',
                    type: 'string',
                    filterable: false,
                    sortable: false,
                    searchable: false,
                },
            ],
            deletePopup: false,
            regionToDelete: null,
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
        closePopup() {
            this.deletePopup = false;
        },
        cellClicked(cell) {
            if (cell.column.id == 1) {
                this.$inertia.visit(
                    `/admin/manage-regions/${cell.row[0]}/view`
                );
            }
        },
        deleteRegion(id) {
            if (!this.regionToDelete) {
                this.regionToDelete = id;
            }

            if (this.deletePopup) {
                // inertia call to delete region
                this.$inertia.post(
                    `/admin/manage-regions/${this.regionToDelete}/delete-region`,
                    this.regionToDelete,
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
                this.regionToDelete = null;
            } else {
                this.deletePopup = true;
            }
        },
        editRow(id) {
            this.$inertia.visit(`/admin/manage-regions/${id}/view`);
        },
    },
};
</script>
