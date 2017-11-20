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
                if (this.money_num(this.money)['valid'] && this.money) {
                    this.$vux.alert.show({
                        title: '提示',
                        content: '支付',
                        buttonText: '我知道了'
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
