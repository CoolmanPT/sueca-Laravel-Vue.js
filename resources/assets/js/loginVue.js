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

var isUserLoggedIn = {
    data: {
        userName: '',
    },
    methods: {
        isLoggedIn(isLogged, redirectTo){
            console.log(axios.defaults.headers.common['Authorization'])
            if(axios.defaults.headers.common['Authorization'] == null){
                if(!isLogged) {
                    window.location.href = '/';
                }
            } else {
                axios.get('/api/user')
                    .then((response) => {
                        if(isLogged){
                            if(response.data.admin == 1){
                                window.location.href = '/admin'
                            } else if (response.data.admin == 0) {
                                window.location.href = '/game'
                            }
                        } else {
                            this.userName = response.data.name;
                            console.log(response.data.name);
                        }
                    })
                    .catch((error) => {
                        if(!isLogged) {
                            window.location.href = '/';
                        }
                    });
            }
        }
    }
}

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/*COMPONENTs*/
const LoginPage = Vue.component('login-page-component', require('./components/loginComponents/loginPageComponent.vue'));
const RecoverPasswordForm = Vue.component('recover-password-form-component', require('./components/loginComponents/RecoverPasswordFormComponent.vue'));
const NewPasswordForm= Vue.component('new-password-form-component', require('./components/loginComponents/NewPasswordFormComponent.vue'));
const RegisterForm = Vue.component('register-form-component', require('./components/loginComponents/RegisterFormComponent.vue'));
const ActivateAccountForm = Vue.component('activation-form-component', require('./components/loginComponents/ActivateAccountFormComponent.vue'));
Vue.component('statistic-component', require('./components/loginComponents/PublicStatisticsComponent.vue'));
Vue.component('login-form-component', require('./components/loginComponents/loginFormComponent.vue'));

/*ROUTES*/
const routes = [
    { path: '/', redirect: '/login' },
    { path: '/login', component: LoginPage },
    { path: '/register', component: RegisterForm },
    { path: '/password/reset', component: RecoverPasswordForm },
    { path: '/password/reset/:token/:user', component: NewPasswordForm, props: true  },
    { path: '/activation/:token', component: ActivateAccountForm, props: true  },
];

const router = new VueRouter({
    routes:routes
});

new Vue({
    router,
    mixins: [isUserLoggedIn],
    created: function () {
        this.isLoggedIn(true);
    }
}).$mount('#login_app');
