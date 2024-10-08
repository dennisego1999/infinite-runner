import '../scss/app.scss';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => {
        const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

        if (!title) {
            return appName;
        }

        return `${title} - ${appName}`;
    },
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({
            created() {
                router.on('invalid', (event) => {
                    // Do not prevent the default behavior while developing
                    if (process.env.NODE_ENV !== 'production') {
                        return;
                    }

                    // Do not prevent the handler for server errors or validation checks
                    if (event.detail.response.status === 500 || event.detail.response.status === 403) {
                        return;
                    }

                    // Prevent a white modal
                    event.preventDefault();

                    // Log the invalid response info
                    console.error('An invalid Inertia response was received.');
                    console.error(event.detail.response);
                });
            },

            mounted() {
                // Remove the props to initialize Vue
                document.getElementById('app').removeAttribute('data-page');
            },

            render: () => h(App, props)
        })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
