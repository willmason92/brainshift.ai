<template>
    <layout-default>
        <a href="" class="popup__back button-reset admin-back-button">
            Back to overview
        </a>
        <div class="admin-title">
            <h1>
                {{ title }}
            </h1>
            <slot name="button"></slot>
        </div>
        <div class="">
            <slot></slot>
        </div>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../../Layout/Admin.vue';

export default {
    components: {
        LayoutDefault,
        Link,
    },
    props: {
        title: String,
        hasChanged: Boolean,
    },
};
</script>
