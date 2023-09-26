<template>
    <layout-default>
        <div>
            <H1>{{ title }}</H1>
            <div>{{ description }}</div>
        </div>
    </layout-default>
</template>

<script>
    import LayoutDefault from '../Layout/Base.vue'
    import {Link} from "@inertiajs/inertia-vue3";

    export default {
        components: {
            LayoutDefault,
        },
        props: {
            status: Number,
        },
        computed: {
            title() {
                return {
                    503: '503: Service Unavailable',
                    500: '500: Server Error',
                    404: '404: Page Not Found',
                    403: '403: Forbidden',
                }[this.status]
            },
            description() {
                return {
                    503: $t('Sorry, we are doing some maintenance. Please check back soon.'),
                    500: $t('Whoops, something went wrong on our servers.'),
                    404: $t('Sorry, the page you are looking for could not be found.'),
                    403: $t('Sorry, you are forbidden from accessing this page.'),
                }[this.status]
            },
        },
    }
</script>