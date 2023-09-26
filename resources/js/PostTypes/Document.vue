<template>
    <div class="document card">
        <a target="_blank" :href="post.document.url" class="button button-reset">
            {{ $t('Download Document') }}
        </a>
    </div>
</template>

<script>
    export default {
        props: {
            post: Object,
        }
    }
</script>
