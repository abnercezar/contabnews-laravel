import "../css/app.css";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { createPinia } from "pinia";
import { ZiggyVue } from "ziggy-js";
import AppLayout from "./Layouts/AppLayout.vue";

const appName = import.meta.env.VITE_APP_NAME || "Laravel TabNews";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob("./pages/**/*.vue")
        ).then((module) => {
            // Aplica layout padrão se a página não definir um
            module.default.layout = module.default.layout || AppLayout;
            return module;
        }),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        const pinia = createPinia();
        app.use(pinia).use(plugin).use(ZiggyVue).mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
