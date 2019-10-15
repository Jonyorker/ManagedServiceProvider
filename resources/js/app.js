import './bootstrap';
import Vue from 'vue';
import Vuetify from 'vuetify/lib';

// Route information for Vue Router
import Routes from '@/js/routes.js';

// Component File
import App from '@/js/views/App';

Vue.use(Vuetify);

const opts = {}

const app = new Vue({
    el: '#app',
    router: Routes,
    render: h => h(App),
    vuetify : new Vuetify(opts)
})

export default app;
