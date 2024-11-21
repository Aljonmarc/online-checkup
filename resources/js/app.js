import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import FontAwesomeIcon from './fontawesome'; // import fontawesome config file
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import '@fortawesome/fontawesome-free/css/all.css';


import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const appName = 'S.H.S.I';

import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);
  

        app.component('font-awesome-icon', FontAwesomeIcon); // Register FontAwesome component here
        app.use(ElementPlus)
    
        app.mount(el);
        app.use(VueSweetalert2);
       
    },
    progress: {
        color: '#4B5563',
    },
});
