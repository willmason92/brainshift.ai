<template>
    <div class="main-container" id="container">
        <Toast></Toast>
        <Nav />
        <div class="main" :style="{ backgroundColor: bgColor }">
            <div class="wrap">
                <slot />
            </div>
        </div>
        <Footer v-if="!removeFooter" />
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import Nav from '../Shared/Nav';
import Footer from '../Shared/Footer';
import Toast from '../Shared/Toast.vue';

export default {
    mounted() {
        // remove popup scroll limitation
        document.body.classList.remove('popup-prevent-scroll');
        document.documentElement.classList.remove('popup-prevent-scroll');
    },
    components: {
        Nav,
        Footer,
        Link,
        Toast,
    },
    props: {
        bgColor: null,
        removeFooter: Boolean,
    },
};
</script>
