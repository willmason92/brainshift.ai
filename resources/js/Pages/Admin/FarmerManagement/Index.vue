<template>
    <layout-default>
        <h1 class="admin-title">{{ $t('Manage Farmers') }}</h1>

        <p class="error-message" v-if="errors?.id">
            {{ errors?.id }}
        </p>

        <AdminTable
            :meta="meta"
            :data="localData"
            :totalPages="5"
            :hideSearch="true"
            :hideFilters="true"
            :showDelete="hasRole('superadmin')"
            :showEdit="hasRole('superadmin')"
            :title="$t('Farmers')"
            :isLoading="!isLoaded"
            @cellClicked="cellClicked"
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
                {{ $t('Are you sure you want to delete this farmer?') }}
            </h3>
            <button
                type="button"
                class="button button--fill button--span button--thin button-reset alert-box__cta"
                @click="deleteUser()"
            >
                {{ $t('Delete farmer') }}
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
import useUserRole from '../../../Composables/useUserRole';

export default {
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
    setup() {
        const { hasRole } = useUserRole();
        return { hasRole };
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
                    reference: 'id',
                    name: 'ID',
                    type: 'id',
                    filterable: false,
                    sortable: true,
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
                    column: 'location.name',
                    reference: 'farm_name',
                    name: 'Farm name',
                    type: 'string',
                    filterable: false,
                    sortable: false,
                    searchable: false,
                },
                {
                    id: 3,
                    column: 'location.country',
                    reference: 'country',
                    name: 'Country',
                    default: 'No country',
                    type: 'string',
                    filterable: false,
                    sortable: false,
                    searchable: false,
                },
                {
                    id: 4,
                    column: 'sectors',
                    reference: 'sectors',
                    subcolumn: 'name',
                    name: 'Sectors',
                    type: 'tag',
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
                                let value = _.get(data, column.column);
                                if (Array.isArray(value)) {
                                    let arrayEntry = [];
                                    value.forEach((subvalue) => {
                                        if (column.subcolumn) {
                                            arrayEntry.push(
                                                subvalue[column.subcolumn]
                                            );
                                        } else {
                                            arrayEntry.push(subvalue);
                                        }
                                    });
                                    entry.push(arrayEntry);
                                } else {
                                    entry.push(value);
                                }
                            }
                        }
                    });
                    this.localData.push(entry);
                });
            }
        },
        cellClicked(cell) {
            if (cell.column.column == 'name') {
                this.$inertia.visit(
                    `/admin/manage-farmers/${cell.row[0]}/view`
                );
            }
        },
        editRow(id) {
            this.$inertia.visit(`/admin/manage-farmers/${id}/view?edit=true`);
        },
        deleteRow(id) {
            this.idToDelete = id;
            this.deleteUser();
        },
        deleteUser() {
            if (!this.deletePopup) {
                this.openDeletePopup();
            } else if (this.idToDelete) {
                // inertia call here
                this.$inertia.post(
                    `/admin/manage-farmers/${this.idToDelete}/delete-farmer`,
                    this.idToDelete,
                    {
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
