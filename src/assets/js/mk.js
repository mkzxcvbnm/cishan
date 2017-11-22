import Vue from 'vue'
import { AjaxPlugin } from 'vux'
import { mapState, mapActions } from 'vuex'
const mk = {
    env: process.env.NODE_ENV,
    api: this.env === 'production' ? '//api.jiujiu99.net/' : '',
    transfer: new Vue(),
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
    getCookie(name) {
        var reg = new RegExp('(^| )' + name + '=([^;]*)(;|$)');
        var arr = document.cookie.match(reg);
        if (arr) {
            return (arr[2]);
        } else {
            return null;
        }
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
            computed: {
                ...mapState([
                    'data'
                ])
            },
            methods: {
                ...mapActions([
                    'Data',
                    'PushData'
                ])
            },
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
