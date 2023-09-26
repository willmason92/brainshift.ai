<template>
    <div class="collapsed-box card" :class="{ active: isOpen }">
        <div class="collapsed-box__title" @click="toggleBox">
            <h3 class="collapsed-box-title">{{ title }}</h3>
            <svg
                ref="arrow"
                class="collapsed-box-arrow"
                width="18"
                height="12"
                viewBox="0 0 18 12"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M0.0901605 9.94988L1.80016 11.6299L8.94016 4.33988L16.2302 11.4799L17.9102 9.73988L8.88016 0.949882L0.0901605 9.94988Z"
                    fill="#E60000"
                />
            </svg>
        </div>
        <div
            class="collapsed-box__content"
            ref="content"
            :style="{ height: height, transitionDuration: transitionDuration }"
        >
            <div class="collapsed-box__content-wrapper">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {
        title: String,
        expanded: Boolean,
    },
    data() {
        return {
            isOpen: false,
            height: 0,
            contentHeight: 0,
            transitionDuration: 0,
        };
    },
    mounted() {
        this.contentHeight = this.$refs.content.scrollHeight;
        if (this.$props.expanded) {
            this.toggleBox(false);
        }
    },
    methods: {
        toggleBox(loaded = true) {
            if (loaded) {
                this.transitionDuration = `${
                    0.3 + Math.floor((this.contentHeight / 120) * 0.3)
                }s`;
            }
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.height = `${this.contentHeight}px`;
            } else {
                this.height = 0;
            }
        },
    },
};
</script>
