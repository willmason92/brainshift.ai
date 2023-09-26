<template>
    <a
        target="_blank"
        :href="url"
        class="button button-reset"
    >
        {{ $t('External Link') }}
    </a>
</template>

<script>
export default {
    props: {
        url: String,
    },
};
</script>
