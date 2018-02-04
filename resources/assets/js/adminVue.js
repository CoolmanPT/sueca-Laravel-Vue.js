/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */



window.Popper = require('popper.js').default;
require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import BootstrapVue from 'bootstrap-vue';
Vue.use(BootstrapVue);
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

//DASHBOARD
const dashboard = Vue.component('dashboard-component', require('./components/adminComponents/adminDashboard.vue'));
const settings = Vue.component('settings-component', require('./components/adminComponents/settingsComponent.vue'));
const users = Vue.component('users-component', require('./components/adminComponents/usersComponent.vue'));
const decks = Vue.component('decks-component', require('./components/adminComponents/decksComponent.vue'));
const newDeck = Vue.component('new-deck-component', require('./components/adminComponents/newDeckComponent.vue'));
const addCard = Vue.component('add-card-component', require('./components/adminComponents/addCardComponent.vue'));
const adminProfile = Vue.component('admin-profile-component', require('./components/adminComponents/adminProfile.vue'));
Vue.component('platform-email-settings-component', require('./components/adminComponents/platform-email_settingsComponent.vue'));
Vue.component('admin-change-email-component', require('./components/adminComponents/admin-change_emailComponent.vue'));
Vue.component('admin-change-password-component', require('./components/adminComponents/admin-change_passwordComponent.vue'));
Vue.component('navbar-component', require('./components/adminComponents/navbarComponent.vue'));
Vue.component('sidebar-component', require('./components/adminComponents/sidebarComponent.vue'));
Vue.component('counter-info-component', require('./components/adminComponents/counterInfoComponent.vue'));
Vue.component('admin-avatar-change-component', require('./components/adminComponents/adminAvatarChangeComponent.vue'));

/*ROUTES*/
const routes = [
    { path: '/', redirect: 'dashboard' },
    { path: '/dashboard', component: dashboard },
    { path: '/settings', component: settings },
    { path: '/profile', component: adminProfile },
    { path: '/users', component: users },
    { path: '/decks', component: decks },
    { path: '/newDeck', component: newDeck},
    { path: '/addCard:deck', component: addCard},

];

const router = new VueRouter({
    routes:routes
});

new Vue({
    router,

    methods: {

    }

}).$mount('#admin_app');

// ------------------------------------------------------- //
// Sidebar Functionality
// ------------------------------------------------------ //
$('#toggle-btn').on('click', function (e) {
    e.preventDefault();
    $(this).toggleClass('active');

    $('.side-navbar').toggleClass('shrinked');
    $('.content-inner').toggleClass('active');
    $(document).trigger('sidebarChanged');

    if ($(window).outerWidth() > 1183) {
        if ($('#toggle-btn').hasClass('active')) {
            $('.navbar-header .brand-small').hide();
            $('.navbar-header .brand-big').show();
        } else {
            $('.navbar-header .brand-small').show();
            $('.navbar-header .brand-big').hide();
        }
    }

    if ($(window).outerWidth() < 1183) {
        $('.navbar-header .brand-small').show();
    }
});
