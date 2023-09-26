<template>
    <div v-if="experts.length" class="expert-summary container" v-for="(expert, index) in experts">
        <div class="expert-summary-info f-body-2">
            <span v-if="expert.image" class="expert-summary__image">
                <img :src="expert.image" :alt="expert.company_name" />
            </span>
            <span class="expert-summary__name f-body-2">
                {{ expert.company_name }}
            </span>
        </div>

        <div
            class="expert-summary-info f-body-2"
            v-if="hasExpertLocation(expert)"
        >
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
                {{ expertLocation(expert) }}
            </span>
        </div>

        <div class="expert-summary__tags">
            <span
                class="tag expert-tag"
                v-for="tag in expert.specialities.concat(expert.sectors)"
            >
                {{ tag.name }}
            </span>
        </div>

        <div class="expert-summary__controls">
            <Link
                :href="'/find-an-expert/view/' + expert.id"
                class="button button--small button--span"
            >
                {{ $t('More') }}
            </Link>
            <Link
                v-if="expert.expert_type_id == 1"
                :href="'/find-an-expert/view/' + expert.id"
                class="button button--small button--span button--fill"
                :data="{ contact: true }"
            >
                {{ $t('Contact') }}
            </Link>
            <button
                v-else
                class="button button--small button--span button--fill"
                @click="contactOption(expert)"
            >
                {{ $t('Contact') }}
            </button>
        </div>
    </div>
    <div v-else class="card">
        <div class="rich-text">
            <h2 class="h3">
                {{ $t('No results') }}
            </h2>
            <p>
                {{ $t('Unfortunately, there are no results within your area currently. However, we would love to try and help you at Eternit.') }}
            </p>
            <p>
                {{ $t('Please contact the') }} <a :href="contactUrl" target="_blank">{{ $t('Eternit Sale Team') }}</a>
            </p>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { usePage } from '@inertiajs/inertia-vue3'
import LayoutDefault from '../../Layout/Base.vue';
import Contact from './Popup/Contact.vue';

export default {
    components: {
        LayoutDefault,
        Link,
        Contact,
    },
    props: {
        experts: Object,
        expertType: Object,
        sectorOptions: Array,
        currentProjects: Array,
    },

    data() {
        return {
            popupContactOption: false,
            _expert: {},
            contactUrl: usePage().props.value.company.contact.url,
        };
    },

    methods: {
        contactOption(expert) {
            if (this.expertType.name === 'Installers') {
                this.popupContactOption = true;
                this._expert = expert;
                return;
            }

            let url = expert.expert_url;
            if (!/^https|https?:\/\//i.test(url)) {
                url = 'http://' + url;
            }
            window.open(url);
        },

        closePopup() {
            this.popupContactOption = false;
        },

        expertLocation(expert) {
            const street =
                expert?.location?.street ||
                expert?.user?.location?.street ||
                '';
            const postcode =
                expert?.location?.postcode ||
                expert?.user?.location?.postcode ||
                '';
            const country =
                expert?.location?.country ||
                expert?.user?.location?.country ||
                '';
            return `${street}, ${postcode},  ${country}`;
        },

        hasExpertLocation(expert) {
            return expert?.location || expert?.user?.location || false;
        },
    },
};
</script>
