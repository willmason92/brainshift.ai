<template>
    <div class="admin-table-pagination-wrapper">
        <div class="admin-table-pagination">
            <div
                class="pagination-previous"
                :class="{ disabled: currentPage == 1 }"
                @click="changePage(currentPage - 1)"
            >
                Previous
            </div>
            <div
                v-if="currentPage > totalPages - 1 && totalPages > 4"
                class="pagination-number"
                @click="changePage(currentPage - 4)"
            >
                {{ currentPage - 4 }}
            </div>
            <div
                v-if="
                    (currentPage > totalPages - 2 && totalPages > 4) ||
                    (totalPages == 4 && currentPage == totalPages)
                "
                class="pagination-number"
                @click="changePage(currentPage - 3)"
            >
                {{ currentPage - 3 }}
            </div>
            <div
                v-if="
                    (currentPage > totalPages - 3 && totalPages > 4) ||
                    ((totalPages == 3 || totalPages == 4) && currentPage > 2)
                "
                class="pagination-number"
                @click="changePage(currentPage - 2)"
            >
                {{ currentPage - 2 }}
            </div>
            <div
                v-if="currentPage > 1"
                class="pagination-number"
                @click="changePage(currentPage - 1)"
            >
                {{ currentPage - 1 }}
            </div>
            <div class="pagination-number active">{{ currentPage }}</div>
            <div
                v-if="currentPage + 1 < totalPages"
                class="pagination-number"
                @click="changePage(currentPage + 1)"
            >
                {{ currentPage + 1 }}
            </div>
            <div
                v-if="
                    currentPage + 3 < totalPages &&
                    currentPage < 2 &&
                    totalPages > 4
                "
                class="pagination-number"
                @click="changePage(currentPage + 2)"
            >
                {{ currentPage + 2 }}
            </div>

            <div
                v-if="
                    currentPage < totalPages - 2 ||
                    (totalpages == 4 && currentPage < totalPages - 1)
                "
                class="pagination-number"
            >
                ...
            </div>
            <div
                v-if="currentPage != totalPages"
                class="pagination-number"
                @click="changePage(totalPages)"
            >
                {{ totalPages }}
            </div>
            <div
                class="pagination-next"
                :class="{ disabled: currentPage == totalPages }"
                @click="changePage(currentPage + 1)"
            >
                Next
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AdminTablePagination',
    props: {
        currentPage: Number,
        totalPages: Number,
    },
    emits: ['changePage'],
    data() {
        return {};
    },
    methods: {
        changePage(num) {
            if (num > 0 && num <= this.totalPages)
                this.$emit('changePage', num);
        },
    },
};
</script>
