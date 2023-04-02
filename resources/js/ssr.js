import { createSSRApp, h } from 'vue';
import { renderToString } from '@vue/server-renderer';
import {createInertiaApp, Head, Link} from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { createPinia } from "pinia";

import MainLayout from '@/Layouts/MainLayout.vue';

// const appName = 'Laravel';
const defaultTitle = 'Интернет-магазин Вертикаль';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
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
        setup({ App, props, plugin }) {
            return createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(createPinia())
                .component("Link", Link)
                .component("Head", Head)
                .use(ZiggyVue, {
                    ...page.props.ziggy,
                    location: new URL(page.props.ziggy.location),
                });
        },
    })
);
