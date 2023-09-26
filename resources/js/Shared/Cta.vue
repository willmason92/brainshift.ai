<template>
    <div class="cta">
        <slot></slot>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
        Link,
    },
    props: {
        expert: Object,
    },
    data() {
        return {};
    },
};
</script>
