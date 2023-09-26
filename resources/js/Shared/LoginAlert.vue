<template>
    <div class="popup-overlay" v-if="active"></div>
    <div
        class="alert-box login-alert"
        v-if="active"
        :class="{ 'is-active': active }"
    >
        <div class="alert-box__inner">
            <div class="login-alert__controls">
                <img
                    class="login-alert__icon"
                    src="../../images/alert-outline.svg"
                    alt=""
                    width="60"
                    height="60"
                />
                <h3 class="alert-box__title">
                    {{ $t('Please login or register to continue.') }}
                </h3>
                <button
                    type="button"
                    class="alert-box__close button-reset"
                    @click="closePopup()"
                ></button>
            </div>
            <div class="alert-box__content">
                <p>
                    {{ $t('If you do not have an account with the') }}
                    <strong>Etex Group</strong>
                    {{
                        $t(
                            "you can create an account by clicking 'Login or register' below."
                        )
                    }}
                </p>
                <a
                    href="/login"
                    type="button"
                    class="button button--fill button--thin button--span login-alert-button"
                >
                    {{ $t('Login or register') }}
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    emits: ['activeState'],
    props: {
        active: Boolean,
    },
    methods: {
        closePopup() {
            this.$emit('activeState', false);
        },
        pageBack() {
            this.closePopup();
        },
    },
};
</script>
