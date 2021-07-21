
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import VueRouter from 'vue-router'

Vue.use(VueRouter)
Vue.use(Vuetify)

Vue.component('trax-root', require('./components/TraxRoot.vue'));
Vue.component('trips-view', require('./components/partials/TripsView.vue'));
Vue.component('cars-view', require('./components/partials/CarsView.vue'));
Vue.component('car-view', require('./components/partials/CarView.vue'));
Vue.component('new-car-view', require('./components/partials/NewCarView.vue'));
Vue.component('new-trip-view', require('./components/partials/NewTripView.vue'));


// Create Router

require ('./routing-config');
const router = new VueRouter({
    routes: routes(Vue) // short for `routes: routes`
})

const app = new Vue({
    router,
    el: '#app'
});
