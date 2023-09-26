<template>
    <layout-default>
        <h1 class="admin-title">
            <span>{{ $t('Static pages') }}</span>
        </h1>
        <!-- title only shows if filters hidden -->
        <AdminTable
            :meta="meta"
            :data="localData"
            :totalPages="5"
            :hideSearch="true"
            :hideFilters="true"
            :showEdit="true"
            :showDelete="false"
            :title="$t('Static pages')"
            @editRow="editRow"
            @requestChanged="requestChanged"
            @cellClicked="cellClicked"
        />
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
    name: 'ManageBlog',
    props: {
        data: Array,
        projects: Array,
        AlertBox: Boolean,
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
                    column: 'title',
                    name: 'Title',
                    filterable: false,
                    sortable: false,
                    searchable: false,
                    type: 'link',
                },
            ],
        };
    },
    methods: {
        updateData() {
            if (this.data) {
                this.data.forEach((data) => {
                    if (data.key.includes('static_page.')) {
                        let entry = [];
                        entry.push(data.id);
                        this.meta.forEach((column) => {
                            if (column.column == 'title') {
                                let post = _.get(data, 'value');
                                let title =
                                    post.match(
                                        /(?<=\>)(?!\<)(.*)(?=\<)(?<!\>)/
                                    ) || 'No title';
                                entry.push(title[0]);
                            } else {
                                entry.push(_.get(data, column.column));
                            }
                        });
                        this.localData.push(entry);
                    }
                });
            }
        },
        editRow(id) {
            this.$inertia.visit(`/admin/manage-static-pages/${id}/edit`);
        },
        cellClicked(cell) {
            if (cell.column.id == 0) {
                this.$inertia.visit(
                    `/admin/manage-static-pages/${cell.row[0]}/edit`
                );
            }
        },
        requestChanged(request) {
            // @todo handle change of sort/page/search/filter here
        },
    },
};
</script>
