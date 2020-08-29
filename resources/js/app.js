import "./bootstrap"
import Vue from "vue"
import vuetify from "@/js/plugins/vuetify"
// import VueResource from "vue-resource"
import VeeValidate from 'vee-validate';
import 'es6-promise/auto'
import axios from 'axios'
import VueAuth from '@websanova/vue-auth'
import auth from '@websanova/vue-auth';
import VueAxios from 'vue-axios'
import VueRouter from 'vue-router'
import config from '@/auth'

//Route information router
import Route from "@/js/routes.js"
//Component file
import App from "@/js/views/App"
// Store file
import store from "./stores/store"

// Set Vue router
Vue.router = Route
Vue.use(VueRouter)

// Set Vue globally
window.Vue = Vue

axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api/`
Vue.use(VueAxios, axios)

// Set Vue authentication
Vue.use(auth, config)

// Vue.use(VueResource)
// Vue.http.options.root = "http://localhost:8000/api";

Vue.use(VeeValidate);

const app = new Vue({
    el: "#app",
    vuetify,
    store,
    router: Route,
    render: h => h(App)
}).$mount('#app')

