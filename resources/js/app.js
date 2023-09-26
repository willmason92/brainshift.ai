import { createI18n } from 'vue-i18n';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import VueGoogleMaps from '@fawmi/vue-google-maps';
import { Inertia } from '@inertiajs/inertia'

if (typeof dataLayer !== 'undefined') {
    Inertia.on('navigate', (event) => {
        dataLayer.push({
            'url': event.detail.page.url,
            'event': 'pageview'
        })
    })
}

function loadLocaleMessages(locale, defaultLocale) {
    var messages = {};
    messages[defaultLocale] = require('../../lang/' + defaultLocale + '.json');
    try {
        messages[locale] = require('../../lang/' + locale + '.json');
    } catch (ex) {
        messages[locale] = {};
    }
    return messages;
}

createInertiaApp({
    resolve: (name) => require(`./Pages/${name}`),
    setup({ el, App, props, plugin }) {
        const i18n = createI18n({
            locale: props.initialPage.props.locale,
            fallbackLocale: props.initialPage.props.fallbackLocale,
            messages: loadLocaleMessages(
                props.initialPage.props.locale,
                props.initialPage.props.fallbackLocale
            ),
        });

        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .use(VueGoogleMaps, {
                load: {
                    key: props.initialPage.props.gMapsApiKey,
                    libraries: 'places',
                },
            })
            .mount(el);
    },
});

/* load app js lang file */

const toggle = document.querySelector('.toggle');
const menu = document.querySelector('.menu');
const items = document.querySelectorAll('.item');

/* Toggle mobile menu */
function toggleMenu() {
    if (menu.classList.contains('active')) {
        menu.classList.remove('active');
        toggle.querySelector('a').innerHTML = "<i class='fas fa-bars'></i>";
    } else {
        menu.classList.add('active');
        toggle.querySelector('a').innerHTML = "<i class='fas fa-times'></i>";
    }
}

/* Activate Submenu */
function toggleItem() {
    if (this.classList.contains('submenu-active')) {
        this.classList.remove('submenu-active');
    } else if (menu.querySelector('.submenu-active')) {
        menu.querySelector('.submenu-active').classList.remove(
            'submenu-active'
        );
        this.classList.add('submenu-active');
    } else {
        this.classList.add('submenu-active');
    }
}

/* Close Submenu From Anywhere */
function closeSubmenu(e) {
    if (menu.querySelector('.submenu-active')) {
        let isClickInside = menu
            .querySelector('.submenu-active')
            .contains(e.target);

        if (!isClickInside && menu.querySelector('.submenu-active')) {
            menu.querySelector('.submenu-active').classList.remove(
                'submenu-active'
            );
        }
    }
}
