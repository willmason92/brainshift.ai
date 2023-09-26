<template>
    <layout-default>
        <div class="farmers-filters__content">
            <p class="farmers-filters__title">
                {{ $t('Your ideal shed solutions') }}
            </p>

            {{ dimensions }}

            <Link
                href="/shed-solution/render"
                class="button button--fill button--span"
            >
                {{ $t('Save Project') }}
            </Link>

            <Link
                href="/shed-solution/"
                class="button button--no-bg button--span"
            >
                {{ $t('Start new configuration') }}
            </Link>
        </div>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../../Layout/Base.vue';

export default {
    components: {
        LayoutDefault,
        Link,
    },
    data() {
        return {
            // Simulated data
            dimensions: [{ length: 100, width: 100, type: 0 }],
        };
    },
};
</script>
