<template>
    <div class="admin-container" id="container">
        <Toast></Toast>
        <AdminNav />
        <div class="main">
            <div class="wrap wrap--admin">
                <slot />
            </div>
        </div>
        <!-- <Footer /> -->
    </div>
</template>

<script>
import AdminNav from '../Shared/Admin/Nav';
import Toast from '../Shared/Toast.vue';

export default {
    mounted() {
        // remove popup scroll limitation
        document.body.classList.remove('popup-prevent-scroll');
        document.documentElement.classList.remove('popup-prevent-scroll');
    },
    components: {
        AdminNav,
        Toast,
    },
};
</script>
