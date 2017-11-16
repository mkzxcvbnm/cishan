import Vue from 'vue'
import { AjaxPlugin } from 'vux'
window.mk = new Vue()
const mk = {
    post(url, data = {}) {
        return AjaxPlugin.$http({
            method: 'post',
            url: url,
            data: data
        })
    },
    get(url, data = {}) {
        return AjaxPlugin.$http({
            method: 'get',
            url: url,
            params: data
        })
    },
    validation(arr) {
        let key = true
        arr.every((v, i, a) => {
            v.setTouched()
            v.validate()
            if (!v.valid) {
                key = false
                v.focus()
            }
            return v.valid
        })
        return key
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
