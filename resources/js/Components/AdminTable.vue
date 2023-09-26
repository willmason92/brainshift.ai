<template>
    <div class="admin-table-wrapper" :class="{ loading: isLoading }">
        <AdminTableControls
            v-if="!hideControls"
            :meta="meta.filter((meta) => meta.filterable)"
            @changePerPage="changePerPage"
            @updateFilters="updateFilters"
            @updateSearch="updateSearch"
            :hideSearch="hideSearch"
            :hideFilters="hideFilters"
            :title="title"
        />
        <table class="admin-table">
            <colgroup>
                <col :style="{ width: firstColWidth }" />
                <col
                    :style="[
                        meta.length > 2 && meta[1].type == 'image'
                            ? { width: '15rem' }
                            : '',
                    ]"
                />
                <template v-if="meta.length > 2">
                    <col v-for="column in meta.length - 2" />
                </template>
            </colgroup>
            <thead>
                <tr>
                    <th
                        class="admin-table-cell admin-table-head h7"
                        :class="{
                            'admin-table-head--sortable': column.sortable,
                        }"
                        v-for="column in meta"
                        scope="col"
                        @click="orderBy(column)"
                    >
                        <span>{{ column.name }}</span>
                        <svg
                            v-if="getSorted(column) && column.sortable"
                            class="admin-sort"
                            :class="{ invert: !getSorted(column)?.dsc }"
                            width="9"
                            height="14"
                            viewBox="0 0 9 14"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M3.91281 11.0061V0.44165H5.08719V11.0061L8.16959 7.5065L9 8.44932L4.5 13.5584L0 8.44932L0.830411 7.5065L3.91281 11.0061Z"
                                fill="#CCC5B8"
                            />
                        </svg>
                        <svg
                            v-else-if="column.sortable"
                            class="admin-sort"
                            width="15"
                            height="14"
                            viewBox="0 0 15 14"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M3.91281 11.0061V0.44165H5.08719V11.0061L8.16959 7.5065L9 8.44932L4.5 13.5584L0 8.44932L0.830411 7.5065L3.91281 11.0061Z"
                                fill="#CCC5B8"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M10.5 0.44165L15 5.55075L14.1696 6.49357L11.0872 2.99394L11.0872 13.5584H9.91281L9.91281 2.99394L6.83041 6.49357L6 5.55075L10.5 0.44165Z"
                                fill="#CCC5B8"
                            />
                        </svg>
                    </th>

                    <th
                        v-if="showEdit || showDelete"
                        class="admin-table-cell admin-table-head admin-table-head--control h7"
                        scope="col"
                    ></th>
                </tr>
            </thead>
            <tbody>
                <tr
                    class="admin-table-row"
                    v-for="row in data.slice(0, request.paging.show)"
                >
                    <AdminTableCell
                        v-for="(cell, idx) in row.slice(1)"
                        :type="meta[idx].type"
                        :value="
                            cell == null
                                ? meta[idx]?.default || 'Unknown'
                                : cell
                        "
                        :column="meta[idx]"
                        :id="row[0]"
                        @click="
                            this.$emit('cellClicked', {
                                column: meta[idx],
                                row: row,
                            })
                        "
                        @dropdownChanged="
                            (change) =>
                                this.$emit('dropdownChanged', { change, row })
                        "
                    />
                    <AdminTableCell
                        @delete="deleteRow"
                        @edit="editRow"
                        :showDelete="showDelete"
                        :showEdit="showEdit"
                        v-if="showDelete || showEdit"
                        :id="row[0]"
                        type="control"
                    />
                </tr>
            </tbody>
        </table>
        <AdminTablePagination
            :currentPage="request.paging.page"
            :totalPages="totalPages"
            @changePage="changePage"
        />
    </div>
</template>

<script>
import AdminTableCell from './AdminTableCell.vue';
import AdminTableControls from './AdminTableControls.vue';
import AdminTablePagination from './AdminTablePagination.vue';

export default {
    name: 'AdminTable',
    emits: [
        'requestChanged',
        'deleteRow',
        'editRow',
        'cellClicked',
        'dropdownChanged',
    ],
    components: {
        AdminTableCell,
        AdminTableControls,
        AdminTablePagination,
    },
    props: {
        data: Array,
        meta: Array,
        totalPages: Number,
        hideSearch: Boolean,
        hideFilters: Boolean,
        title: String,
        showEdit: Boolean,
        showDelete: Boolean,
        isLoading: {
            type: Boolean,
            default: false,
        },
    },
    mounted() {
        this.lastUpdated = +new Date();
    },
    data() {
        return {
            sorts: [],
            filters: [],
            activeFilters: [],

            request: {
                filters: {},
                sort: {},
                search: null,
                paging: {
                    page: 1,
                    show: 10,
                },
            },

            lastUpdated: null,
            updateWaiting: false,
        };
    },
    methods: {
        orderBy(column) {
            // if column not sortable, return
            if (!column.sortable) return;

            // test to see if column is being sorted
            if (column.reference == this.request.sort?.column) {
                if (this.request.sort.dsc) {
                    this.request.sort.dsc = false;
                } else {
                    this.request.sort = {};
                }
            } else {
                this.request.sort = {
                    column: column?.reference || column?.id,
                    dsc: true,
                };
            }
        },

        getSorted(column) {
            if (this.request.sort == null) return null;

            if (column.reference == this.request.sort.column) {
                return this.request.sort;
            }
            return null;
        },

        changePerPage(amount) {
            this.request.paging.show = amount;
        },

        deleteRow(id) {
            this.$emit('deleteRow', id);
        },

        editRow(id) {
            this.$emit('editRow', id);
        },

        changePage(num) {
            this.request.paging.page = num;
        },

        updateFilters(filters) {
            this.request.filters = filters;
        },

        updateSearch(search) {
            this.request.search = search;
        },

        rateLimitRequestChange() {
            const now = +new Date();

            // if called within 1 second already
            if (now - this.lastUpdated > 1000) {
                this.lastUpdated = now;
                this.updateWaiting = false;
                this.$emit('requestChanged', this.request);
                console.log('request: ', this.request);
            } else {
                // see if waiting for an update
                // to limit to only one pending update
                if (!this.updateWaiting) {
                    // if not waiting, queue check 1 second later
                    setTimeout(this.rateLimitRequestChange, 1000);
                    this.updateWaiting = true;
                }
            }
        },
    },
    watch: {
        request: {
            deep: true,
            handler: function () {
                this.rateLimitRequestChange();
            },
        },
    },
    computed: {
        firstColWidth() {
            if (this.meta.length < 2) {
                // if there's only one column, expand to fill
                return 'auto';
            } else if (this.meta[0].type == 'image') {
                return '15rem';
            } else if (this.meta[0].type == 'id') {
                return '10rem';
            } else {
                return '25%';
            }
        },
    },
};
</script>
