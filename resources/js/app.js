import './bootstrap';
import '../css/app.css';

import { createSSRApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { createPinia } from "pinia";

import MainLayout from '@/Layouts/MainLayout.vue';

// const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Интернет-магазин Вертикаль';
const defaultTitle = 'Интернет-магазин Вертикаль';

createInertiaApp({
    title: (title) => title || defaultTitle,
    resolve: (name) => {
        const page = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue"),
        );

        page.then((module) => {
            module.default.layout ??= MainLayout;
        });

        return page;
    },
    setup({ el, App, props, plugin }) {
        return createSSRApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(createPinia())
            .component("Link", Link)
            .component("Head", Head)
            .mount(el);
    },
    progress: {
        color: 'var(--color-ui-accent)',
    },
});
