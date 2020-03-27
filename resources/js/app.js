import './bootstrap';

import router from './routes';

Vue.component('pagination', require('laravel-vue-pagination'));



new Vue({
    
    el: '#app',

    router
});
