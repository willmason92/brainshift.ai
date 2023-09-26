<template>
    <layout-default>
        <div class="admin-back">
            <Link href="/admin" class="popup__back button-reset">
                {{ $t('Back to dashboard') }}
            </Link>
            <Link
                href="/admin/manage-blog/add"
                class="button button--fill button--thin admin-title__cta"
                type="button"
            >
                {{ $t('Add post') }}
            </Link>
        </div>
        <h1 class="admin-title">
            <span>{{ $t('Manage blog') }}</span>
        </h1>

        <!-- title only shows if filters hidden -->
        <AdminTable
            :meta="meta"
            :data="posts"
            :totalPages="9"
            :hideSearch="false"
            :hideFilters="false"
            :isEditable="true"
            :showEdit="true"
            :showDelete="true"
            title="Posts"
            :isLoading="!isLoaded"
            @cellClicked="cellClicked"
            @editRow="editRow"
            @deleteRow="deleteRow"
            @requestChanged="requestChanged"
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
                {{ $t('Are you sure you want to delete this post?') }}
            </h3>
            <button
                type="button"
                class="button button--fill button--span button--thin button-reset alert-box__cta"
                @click="deletePost()"
            >
                {{ $t('Delete post') }}
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
    name: 'ManageBlog',
    props: {
        shedSolutions: Array,
        meta: Array,
        data: Array,
    },
    components: {
        LayoutDefault,
        Link,
        AdminTable,
        AlertBox,
    },
    data() {
        return {
            posts: [],
            deletePopup: false,
            idToDelete: null,
            isLoaded: true,
        };
    },
    methods: {
        updateData() {
            this.data.forEach((post) => {
                let entry = [];
                entry.push(post.id);
                this.meta.forEach((column) => {
                    entry.push(_.get(post, column.column));
                });
                this.posts.push(entry);
            });
        },
        cellClicked(cell) {
            if (cell.column.id == 0 || cell.column.id == 1) {
                this.$inertia.visit(`/admin/manage-blog/${cell.row[0]}/view`);
            }
        },
        editRow(id) {
            this.$inertia.visit(`/admin/manage-blog/${id}/view`);
        },
        deleteRow(id) {
            this.idToDelete = id;
            this.deletePost();
        },
        deletePost() {
            if (!this.deletePopup) {
                this.openDeletePopup();
            } else if (this.idToDelete) {
                // inertia call here
                this.$inertia.post(
                    `/admin/manage-blog/${this.idToDelete}/delete`,
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

                console.log('deleting!');
                this.closePopup();
            }
        },
        closePopup() {
            this.deletePopup = false;
        },
        openDeletePopup() {
            this.deletePopup = true;
        },
        requestChanged(request) {
            // handle change of sort/page/search/filter here
        },
    },
};
</script>
