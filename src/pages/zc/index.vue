<template>
    <div class="zc" ref="zc">
        <div v-if="sdata.status == 0" class="page_top state_0">
            <span class="page_top_l">预告中</span>
            <span class="page_top_r">{{dateFormat(sdata.begin_time, 'MM月DD日 HH:mm')}} 开始</span>
        </div>
        <div v-else-if="sdata.status == 1" class="page_top state_1">
            <span class="page_top_l">进行中</span>
            <span class="page_top_r">
                <clocker :time="dateFormat(sdata.end_time * 1000, 'YYYY-MM-DD HH:mm:ss')" @on-finish="sdata.status = 2">
                    距众筹通道关闭剩%H小时%M分钟%S秒
                </clocker>
            </span>
        </div>
        <div v-else class="page_top state_2">
            <span class="page_top_r">已结束，请关注其他项目</span>
        </div>
        <img class="zc_pic" src="~img/banner.jpg" alt="">
        <div class="zc_box1 center_box bf">
            <x-progress class="zc_progress" :percent="percent(sdata)" :show-cancel="false"></x-progress>
            <p>众筹进度：{{percent(sdata).toFixed(2)}}%</p>
        </div>
        <flexbox class="zc_box2 center_box bf" justify="space-between">
            <flexbox-item>
                <span>¥{{sdata.need_money}}</span>
                <p>目标金额</p>
            </flexbox-item>
            <flexbox-item>
                <span>¥{{sdata.now_money}}</span>
                <p>已筹款</p>
            </flexbox-item>
            <flexbox-item>
                <span>{{sdata.num}}</span>
                <p>爱心（份）</p>
            </flexbox-item>
        </flexbox>
        <div class="zc_box3 center_box bf">
            <p>感谢<span>{{sdata.num}}</span>位朋友的爱心，让我们一起努力</p>
            <div class="zc_avatar">
                <ul>
                    <li v-if="index < 11" v-for="(item, index) in avatar" :key="index" :style="{backgroundImage: 'url('+item.avatar+')'}"></li>
                </ul>
                <i class="fa fa-angle-right" aria-hidden="true" @click="scrollToMsg"></i>
            </div>
        </div>
        <div class="interval"></div>
        <div class="zc_content bf">
            <div class="tit center_box">
                项目详情
            </div>
            <div class="content center_box">
                {{sdata.depict}}
            </div>
        </div>
        <div class="interval"></div>
        <div class="zc_ly bf">
            <div class="tit center_box" ref="msg">
                爱心留言
            </div>
            <div class="ly_list">
                <div class="ly_item" v-for="(item, index) in reply.list" :key="index">
                    <div class="ly_pic cover" :style="{backgroundImage: 'url('+item.avatar+')'}"></div>
                    <div class="ly_info">
                        <div class="ly_info_top">
                            <div class="fl">{{item.nikename}}</div>
                            <div class="fr">捐款：<span>{{item.money}}</span>元</div>
                        </div>
                        <div class="ly_content">{{item.content}}</div>
                        <div class="ly_time">{{dateFormat(item.create_time * 1000, 'YYYY-MM-DD HH:mm:ss')}}</div>
                        <div v-if="!!item.mark" class="ly_hf">回复：{{item.mark}}</div>
                    </div>
                </div>
                <div class="no_data" v-if="!reply.params.page && !reply.list.length">暂无数据</div>
                <div class="ly_more" v-if="reply.params.page" @click="getreply">
                    <i v-show="!reply.loading" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                    查看更多<i class="fa fa-angle-down" aria-hidden="true"></i>
                </div>
            </div>
        </div>
        <transition
        name="footer"
        mode="out-in"
        appear
        >
            <tabbar class="bbtn" v-if="sdata.id">
                <tabbar-item>
                    <span class="ljjk" slot="label" @click="pay">立即捐款</span>
                </tabbar-item>
                <tabbar-item>
                    <span class="yqjk" slot="label">邀请好友一起捐款</span>
                </tabbar-item>
            </tabbar>
        </transition>
        <mk-rbtn></mk-rbtn>
    </div>
</template>

<script>
    import { dateFormat, Tabbar, TabbarItem, Clocker, XProgress, Flexbox, FlexboxItem } from 'vux'
    import mkRbtn from '@/components/rbtn'
    import velocity from 'velocity-animate'
    export default {
        name: 'zc',
        components: {
            mkRbtn,
            Tabbar,
            TabbarItem,
            Clocker,
            XProgress,
            Flexbox,
            FlexboxItem
        },
        data() {
            return {
                sdata: {},
                avatar: [],
                reply: {
                    list: [],
                    loading: true,
                    params: {
                        limit: 4,
                        page: 1
                    }
                }
            }
        },
        computed: {
            id() {
                return this.$route.params.id
            }
        },
        methods: {
            dateFormat(time, format) {
                return dateFormat(time, format)
            },
            percent(item) {
                return item.now_money / item.need_money * 100
            },
            scrollToMsg(id) {
                let container = this.data.scrollBox.getScrollBody()
                let box = this.$refs.zc
                velocity(box, 'scroll', {
                    container: container,
                    offset: this.$refs.msg.offsetTop - 100,
                    mobileHA: false
                })
            },
            getreply() {
                let hf = this.reply
                if (hf.loading) {
                    hf.loading = false
                    this.get(this.api + 'api/index/ZcReply', {tid: this.id, ...hf.params}).then(res => {
                        let info = res.data.data
                        info.forEach(item => {
                            hf.list.push(item)
                        })
                        hf.loading = true
                        hf.params.page++
                        if (!info.length || !hf.params.limit || info.length < hf.params.limit) {
                            hf.params.page = false
                            return false
                        }
                    })
                }
            },
            pay() {
                let sdata = this.sdata
                if (sdata.status === 0) {
                    this.$vux.alert.show({
                        title: '通知',
                        content: '活动暂未开始，请开始后再来',
                        buttonText: '我知道了'
                    })
                } else if (sdata.status === 2) {
                    this.$vux.alert.show('活动已结束')
                } else {
                    this.$router.push({ name: 'zcPay', params: { id: sdata.id, quick_payment: sdata.quick_payment } })
                }
            }
        },
        created() {
            this.get(this.api + 'api/index/ZcShow', {id: this.id}).then(res => {
                let info = res.data.data
                this.Data({title: info.title})
                this.$set(this, 'sdata', info)
            })
            this.get(this.api + 'api/index/ZcAvatar', {tid: this.id}).then(res => {
                let info = res.data.data
                this.$set(this, 'avatar', info)
            })
            this.getreply()
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
    .line_box {
        position: relative;
        &.line:before,&.line:after {
            content: '';
            width: 100%;
            position: absolute;
            left: 0;
        }
        &.line:before {
            top: 0;
        }
        &.line:after {
            bottom: 0;
        }
    }
    .line {
        width: 100%;
        height: 1px;
        background-color: #e5e5e5;
    }
    .zc {
        font-size: 14px;
        .footer-enter-active, .footer-leave-active {
            transition: all 1s;
        }
        .bbtn {
            background-color: #fff;
            padding: 20px 4%;
            width: 92%;
            justify-content: space-between;
            span {
                display: block;
                width: 162px;
                height: 41px;
                line-height: 41px;
                text-align: center;
                color: #fff;
                border-radius: 6px;
            }
            .ljjk {
                background-color: $color;
            }
            .yqjk {
                background-color: #ff5a00;
            }
            .weui-tabbar__item {
                flex: 0;
            }
            .weui-tabbar__item.vux-tabbar-simple {
                height: auto;
            }
        }
        .zc_pic {
            width: 100%;
            display: block;
        }
        .zc_box1 {
            padding-top: 16px;
            p {
                color: #6d6d6d;
                line-height: 37px;
            }
        }
        .zc_box2 {
            width: auto;
            text-align: center;
            padding: 10px 0;
            @extend .line_box;
            &:before,&:after {
                @extend .line;
            }
            span {
                color: $color;
                line-height: 32px;
            }
            p {
                color: #6d6d6d;
                line-height: 24px;
            }
        }
        .zc_box3 {
            padding-top: 10px;
            padding-bottom: 40px;
            p {
                color: #000;
                span {
                    color: $color;
                }
            }
            .zc_avatar {
                padding-top: 25px;
                overflow: hidden;
                ul {
                    width: 307.5px;
                    height: 42.5px;
                    overflow: hidden;
                    float: left;
                }
                li {
                    width: 42.5px;
                    height: 42.5px;
                    border-radius: 50%;
                    float: left;
                    margin-right: -16px;
                    background: url(~img/banner.jpg) no-repeat center / cover;
                }
                .fa {
                    float: right;
                    font-size: 28px;
                    margin-top: 2px;
                }
            }
        }
        .zc_content {
            .content {
                padding-top: 20px;
                padding-bottom: 20px;
            }
        }
        .tit {
            height: 36px;
            line-height: 36px;
            @extend .line_box;
            font-size: 16px;
            color: #000;
            &:after {
                @extend .line;
            }
        }
    }
    .ly_list {
        .ly_item {
            padding: 20px 4% 15px;
            display: flex;
            @extend .line_box;
            &:before {
                @extend .line;
            }
            &:first-child:before {
                display: none;
            }
            .ly_pic {
                width: 57px;
                height: 57px;
                border-radius: 50%;
                margin-right: 15px;
            }
            .ly_info {
                flex: 1;
            }
            .ly_info_top {
                display: flex;
                justify-content: space-between;
                line-height: 1;
                padding-bottom: 10px;
                .fl {
                    color: #6173a4;
                }
                .fr {
                    color: #6d6d6d;
                    font-size: 13px;
                    span {
                        color: $color;
                    }
                }
            }
            .ly_content {
                color: #2e2e2e;
                padding-bottom: 5px;
            }
            .ly_time {
                color: #6d6d6d;
                font-size: 13px;
            }
            .ly_hf {
                color: $color;
                margin-top: 10px;
            }
        }
        .ly_more {
            text-align: center;
            line-height: 40px;
            color: $color;
            border-top: 1px solid #e5e5e5;
            border-bottom: 1px solid #e5e5e5;
            i {
                font-size: 25px;
                margin-left: 10px;
                position: relative;
                top: 3px;
            }
        }
        .no_data {
            font-size: 20px;
            color: #dbdbdb;
            height: 86px;
            line-height: 86px;
            text-align: center;
        }
    }
    .page_top {
        height: 54px;
        line-height: 54px;
        color: #2e2e2e;
        background-color: #f2f2f2;
        display: flex;
        text-align: center;
        .page_top_l {
            color: #fff;
            width: 25%;
        }
        .page_top_r {
            flex: 1;
        }
        &.state_0 {
            .page_top_l {
                background-color: #ff5a00;
            }
        }
        &.state_1 {
            .page_top_l {
                background-color: $color;
            }
        }
        &.state_2 {
            background: #afafaf;
        }
    }
</style>
