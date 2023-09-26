<template>
    <layout-default>
        <div class="admin-back">
            <Link href="/admin/" class="popup__back button-reset">
                {{ $t('Back to dashboard') }}
            </Link>
        </div>
        <h1 class="admin-title">{{ $t('Manage Installers') }}</h1>

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
            :showEdit="true"
            :title="$t('Installers')"
            :isLoading="!isLoaded"
            @cellClicked="cellClicked"
            @deleteRow="deleteRow"
            @editRow="editRow"
        />
        <AlertBox
            v-if="deletePopup && hasRole('superadmin')"
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
                {{ $t('Are you sure you want to delete this installer?') }}
            </h3>
            <button
                type="button"
                class="button button--fill button--span button--thin button-reset alert-box__cta"
                @click="deleteUser()"
            >
                {{ $t('Delete installer') }}
            </button>
        </AlertBox>
    </layout-default>
</template>
<script>
import { Link } from '@inertiajs/inertia-vue3';
import { usePage } from '@inertiajs/inertia-vue3';
import _ from 'lodash';
import LayoutDefault from '../../../Layout/Admin.vue';
import AdminTable from '../../../Components/AdminTable.vue';
import AlertBox from '../../../Shared/AlertBox.vue';
import useUserRole from '../../../Composables/useUserRole';

export default {
    mounted() {
        this.updateData();
    },
    setup() {
        const { hasRole } = useUserRole();
        return { hasRole };
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
                    reference: 'name_column',
                    column: 'name',
                    name: 'Name',
                    type: 'link',
                    filterable: false,
                    sortable: true,
                    searchable: false,
                },
                {
                    id: 2,
                    reference: 'company_name',
                    column: 'expert.company_name',
                    name: 'Company name',
                    type: 'string',
                    filterable: false,
                    sortable: false,
                    searchable: false,
                },
                {
                    id: 3,
                    reference: 'country',
                    column: 'location.country',
                    name: 'Country',
                    default: 'No country',
                    type: 'string',
                    filterable: false,
                    sortable: false,
                    searchable: false,
                },
                {
                    id: 4,
                    reference: 'sector',
                    column: 'expert.sectors',
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
            user: usePage().props?.value?.user,
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
            if (cell.column.id == 1) {
                this.$inertia.visit(
                    `/admin/manage-installers/${cell.row[0]}/view`
                );
            }
        },
        editRow(id) {
            this.$inertia.visit(
                `/admin/manage-installers/${id}/view?edit=true`
            );
        },
        deleteRow(id) {
            this.idToDelete = id;
            this.deleteUser();
        },
        deleteUser() {
            if (!this.deletePopup) {
                this.openDeletePopup();
            } else if (this.idToDelete) {
                this.$inertia.post(
                    `/admin/manage-installers/${this.idToDelete}/delete-installer`,
                    this.idToDelete,
                    {
                        preserveScroll: true,
                        onBefore: () => {
                            this.isLoaded = false;
                        },
                        onSuccess: (data) => {
                            this.updateData();
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
