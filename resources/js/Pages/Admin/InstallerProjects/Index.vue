<template>
    <layout-default>
        <div class="admin-back">
            <Link href="/admin/" class="popup__back button-reset">
                {{ $t('Back to dashboard') }}
            </Link>
        </div>
        <h1 class="admin-title">{{ $t('Installer Projects') }}</h1>

        <p class="error-message" v-if="errors?.id">
            {{ errors?.id }}
        </p>

        <AdminTable
            :meta="meta"
            :data="localData"
            :totalPages="9"
            :hideSearch="false"
            :hideFilters="false"
            :showEdit="true"
            :showDelete="true"
            :title="$t('Installer Projects')"
            :isLoading="!isLoaded"
            @editRow="editRow"
            @deleteRow="deleteRow"
            @requestChanged="requestChanged"
            @cellClicked="cellClicked"
        >
        </AdminTable>

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
                {{ $t('Are you sure you want to delete this project?') }}
            </h3>
            <button
                type="button"
                class="button button--fill button--span button--thin button-reset alert-box__cta"
                @click="deleteProject()"
            >
                {{ $t('Delete project') }}
            </button>
        </AlertBox>
    </layout-default>
</template>

<script>
import _ from 'lodash';
import { Link } from '@inertiajs/inertia-vue3';
import { useForm } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../../Layout/Admin.vue';
import AdminTable from '../../../Components/AdminTable.vue';
import AlertBox from '../../../Shared/AlertBox.vue';

export default {
    mounted() {
        this.updateData();
    },
    props: {
        data: Array,
        projects: Array,
        AlertBox: Boolean,
        errors: Object,
    },
    components: {
        LayoutDefault,
        Link,
        AdminTable,
        AlertBox,
    },
    data() {
        return {
            localData: [],
            // build object to store column info
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
                    reference: 'post_title',
                    column: 'post_title',
                    name: 'Title',
                    type: 'link',
                    filterable: false,
                    sortable: false,
                    searchable: false,
                },
                {
                    id: 2,
                    reference: 'name',
                    column: 'author.name',
                    name: 'Author',
                    filterable: true,
                    filters: ['Dan Walker'],
                    filters: [{ id: 0, value: 'Dan Walker' }],
                    sortable: true,
                    searchable: false,
                    type: 'string',
                },
                {
                    id: 3,
                    reference: 'sector',
                    column: 'sector.name',
                    name: 'Sector',
                    type: 'string',
                    filterable: true,
                    filters: [
                        { id: 0, value: 'Dairy' },
                        { id: 1, value: 'Beef' },
                    ],
                    sortable: true,
                    searchable: false,
                },
                {
                    id: 4,
                    reference: 'created_at',
                    column: 'created_at',
                    name: 'Created on',
                    type: 'date',
                    filterable: false,
                    sortable: true,
                    searchable: false,
                },
                {
                    id: 5,
                    reference: 'published_status',
                    column: 'project_published_status',
                    name: 'Publish status',
                    type: 'tag',
                    tagColors: {
                        Published: 'green',
                        Unpublished: 'red',
                    },
                    filterable: true,
                    filters: [
                        { id: 1, value: 'Published' },
                        { id: 0, value: 'Unpublished' },
                    ],
                    sortable: true,
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
                        entry.push(_.get(data, column.column));
                    });
                    this.localData.push(entry);
                });
            }
        },
        deleteRow(id) {
            this.idToDelete = id;
            this.deleteProject();
        },
        deleteProject() {
            if (!this.deletePopup) {
                this.openDeletePopup();
            } else if (this.idToDelete) {
                this.$inertia.post(
                    `/admin/installer-projects/${this.idToDelete}/delete-project`,
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
        editRow(id) {
            this.$inertia.visit(`/admin/installer-projects/${id}/view?edit=1`);
        },
        cellClicked(cell) {
            if (cell.column.id == 1) {
                this.$inertia.visit(
                    `/admin/installer-projects/${cell.row[0]}/view`
                );
            }
        },
        requestChanged(request) {
            // handle change of sort/page/search/filter here
            // this.$inertia.post(`/admin/table/route/here`, request, {
            //     onSuccess() {
            //         this.updateData();
            //     },
            // });
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
