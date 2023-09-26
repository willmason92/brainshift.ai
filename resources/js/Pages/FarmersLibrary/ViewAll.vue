<template>
    <div v-for="group of typePosts" :key="group">
        <h2 class="farmers-library__type-title h3">
            {{ group.category.name }}
        </h2>
        <ArticleList :posts="group.posts" />
        <Link
            :href="'/farmers-library/list/?type=' + group.category.id"
            class="button button--full button--no-bg"
        >
            {{ $t('More') }} {{ group.category.name }}
        </Link>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import ArticleList from '../../Shared/ArticleList.vue';

export default {
    components: {
        Link,
        ArticleList,
    },
    props: {
        typePosts: Array,
    },
    mounted() {
        localStorage.farmersUrl = window.location.pathname + window.location.search
    }
};
</script>
