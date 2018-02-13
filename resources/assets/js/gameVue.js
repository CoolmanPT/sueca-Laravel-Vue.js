/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Popper = require('popper.js').default;
require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';

import VueSocketio from 'vue-socket.io';
Vue.use(VueSocketio, 'http://localhost:8080');
//Vue.use(VueSocketio, 'http://46.101.93.84:8080');

Vue.use(VueRouter);

window.Vue = require('vue');

axios.defaults.headers.common['Accept'] = "application/json";
axios.defaults.headers.common['Authorization'] = localStorage.getItem('access_token');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/*COMPONENTs*/
const Games = Vue.component('login-page-component', require('./components/gameComponents/GamesComponent.vue'));
Vue.component('game-lobby', require('./components/gameComponents/lobbyComponent.vue'));
Vue.component('navbar-component-game', require('./components/gameComponents/navbarComponent.vue'));
Vue.component('sidebar-component-game', require('./components/gameComponents/sidebarComponent.vue'));
Vue.component('game', require('./components/gameComponents/game.vue'));



/*ROUTES*/
const routes = [
    { path: '/', redirect: '/lobby' },
    { path: '/lobby', component: Games },

];

const router = new VueRouter({
    routes:routes
});

new Vue({
    router,
}).$mount('#game_app');
