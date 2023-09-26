<template>
    <layout-default>
        <div class="farmers-filters-header">
            <Link href="/" class="popup__back button-reset">
                {{ $t('Home') }}
            </Link>
        </div>
        <div class="rich-text" v-html="content"></div>
    </layout-default>
</template>

<script>

    import { Link } from '@inertiajs/inertia-vue3'
    import LayoutDefault from '../Layout/Base.vue'

    export default {
        components: {
            LayoutDefault,
            Link,
        },
        props: {
            content: String,
        },
    };
</script>
