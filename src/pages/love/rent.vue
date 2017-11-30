<template>
    <div class="chairPay">
        <div class="interval"></div>
        <div class="receive_obj bf border_b">
            <span class="tit">领取对象</span>
            <p>1.持有丹阳农保卡的农村居民；</p>
            <p>2.持有丹阳医保卡，生活有一定困难的城市居民；</p>
            <p>3.暂住在丹阳，生活有一定困难的外来务工居民；</p>
            <p>4.其他需要帮助的人群。</p>
        </div>
        <group>
            <cell title="订单详情"></cell>
            <cell title="商品价格">
                <span class="color">¥{{money}}</span>
            </cell>
        </group>
        <div class="no_bg_check">
            <checklist required :options="[ '您已同意' + love_type[type] + '申请协议' ]" ref="check" v-model="agree"></checklist>
            <a class="check_btn" href="javascript:;">查看协议</a>
        </div>
        <div class="center mt20">
            <x-button text="微信支付" :disabled="!(money > 0)" @click.native="pay" type="primary"></x-button>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex'
    import { Group, XButton, Cell, Checklist } from 'vux'
    export default {
        name: 'chairPay',
        components: {
            XButton,
            Group,
            Cell,
            Checklist
        },
        data() {
            return {
                money: null,
                agree: []
            }
        },
        computed: {
            ...mapState([
                'love_type'
            ]),
            type() {
                return this.$route.params.type
            }
        },
        methods: {
            // wxsq() {
            //     this.post(this.api + 'api/action/JsPay', {
            //         openid: this.user.openid,
            //         body: 'mk',
            //         total_fee: 1
            //     }).then(res => {
            //         if (res.data.code === '0') {
            //             let info = JSON.parse(res.data.data)
            //             this.wxPay(info, res => {
            //                 if (res.err_msg === 'get_brand_wcpay_request:ok') {
            //                     this.$vux.alert.show({
            //                         title: '成功',
            //                         content: '恭喜您，支付成功!更新可能会有延迟!',
            //                         onHide: function() {
            //                             this.$router.go(-1)
            //                         }.bind(this)
            //                     })
            //                 } else if (res.err_msg === 'get_brand_wcpay_request:cancel') {
            //                     this.$vux.alert.show({
            //                         title: '失败',
            //                         content: '取消支付'
            //                     })
            //                 } else {
            //                     this.$vux.alert.show({
            //                         title: '失败',
            //                         content: '支付失败' + res.err_msg
            //                     })
            //                 }
            //             })
            //             // this.wxConfig(info, info => {
            //             //     this.$wechat.chooseWXPay({
            //             //         timestamp: info.timeStamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
            //             //         nonceStr: info.nonceStr, // 支付签名随机串，不长于 32 位
            //             //         package: info.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
            //             //         signType: info.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
            //             //         paySign: info.paySign, // 支付签名
            //             //         success: function (res) {
            //             //             console.log(res)
            //             //         }
            //             //     })
            //             // })
            //         }
            //     }).catch(error => {
            //         console.log(error)
            //     })
            // },
            pay() {
                if (!this.agree.length) {
                    this.$vux.toast.show({
                        type: 'warn',
                        text: '需要同意协议'
                    })
                    return false
                }
                if (!this.user.mobile) {
                    this.$vux.confirm.show({
                        title: '通知',
                        content: '您还没有验证手机号，立即前往验证',
                        onConfirm: function() {
                            this.$store.commit('BACKURL', this.$route.fullPath)
                            this.$router.push({name: 'verifyPhone'})
                        }.bind(this)
                    })
                    return false
                }
                this.post(this.api + 'api/action/ApplyDz', {
                    openid: this.user.openid,
                    uid: this.user.uid,
                    name: this.user.nikename,
                    mobile: this.user.mobile,
                    type: this.type,
                    begin_time: Math.round(new Date().getTime() / 1000),
                    total_fee: this.money * 100
                }).then(res => {
                    if (res.data.code === '0') {
                        let info = JSON.parse(res.data.data)
                        this.wxPay(info, res => {
                            if (res.err_msg === 'get_brand_wcpay_request:ok') {
                                this.$vux.alert.show({
                                    title: '成功',
                                    content: '恭喜您，支付成功!更新可能会有延迟!',
                                    onHide: function() {
                                        this.$router.go(-1)
                                    }.bind(this)
                                })
                            } else if (res.err_msg === 'get_brand_wcpay_request:cancel') {
                                this.$vux.alert.show({
                                    title: '失败',
                                    content: '取消支付'
                                })
                            } else {
                                this.$vux.alert.show({
                                    title: '错误',
                                    content: '支付失败: ' + res.errMsg
                                })
                            }
                        })
                    } else {
                        this.$vux.alert.show({
                            title: '失败',
                            content: res.data.msg
                        })
                    }
                }).catch(error => {
                    this.$vux.alert.show({
                        title: '错误',
                        content: '请求错误'
                    })
                    console.log(error)
                })
            }
        },
        created() {
            this.Data({title: '爱心' + this.love_type[this.type] + '申请-支付押金'})
            this.get(this.api + 'api/index/Dzprice', {tid: this.type}).then(res => {
                let info = res.data.data
                this.$set(this, 'money', info.price)
            })
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
    .chairPay {
        color: #000;
    }
    .receive_obj {
        padding: 13px 4%;
        .tit {
            line-height: 1.2;
            display: block;
            padding-bottom: 10px;
        }
    }
</style>
