import Vue from 'vue'

import Index from './Index'
import store from './store'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

const router = new VueRouter({
    routes: []
});

new Vue({
    el: '#list',
    components: {
        Index
    },
    store,
    template: '<Index/>'
})