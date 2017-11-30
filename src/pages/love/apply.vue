<template>
    <div class="chair">
        <img class="top_pic" src="~img/top_pic.jpg">
        <div class="love_box1 center_box bf">
            <p class="tit">丹阳市九九爱心社爱心{{love_type[type]}}申请</p>
            <x-progress class="zc_progress" :percent="sdata.rs_num / sdata.num" :show-cancel="false"></x-progress>
            <flexbox class="info" justify="space-between">
                <flexbox-item>
                    总数：<span>{{sdata.num}}</span>
                </flexbox-item>
                <flexbox-item class="tar">
                    已领取：<span>{{sdata.rs_num}}</span>
                </flexbox-item>
            </flexbox>
        </div>
        <div class="apply_btn bf">
            <div class="fr">
                <x-button mini @click.native="showDialog = true">申请需知</x-button>
                <router-link :to="{ name: 'rent', params: { type: type } }"><x-button mini type="primary">申请短租</x-button></router-link>
                <router-link :to="{ name: 'free', params: { type: type } }"><x-button mini type="warn">申请赠送</x-button></router-link>
            </div>
        </div>
        <div class="interval"></div>
        <mk-ibox3></mk-ibox3>
        <noticePopup :id="1" :showDialog="showDialog" @on-change="v => showDialog = v"></noticePopup>
    </div>
</template>

<script>
    import { mapState } from 'vuex'
    import { XProgress, Flexbox, FlexboxItem, XButton } from 'vux'
    import mkIbox3 from '@/components/ibox3'
    import noticePopup from '@/components/notice_popup'
    export default {
        name: 'chair',
        components: {
            XProgress,
            Flexbox,
            FlexboxItem,
            XButton,
            mkIbox3,
            noticePopup
        },
        data() {
            return {
                sdata: {},
                showDialog: false
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
        created() {
            this.Data({title: '爱心' + this.love_type[this.type] + '申请'})
            this.get(this.api + 'api/index/Sku', {id: this.type, type: this.type}).then(res => {
                this.$set(this, 'sdata', res.data.data)
            })
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
    .love_box1 {
        .tit {
            color: #6d6d6d;
            font-size: 14px;
            line-height: 42px;
            height: 42px;
        }
        .info {
            font-size: 14px;
            color: #6d6d6d;
            span {
                color: $color;
                line-height: 37px;
            }
        }
    }
    .apply_btn {
        padding: 22px 4%;
        overflow: hidden;
        border-top: 1px solid #e5e5e5;
        .fr {
            float: right;
        }
        .weui-btn + .weui-btn {
            margin-top: 0;
        }
        .weui-btn_warn {
            background-color: #ff5a00;
        }
        .weui-btn_mini {
            font-size: 14px;
            margin-left: 10px;
            padding: 0 10px;
        }
    }
</style>
