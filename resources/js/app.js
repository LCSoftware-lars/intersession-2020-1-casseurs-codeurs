import "./bootstrap"
import Vue from "vue"
import vuetify from "@/js/plugins/vuetify"
import Vuex from 'vuex'
// import VueRouter from 'vue-router'
// import router from './router/index'
// import store from './store/index'

//Route information router
import Route from "@/js/routes.js"

//Component file
import App from "@/js/views/App"

// Store file
import store from "./stores/store"

const app = new Vue({
    el: "#app",
    vuetify,
    store,
    router: Route,
    render: h => h(App)
})