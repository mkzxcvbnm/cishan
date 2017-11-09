import Vue from 'vue'
import { AjaxPlugin } from 'vux'
window.mk = new Vue()
const mk = {
    post(url, data = {}) {
        return AjaxPlugin.$http({
            method: 'post',
            url: url,
            data: data
        }).catch(error => {
            console.log('axios-post-error ' + error);
        })
    },
    get(url) {
        return AjaxPlugin.$http({
            method: 'get',
            url: url
        }).catch(error => {
            console.log('axios-post-error ' + error);
        })
    }
}
export default {
    install(Vue, options) {
        // 1. 添加全局方法或属性
        Vue.myGlobalMethod = function () {
        }
        // 2. 添加全局资源
        Vue.directive('mk-directive', {
            bind (el, binding, vnode, oldVnode) {
            // 逻辑...
            }
        })
        // 3. 注入组件
        Vue.mixin({
            created: function () {
            // 逻辑...
            }
        })
        // 4. 添加实例方法
        for (let k in mk) {
            Vue.prototype[k] = mk[k]
        }
    }
}
