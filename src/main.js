// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import router from '@/router'
import store from '@/store'
// import FastClick from 'fastclick'
import App from './App'
import { AjaxPlugin, AlertPlugin, ConfirmPlugin, ToastPlugin } from 'vux'
import 'font-awesome/css/font-awesome.css'
import mk from 'js/mk'

require('js/transition')

AjaxPlugin.$http.interceptors.request.use(config => {
    return config;
}, error => {
    return Promise.reject(error);
});

AjaxPlugin.$http.interceptors.response.use(response => {
    return response;
}, error => {
    return Promise.reject(error);
});

router.beforeEach((to, from, next) => {
    store.dispatch('Data', {showBack: to.path !== '/', showFoot: false})
    next()
})

router.afterEach((to) => {

})

window.addEventListener('popstate', function(e) {
    store.commit('ISBACK', true)
}, false);

Vue.use(AjaxPlugin)
Vue.use(AlertPlugin)
Vue.use(ConfirmPlugin)
Vue.use(ToastPlugin)
Vue.use(mk)

// FastClick.attach(document.body)

require('es6-promise').polyfill()

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
    store,
    router,
    render: h => h(App)
}).$mount('#app')
