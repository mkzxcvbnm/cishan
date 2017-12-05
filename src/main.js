// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import router from '@/router'
import store from '@/store'
import FastClick from 'fastclick'
import App from './App'
import { AjaxPlugin, AlertPlugin, ConfirmPlugin, ToastPlugin, WechatPlugin } from 'vux'
import 'font-awesome/css/font-awesome.css'
import mk from 'js/mk'
import axios from 'axios'

axios.defaults.withCredentials = true

AjaxPlugin.$http.interceptors.request.use(config => {
    return config
}, error => {
    return Promise.reject(error)
})

AjaxPlugin.$http.interceptors.response.use(response => {
    return response
}, error => {
    return Promise.reject(error)
})

Vue.use(AjaxPlugin)
Vue.use(AlertPlugin)
Vue.use(ConfirmPlugin)
Vue.use(ToastPlugin)
Vue.use(WechatPlugin)
Vue.use(mk)

window.addEventListener('popstate', function(e) {
    store.commit('ISBACK', true)
}, false);

router.beforeEach((to, from, next) => {
    store.commit('ISLOADING', true)
    next()
})

router.afterEach((to) => {
    if (store.state.user.openid) {
        store.commit('ISLOADING', false)
    }
})

FastClick.attach(document.body)

require('es6-promise').polyfill()

Vue.config.productionTip = false

// console.log(Vue.wechat.config)

/* eslint-disable no-new */
new Vue({
    store,
    router,
    render: h => h(App)
}).$mount('#app')
