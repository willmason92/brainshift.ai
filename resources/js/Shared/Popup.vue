<template>
    <div class="popup" :class="{ 'is-active': active }" :id="id">
        <div class="popup__inner">
            <div class="popup__controls">
                <slot name="backButton">
                    <button
                        type="button"
                        class="popup__back button-reset"
                        @click="pageBack()"
                    >
                        {{ $t('Back') }}
                    </button>
                </slot>
                <slot name="closeButton">
                    <button
                        type="button"
                        class="popup__close button-reset"
                        @click="closePopup()"
                    >
                        {{ $t('Close') }}
                    </button>
                </slot>
            </div>
            <slot name="default" :stageNumber="stageNumber"></slot>
        </div>
    </div>
</template>

<script>
export default {
    emits: ['activeState'],
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
    props: {
        active: Boolean,
        id: String,
    },
    data() {
        return {
            stageNumber: 0,
        };
    },
    methods: {
        closePopup() {
            this.$emit('activeState', false);
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
    },
};
</script>
