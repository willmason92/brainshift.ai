<template>
    <layout-default>
        <div class="admin-back">
            <Link href="/admin/" class="popup__back button-reset">
                {{ $t('Back to dashboard') }}
            </Link>
            <Link
                href="/admin/manage-materials/add"
                class="button button--fill button--thin admin-title__cta"
            >
                {{ $t('Add material') }}
            </Link>
        </div>
        <h1 class="admin-title">{{ $t('Manage materials') }}</h1>

        <AdminTable
            :meta="meta"
            :data="localData"
            :totalPages="5"
            :hideSearch="true"
            :hideFilters="true"
            :showEdit="true"
            :showDelete="true"
            :title="$t('Materials')"
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
                {{ $t('Are you sure you want to delete this material?') }}
            </h3>
            <button
                type="button"
                class="button button--fill button--span button--thin button-reset alert-box__cta"
                @click="deleteMaterial()"
            >
                {{ $t('Delete material') }}
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
                    sortable: false,
                    searchable: false,
                },
                {
                    id: 1,
                    column: 'file.url',
                    reference: 'file_url',
                    name: 'Image',
                    type: 'image',
                    filterable: false,
                    sortable: false,
                    searchable: false,
                },
                {
                    id: 2,
                    column: 'name',
                    reference: 'name',
                    name: 'Name',
                    type: 'link',
                    filterable: false,
                    sortable: true,
                    searchable: false,
                },
                {
                    id: 3,
                    column: 'product_line',
                    reference: 'product_line',
                    name: 'Product Line',
                    type: 'string',
                    filterable: true,
                    sortable: false,
                    searchable: false,
                },
                {
                    id: 4,
                    column: 'colour.name',
                    reference: 'colour',
                    name: 'Colour',
                    type: 'string',
                    filterable: true,
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
                                    if (column.subcolumn) {
                                        entry.push(value[column.subcolumn]);
                                    } else {
                                        entry.push(value);
                                    }
                                }
                            }
                        }
                    });
                    this.localData.push(entry);
                });
            }
        },
        cellClicked(cell) {
            if (cell.column.id == 1 || cell.column.id == 2) {
                this.$inertia.visit(
                    `/admin/manage-materials/${cell.row[0]}/edit`
                );
            }
        },
        editRow(id) {
            this.$inertia.visit(`/admin/manage-materials/${id}/edit`);
        },
        deleteRow(id) {
            this.idToDelete = id;
            this.deleteMaterial();
        },
        deleteMaterial() {
            if (!this.deletePopup) {
                this.openDeletePopup();
            } else if (this.idToDelete) {
                this.$inertia.post(
                    `/admin/manage-materials/${this.idToDelete}/delete-material`,
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
