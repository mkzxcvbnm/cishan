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
            <x-button text="微信支付" @click.native="pay" type="primary"></x-button>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
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
                money: 300,
                agree: []
            }
        },
        computed: {
            ...mapState([
                'love_type',
                'data'
            ]),
            type() {
                return this.$route.params.type
            }
        },
        methods: {
            ...mapActions([
                'Data'
            ]),
            pay() {
                if (!this.agree.length) {
                    this.$vux.toast.show({
                        type: 'warn',
                        text: '需要同意协议'
                    })
                    return
                }
                this.$vux.alert.show({
                    title: '成功',
                    content: '支付'
                })
            }
        },
        created() {
            this.Data({title: '爱心' + this.love_type[this.type] + '申请-支付押金'})
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
