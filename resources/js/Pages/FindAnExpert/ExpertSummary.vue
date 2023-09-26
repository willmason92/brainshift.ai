<template>
    <div class="expert-summary">
        <div class="expert-summary-info f-body-2">
            <span v-if="expert.installer.image" class="expert-summary__image">
                <img :src="expert.installer.image" alt="" />
            </span>
            <span class="expert-summary__name f-body-2">{{
                expert.installer.company_name
            }}</span>
        </div>

        <div class="expert-summary-info f-body-2">
            <span class="expert-location-icon"
                ><svg
                    width="17"
                    height="20"
                    viewBox="0 0 17 20"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M14.5318 2.24753C11.4076 -0.749177 6.34236 -0.749177 3.21819 2.24753C0.341753 5.0066 0.0818875 9.39694 2.61426 12.4506L8.875 20L15.1357 12.4506C17.6681 9.39694 17.4082 5.00661 14.5318 2.24753ZM8.875 10C10.3477 10 11.5417 8.80612 11.5417 7.33331C11.5417 5.86057 10.3477 4.66667 8.875 4.66667C7.40225 4.66667 6.20833 5.86057 6.20833 7.33331C6.20833 8.80612 7.40225 10 8.875 10Z"
                        fill="#E60000"
                    />
                </svg>
            </span>
            <span class="expert-summary__location">
                {{ expert.user.location.street }},
                {{ expert.user.location.postcodeq }}
            </span>
        </div>

        <!--        <div class="expert-summary__tags">-->
        <!--            <span class="tag expert-tag" v-for="tag in expert.tags">-->
        <!--                {{ tag }}-->
        <!--            </span>-->
        <!--        </div>-->

        <div class="expert-summary__controls">
            <a
                :href="expert.moreInfoLink"
                class="button button--small button--span"
                >{{ $t('More') }}</a
            >
            <a
                :href="expert.contactLink"
                class="button button--small button--span button--fill"
                >{{ $t('Contact') }}</a
            >
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../Layout/Base.vue';

export default {
    components: {
        LayoutDefault,
        Link,
    },
    props: {
        expert: Object,
    },
};
</script>
