import Vue from 'vue'
import { AjaxPlugin } from 'vux'
import { mapState, mapActions } from 'vuex'
const mk = {
    api: process.env.NODE_ENV === 'development' ? '' : '//api.jiujiu99.net/',
    transfer: new Vue(),
    axios(config, count) {
        return new Promise(function (resolve, reject) {
            let axios = (config, count) => {
                AjaxPlugin.$http(config).then(res => {
                    resolve(res)
                }).catch(error => {
                    count = count ? count + 1 : 1
                    if (count < 4) {
                        Vue.$vux.toast.show({
                            type: 'text',
                            text: '请求失败，尝试重试第' + count + '次',
                            position: 'bottom',
                            width: '14rem',
                            time: 3000,
                            onHide() {
                                axios(config, count)
                            }
                        })
                    } else {
                        Vue.$vux.toast.show({
                            type: 'text',
                            text: '请求失败',
                            position: 'bottom'
                        })
                        reject(error)
                    }
                })
            }
            axios(config)
        })
    },
    post(url, data = {}) {
        return mk.axios({
            method: 'post',
            url: url,
            data: data
        })
    },
    get(url, data = {}) {
        return mk.axios({
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
    getWxConfig() {
        return new Promise((resolve, reject) => {
            this.get(this.api + 'api/jssdk/getSignPackage', {request_url: window.location.href.split('#')[0]}).then(res => {
                let info = res.data.data
                resolve(info)
            }).catch(error => {
                reject(error)
            })
        })
    },
    wxConfig(config) {
        Vue.wechat.config({
            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: config.appId, // 必填，企业号的唯一标识，此处填写企业号corpid
            timestamp: config.timestamp, // 必填，生成签名的时间戳
            nonceStr: config.nonceStr, // 必填，生成签名的随机串
            signature: config.signature, // 必填，签名，见附录1
            jsApiList: [
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo',
                'onMenuShareQZone'
            ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        })
    },
    wxShare(info) {
        let publicInfo = {
            title: info.site_indextitle,
            imgUrl: 'http:' + info.site_indeximg
        }
        Vue.wechat.ready(() => {
            Vue.wechat.onMenuShareTimeline({
                ...publicInfo
            })
            Vue.wechat.onMenuShareAppMessage({
                ...publicInfo,
                desc: info.site_indexdesc
            })
            Vue.wechat.onMenuShareQQ({
                ...publicInfo,
                desc: info.site_indexdesc
            })
            Vue.wechat.onMenuShareWeibo({
                ...publicInfo,
                desc: info.site_indexdesc
            })
            Vue.wechat.onMenuShareQZone({
                ...publicInfo,
                desc: info.site_indexdesc
            })
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
