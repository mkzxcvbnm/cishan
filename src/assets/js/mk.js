import Vue from 'vue'
import { AjaxPlugin } from 'vux'
import { mapState, mapActions } from 'vuex'
const mk = {
    api: process.env.NODE_ENV === 'development' ? '' : '//api.jiujiu99.net/',
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
    },
    wxPay(data, callback) {
        const jsApiCall = () => {
            window.WeixinJSBridge.invoke('getBrandWCPayRequest', {
                appId: data.appId,
                nonceStr: data.nonceStr,
                package: data.package,
                paySign: data.paySign,
                signType: data.signType,
                timeStamp: data.timeStamp
            }, res => {
                callback(res)
            })
        }
        if (typeof window.WeixinJSBridge === 'undefined') {
            if (document.addEventListener) {
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false)
            } else if (document.attachEvent) {
                document.attachEvent('WeixinJSBridgeReady', jsApiCall)
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall)
            }
        } else {
            jsApiCall(data)
        }
    },
    wxConfig(data, callback) {
        Vue.wechat.config({
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: data.appId, // 必填，公众号的唯一标识
            timestamp: data.timeStamp, // 必填，生成签名的时间戳
            nonceStr: data.nonceStr, // 必填，生成签名的随机串
            signature: data.signature, // 必填，签名，见附录1
            jsApiList: ['chooseWXPay'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        })
        Vue.wechat.ready(function() {
            callback()
        })
        Vue.wechat.error(function(res) {
            // console.log('wxConfig_error: ' + res)
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
            computed: {
                ...mapState([
                    'data',
                    'user'
                ])
            },
            methods: {
                ...mapActions([
                    'Data',
                    'PushData',
                    'User'
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
