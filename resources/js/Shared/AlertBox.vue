<template>
    <div v-show="active" class="popup-overlay"></div>
    <div
        v-show="active"
        class="alert-box"
        :class="{ 'is-active': active, wrapperClass: wrapperClass }"
        @click="overlayClicked($event)"
    >
        <div
            class="alert-box__inner"
            :style="`min-height: ${minHeight || 0}vh;
                     min-width: ${minWidth || 0}vw`"
        >
            <div class="alert-box__controls">
                <h3 class="alert-box__title" v-if="title">{{ title }}</h3>
                <button
                    type="button"
                    class="alert-box__close button-reset"
                    @click="closePopup()"
                >
                    {{ $t('Close') }}
                </button>
            </div>
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    emits: ['activeState'],
    props: {
        active: Boolean,
        title: String,
        wrapperClass: String,
        minHeight: Number,
        minWidth: Number,
    },
    data() {
        return {
            stageNumber: 0,
        };
    },
    methods: {
        closePopup() {
            this.$emit('activeState', false);
            document.documentElement.classList.remove('popup-prevent-scroll');
            document.body.classList.remove('popup-prevent-scroll');
        },
        pageBack() {
            console.log(this.stageNumber);
            if (this.stageNumber === 0) {
                this.closePopup();
            } else {
                this.stageNumber--;
            }
        },
        stageChanged(page) {
            this.stageNumber = page;
        },
        overlayClicked(e) {
            console.log('overlay clicked!');
            if (!e.target.closest('.alert-box__inner')) {
                this.closePopup();
            }
        },
    },
    watch: {
        active: {
            handler: (newValue) => {
                if (newValue) {
                    document.body.classList.add('popup-prevent-scroll');
                    document.documentElement.classList.add(
                        'popup-prevent-scroll'
                    );
                } else {
                    document.body.classList.remove('popup-prevent-scroll');
                    document.documentElement.classList.remove(
                        'popup-prevent-scroll'
                    );
                }
            },
        },
    },
};
</script>
