<template>
    <div class="zcPay">
        <div class="interval"></div>
        <checker
        class="box1 bf"
        v-model="money"
        default-item-class="money-item"
        selected-item-class="money-item-selected"
        >
            <checker-item v-for="i in quick_payment" :key="i" :value="i"><span>{{i}}</span>元</checker-item>
        </checker>
        <div class="interval"></div>
        <div class="box2 bf">
            <group class="group_interval">
                <x-input class="input_money" v-model="money" placeholder="请输入您的其他捐赠金额" :is-type="money_num" required></x-input>
            </group>
        </div>
        <div class="interval"></div>
        <div class="box3 bf">
            <x-textarea v-model="text" placeholder="请输入您的捐赠感言，如不输入则显示默认感言，如张三献出爱心88元"></x-textarea>
        </div>
        <div class="center mt30"><x-button text="微信支付" :disabled="disabled" @click.native="pay" type="primary"></x-button></div>
    </div>
</template>

<script>
    import { Checker, CheckerItem, XButton, Group, XInput, XTextarea } from 'vux'
    export default {
        name: 'zcPay',
        components: {
            Checker,
            CheckerItem,
            XButton,
            Group,
            XInput,
            XTextarea
        },
        data() {
            return {
                money: null,
                disabled: false,
                text: '',
                money_num(v) {
                    return {
                        valid: /(^\d*$)|(^\d+(\.\d{0,2})$)/.test(v),
                        msg: '请输入正确的金额'
                    }
                }
            }
        },
        computed: {
            quick_payment() {
                return this.$route.params.quick_payment.split('|')
            }
        },
        methods: {
            pay() {
                if (this.money_num(this.money)['valid'] && this.money > 0) {
                    this.post(this.api + 'api/action/jspay', {
                        openid: this.user.openid,
                        body: '众筹捐款',
                        total_fee: this.money * 100,
                        tid: this.$route.params.id,
                        ido: 'zc'
                    }).then(res => {
                        if (res.data.code === '0') {
                            let info = res.data.data
                            this.wxPay(info, res => {
                                if (res.err_msg === 'get_brand_wcpay_request:ok') {
                                    this.post(this.api + 'api/action/ZcDo', {
                                        openid: this.user.openid,
                                        nikename: this.user.nikename,
                                        uid: this.user.uid,
                                        tid: this.$route.params.id,
                                        total_fee: this.money * 100,
                                        content: this.text ? this.text : this.user.nikename + '献出爱心' + this.money + '元',
                                        order_num: info.order_num
                                    })
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
                } else {
                    this.$vux.alert.show(this.money ? '请输入正确的金额' : '请输入金额')
                }
            }
        },
        created() {
            this.Data({title: '众筹资助'})
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
    .money-item {
        width: 60px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        border-radius: 5px;
        border: 1px solid #9b9b9b;
        margin-right: 18px;
        margin-bottom: 12px;
    }
    .money-item-selected {
        border-color: $color;
    }
    .box1 {
        padding: 12px 4% 0;
        span {
            color: $color;
        }
    }
    .box3 {
        border-bottom: 1px solid #e5e5e5;
    }
    .weui-btn{
        font-size: 16px;
        line-height: 2.7;
    }
</style>
