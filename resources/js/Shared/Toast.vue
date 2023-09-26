<template>
    <div
        class="toast"
        :class="{
            animate: !isVisible,
            'toast--success': type == 'success',
            'toast--error': type == 'error',
        }"
        ref="toast"
        v-if="!isHidden"
        @click="click"
    >
        <div class="toast__message" v-html="msg"></div>
        <p class="toast__dismiss f-title-1" @click="startAnimation">
            {{ dismissText ? $t(dismissText) : $t('Dismiss') }}
        </p>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';

export default {
    mounted() {
      let toast = this.$parent.$page.props.flash.message;
      if (toast?.type && toast?.msg) {
        this.type = toast.type;
        this.msg = toast.msg;
        this.show();
      }
    },
  watch: {
        '$parent.$page.flash.message': {
            deep: true,
            handler() {
                let toast = this.$parent.$page.props.flash.message;
                if (toast?.type && toast?.msg) {
                    this.type = toast.type;
                    this.msg = toast.msg;
                    this.show();
                }
            },
        },
    },
    props: {
        dismissText: String,
    },
    components: {
        Link,
    },
    data() {
        return {
            isVisible: false,
            isHidden: true,
            msg: '',
            type: '',
        };
    },
    methods: {
        startAnimation() {
            this.isVisible = false;
            this.$refs.toast?.addEventListener('transitionend', () => {
                this.hide();
            });
        },
        show() {
            this.isHidden = false;
            this.isVisible = true;
            let time =
                parseInt(this.$parent?.$page?.props?.flash?.message?.delay) ||
                5000;
            this.timer = setTimeout(this.startAnimation, time);
        },
        hide() {
            this.isHidden = true;
            this.$emit('toastEnded');
        },
    },
};
</script>
