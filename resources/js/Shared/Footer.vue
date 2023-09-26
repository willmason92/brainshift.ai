<template>
    <div class="footer">
        <button @click="scrollTop" class="footer__to-top button-reset">
            {{ $t('Go to top') }}
        </button>
        <div class="wrap footer__grid">
            <div class="footer__grid-info">
                <h2 class="f-sans">{{ $t('Our info') }}</h2>
                <p v-if="companyName">{{ companyName }}</p>
                <p v-if="companyAddress1">{{ companyAddress1 }}</p>
                <p v-if="companyCity">{{ companyCity }}</p>
                <p v-if="companyCounty">{{ companyCounty }}</p>
                <p v-if="companyPostcode">{{ companyPostcode }}</p>
                <p v-if="companyVatNumber">VAT: {{ companyVatNumber }}</p>
            </div>
            <div class="footer__grid-buttons">
                <h2 class="footer__second-title f-sans">
                    {{ $t('Get in touch with us') }}
                </h2>
                <p v-if="contactUrl">
                    <a
                        :href="contactUrl"
                        target="_blank"
                        class="button button--thin footer-button--question"
                        >{{ $t('Ask us a question') }}</a
                    >
                </p>
                <p v-if="contactPhone">
                    <a
                        :href="`tel:${contactPhone}`"
                        class="button button--thin footer-button--phone"
                        >{{ contactPhone }}</a
                    >
                </p>
            </div>
            <div class="footer__copyright">
                <p v-if="companyGroup">{{ companyGroup }}</p>
                <p>
                    {{ $t('All rights reserved', [new Date().getFullYear()]) }}
                </p>
                <p>
                    <Link href="/privacy-policy">{{
                        $t('Privacy Policy')
                    }}</Link>
                </p>
                <p>
                    <Link href="/cookie-policy">{{ $t('Cookie Policy') }}</Link>
                </p>
                <p>
                    <Link href="/terms-and-conditions">{{
                        $t('Terms and Conditions')
                    }}</Link>
                </p>
            </div>
        </div>
        <a
            href="https://www.etexgroup.com/en-gb/"
            target="_blank"
            class="footer__logo-container"
        >
            <img
                class="footer__logo"
                src="../../images/footer-logo.svg"
                :alt="$t('footerAlt')"
            />
        </a>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { usePage } from '@inertiajs/inertia-vue3';

export default {
    components: { Link },
    data: function () {
        return {
            companyName: usePage().props.value.company.name,
            companyGroup: usePage().props.value.company.group,
            companyAddress1: usePage().props.value.company.contact.address1,
            companyCity: usePage().props.value.company.contact.city,
            companyCounty: usePage().props.value.company.contact.county,
            companyPostcode: usePage().props.value.company.contact.postcode,
            contactUrl: usePage().props.value.company.contact.url,
            contactPhone: usePage().props.value.company.contact.phone,
            companyVatNumber: usePage().props.value.company.vat_number,
        };
    },
    methods: {
        scrollTop() {
            document.querySelector('html').classList.add('smooth-scroll')
            setTimeout(() => {
                document.body.scrollTop = document.documentElement.scrollTop = 0;
            }, 1)
            setTimeout(() => {
                document.querySelector('html').classList.remove('smooth-scroll')
            }, 400)
        },
    },
};
</script>
