<template>
    <div class="ydy bf" ref="ydy">
        <div v-if="sdata.status == 0" class="page_top state_0">
            <span class="page_top_l">预告中</span>
            <span class="page_top_r">{{dateFormat(sdata.begin_time * 1000, 'MM月DD日 HH:mm')}} 开始</span>
        </div>
        <div v-else-if="sdata.status == 1" class="page_top state_1">
            <span class="page_top_l">进行中</span>
            <span class="page_top_r">
                <clocker :time="dateFormat(sdata.end_time * 1000, 'YYYY-MM-DD HH:mm:ss')" @on-finish="sdata.status = 2">
                    捐款通道关闭剩%H小时%M分钟%S秒结束
                </clocker>
            </span>
        </div>
        <div v-else class="page_top state_2">
            <span class="page_top_r">已结束，请关注其他项目</span>
        </div>
        <div class="interval"></div>
        <div class="pbox1">{{sdata.depict}}</div>
        <div class="pbox2">
            <div class="pbox2_top">
                <span>认捐学生列表</span>
                <button @click="showDialog = true">认捐须知</button>
            </div>
            <ul class="pbox2_list">
                <li :class="{isPledging: item.status === 1 || sdata.status !== 1, isOp: item.op === 1}" v-for="(item, index) in list" :key="index" @click="scrollToStudent(item.id)">{{(Array(2).join(0) + (index+1)).slice(-2)}}.{{btnName1(item)}}</li>
            </ul>
        </div>
        <div class="interval"></div>
        <div class="pbox3">
            <div class="pbox3_top">
                <span>学生资料列表</span>
            </div>
            <div class="pbox3_list">
                <div class="vux-form-preview weui-form-preview pbox3_list_item" :ref="'student_' + item.id" v-for="(item, index) in list" :key="index">
                    <div class="weui-form-preview__hd">
                        <label class="weui-form-preview__label" >{{(Array(2).join(0) + (index+1)).slice(-2)}}.{{item.name}}</label>
                        <em class="weui-form-preview__value">{{item.cid}}</em>
                    </div>
                    <div class="weui-form-preview__bd">
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">地区：</label>
                            <span class="weui-form-preview__value">{{item.city}}</span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">性别：</label>
                            <span class="weui-form-preview__value">{{['男', '女'][item.sex]}}</span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">年级：</label>
                            <span class="weui-form-preview__value">{{item.grade}}</span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">助学金：</label>
                            <span class="weui-form-preview__value">{{item.help_money}}元/学期</span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">汇款金额：</label>
                            <span class="weui-form-preview__value">{{item.give_money}}元/学期</span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">贫困原因：</label>
                            <span class="weui-form-preview__value">{{item.reason}}</span>
                        </div>
                    </div>
                    <div class="weui-form-preview__ft">
                        <a class="weui-form-preview__btn weui-form-preview__btn_default tal ml15" href="javascript:;" @click="student(item)"><span>学生详情</span></a>
                        <a class="weui-form-preview__btn weui-form-preview__btn_primary tar mr15" :class="{isPledging: item.status === 1 || sdata.status !== 1}" href="javascript:" @click="pay(item)"><span>{{btnName2(item)}}</span></a>
                    </div>
                </div>
                <!-- <form-preview class="pbox3_list_item" :class="[studentState(item)]" v-for="(item, index) in data['ydy_list' + id]" :key="index" :header-label="item.title" :header-value="item.id" :body-items="preview_list(item)" :footer-buttons="preview_btn(item)" name="demo"></form-preview> -->
            </div>
        </div>
        <noticePopup :id="0" :showDialog="showDialog" @on-change="v => showDialog = v"></noticePopup>
    </div>
</template>

<script>
    import { dateFormat, XDialog, FormPreview, Clocker } from 'vux'
    import noticePopup from '@/components/notice_popup'
    import velocity from 'velocity-animate'
    export default {
        name: 'ydy',
        components: {
            XDialog,
            FormPreview,
            Clocker,
            noticePopup
        },
        data() {
            return {
                sdata: {},
                list: [],
                showDialog: false
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
            scrollToStudent(id) {
                let container = this.data.scrollBox.getScrollBody()
                let box = this.$refs.ydy
                velocity(box, 'scroll', {
                    container: container,
                    offset: this.$refs['student_' + id][0].offsetTop - 100
                })
            },
            btnName1(item) {
                if (item.op === 1) {
                    return '已经被优先认捐'
                } else if (item.anyone === 1) {
                    return '已被匿名认捐'
                } else {
                    return item.name
                }
            },
            btnName2(item) {
                if (item.op === 1) {
                    return '已经被优先认捐'
                } else if (item.anyone === 1) {
                    return '该学生为匿名捐赠'
                } else if (item.status === 0) {
                    return '我要认捐'
                } else {
                    return '已被' + (item.uname ? item.uname.toString().substr(0, 3) : '') + '认捐'
                }
            },
            student(item) {
                if (item.uid === this.user.uid) {
                    this.$router.push({ name: 'student', params: { id: item.id } })
                } else {
                    this.$vux.alert.show({
                        title: '通知',
                        content: '认捐人才可以查看',
                        buttonText: '我知道了'
                    })
                }
            },
            pay(item) {
                if (item.status === 0) {
                    if (this.sdata.status === 0) {
                        this.$vux.alert.show({
                            title: '通知',
                            content: '活动暂未开始，请开始后再来',
                            buttonText: '我知道了'
                        })
                    } else if (this.sdata.status === 2) {
                        this.$vux.alert.show('活动已结束')
                    } else if (!this.user.mobile) {
                        this.$vux.confirm.show({
                            title: '通知',
                            content: '您还没有验证手机号，立即前往验证',
                            onConfirm: function() {
                                this.$store.commit('BACKURL', this.$route.fullPath)
                                this.$router.push({name: 'verifyPhone'})
                            }.bind(this)
                        })
                        return false
                    } else {
                        this.post(this.api + 'api/action/jspay', {
                            openid: this.user.openid,
                            body: '一对一资助',
                            total_fee: item.give_money * 100,
                            tid: item.id,
                            ido: 'ydy'
                        }).then(res => {
                            if (res.data.code === '0') {
                                let info = res.data.data
                                this.wxPay(info, res => {
                                    if (res.err_msg === 'get_brand_wcpay_request:ok') {
                                        this.$set(item, 'status', 1)
                                        this.$set(item, 'uname', this.user.nikename)
                                        this.post(this.api + 'api/action/YdyDo', {
                                            openid: this.user.openid,
                                            id: item.id,
                                            uid: this.user.uid,
                                            name: this.user.nikename,
                                            mobile: this.user.mobile,
                                            begin_time: Math.round(new Date().getTime() / 1000),
                                            total_fee: item.give_money * 100,
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
                    }
                } else {
                    this.$vux.alert.show('该学生已被认捐')
                }
            }
        },
        created() {
            this.get(this.api + 'api/index/YdyShow', {id: this.id}).then(res => {
                let info = res.data.data
                this.Data({title: info.title})
                this.$set(this, 'sdata', info)
            })
            this.get(this.api + 'api/index/Student', {tid: this.id}).then(res => {
                let info = res.data.data
                this.$set(this, 'list', info)
            })
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
    .ydy {
        font-size: 14px;
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
    .pbox_tit {
        padding: 8px 4%;
        line-height: 34px;
        span {
            color: #000;
            font-size: 16px;
        }
    }
    .pbox1 {
        color: #6d6d6d;
        padding: 12px 4%;
    }
    .pbox2 {
        .pbox2_top {
            @extend .pbox_tit;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e5e5e5;
            border-bottom: 1px solid #e5e5e5;
            button {
                width: 94px;
                line-height: 32px;
                color: #2e2e2e;
                font-size: 14px;
                background-color: #f1f1f1;
                border-radius: 6px;
            }
        }
        .pbox2_list {
            padding: 10px 4%;
            display: flex;
            flex-flow: row wrap;
            justify-content: space-around;
            height: 100%;
            li {
                width: 161px;
                height: 31.5px;
                line-height: 31.5px;
                text-align: center;
                color: $color;
                background-color: transparent;
                border: 1px solid $color;
                border-radius: 6px;
                margin-top: 10px;
                margin-bottom: 10px;
                &.isPledging {
                    color: #f3f3f3;
                    background-color: #bdbdbd;
                    border-color: #bdbdbd;
                }
                &.isOp {
                    color: #c9c9c9;
                    border-color: #c9c9c9;
                    background-color: transparent;
                }
            }
            &:after {
                content: '';
                width: 161px;
            }
        }
    }
    .pbox3 {
        .pbox3_top {
            @extend .pbox_tit;
        }
        .pbox3_list {
            padding: 10px 4%;
            .pbox3_list_item {
                background-color: #f1f1f1;
                border: 1px solid #dfdfdf;
                margin-bottom: 17px;
                .isPledging span {
                    background-color: #bdbdbd;
                    border-color: #bdbdbd;
                    color: #fff;
                }
            }
        }
    }
</style>
