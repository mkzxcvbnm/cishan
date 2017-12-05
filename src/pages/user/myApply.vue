<template>
    <div class="myApply" ref="scroll_el">
        <div class="interval"></div>
        <div class="pbox3_list center_box bf">
            <div class="vux-form-preview weui-form-preview pbox3_list_item" v-for="(item, index) in list" :key="index">
                <span :class="{type: true, type_dz: item.classify === 1, type_zs: item.classify === 2}">{{['', '短租', '赠送'][item.classify]}}</span>
                <div class="weui-form-preview__bd">
                    <div class="weui-form-preview__item">
                        <label class="weui-form-preview__label">申请产品：</label>
                        <span class="weui-form-preview__value">{{['', '拐杖', '轮椅'][item.type]}}</span>
                    </div>
                    <div class="weui-form-preview__item">
                        <label class="weui-form-preview__label">申请时间：</label>
                        <span class="weui-form-preview__value">{{dateFormat(item.begin_time * 1000, 'YYYY年MM月DD日')}}</span>
                    </div>
                    <template v-if="item.classify === 1">
                        <x-button v-if="item.status === 0" class="mt10 mb10" type="primary" mini @click.native="return_payment(item)">退还轮椅（押金）</x-button>
                        <span v-else-if="item.status === 1">处理中</span>
                        <span v-else>已退还</span>
                    </template>
                    <template v-else>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">申请原因：</label>
                            <span class="weui-form-preview__value">{{item.depict}}</span>
                        </div>
                        <div class="pbox3_list_reply">回复：您提交的申请已收到，我们会尽快派志愿者上门走访核实</div>
                        <div class="pbox3_list_result">申请结果：{{['审核中', '符合', '不符合'][item['status']]}}</div>
                        <div class="pbox3_list_result" v-if="item.mark">原因：{{item.mark}}</div>
                    </template>
                </div>
            </div>
            <load-more v-if="params.page && !loading" tip="正在加载"></load-more>
            <load-more v-if="!params.page" :show-loading="false" tip="暂无数据" background-color="transparent"></load-more>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex'
    import { FormPreview, LoadMore, dateFormat, XButton } from 'vux'
    const _ = require('lodash/function')
    export default {
        name: 'myApply',
        components: {
            FormPreview,
            LoadMore,
            XButton
        },
        data() {
            return {
                list: [],
                loading: true,
                params: {
                    limit: false,
                    page: 1,
                    type: ['', 'lovegz', 'lovely'][this.$route.params.type]
                }
            }
        },
        computed: {
            ...mapState([
                'love_type'
            ]),
            scroll() {
                let viewBox = this.data.scrollBox
                let view = viewBox.getScrollBody()
                return _.throttle(() => {
                    if (this.$refs.scroll_el.offsetHeight - view.offsetHeight < viewBox.getScrollTop()) {
                        this.getlist()
                    }
                }, 500)
            }
        },
        methods: {
            dateFormat(time, format) {
                return dateFormat(time, format)
            },
            getlist() {
                if (this.loading) {
                    this.loading = false
                    this.get(this.api + 'api/index/MemberItem', {uid: this.user.uid, item: ['', 'lovegz', 'lovely'][this.$route.params.type]}).then(res => {
                        let info = res.data.data
                        info.forEach(item => {
                            this.list.push(item)
                        })
                        if (!info.length || !this.params.limit || info.length < this.params.limit) {
                            this.$set(this.params, 'page', false)
                            this.data.scrollBox.getScrollBody().removeEventListener('scroll', this.scroll)
                            return false
                        }
                        this.$set(this.params, 'page', this.params.page + 1)
                        this.loading = true
                    })
                }
            },
            return_payment(item) {
                this.$vux.confirm.show({
                    title: '通知',
                    content: '您确定退还' + this.love_type[this.$route.params.type] + '吗，工作人员收到您的反馈后会在5个工作日内处理',
                    onConfirm: function() {
                        this.post(this.api + 'api/action/GiveBack', {uid: this.user.uid, id: item.id, status: 1}).then(res => {
                            // let info = res.data.data
                            this.$set(item, 'status', 1)
                            if (res.data.code === '0') {
                                this.$vux.alert.show({
                                    title: '成功',
                                    content: '退还成功'
                                })
                            } else {
                                this.$vux.alert.show({
                                    title: '失败',
                                    content: '退还失败'
                                })
                            }
                        })
                    }.bind(this)
                })
            }
        },
        created() {
            this.Data({title: this.love_type[this.$route.params.type] + '申请'})
            this.getlist()
            this.$nextTick(() => {
                this.data.scrollBox.getScrollBody().addEventListener('scroll', this.scroll)
            })
        },
        beforeDestroy() {
            this.$nextTick(() => {
                this.data.scrollBox.getScrollBody().removeEventListener('scroll', this.scroll)
            })
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
    .pbox3_list {
        padding: 10px 4%;
        .tit {
            line-height: 43px;
            color: #000;
            font-size: 16px;
            padding-bottom: 10px;
        }
        .pbox3_list_item {
            background-color: #f1f1f1;
            border: 1px solid #dfdfdf;
            margin-bottom: 17px;
            .isPledging span {
                background-color: #bdbdbd;
                border-color: #bdbdbd;
                color: #fff;
            }
            .type {
                display: block;
                position: absolute;
                left: auto;
                right: 5px;
                top: 18px;
                width: 60px;
                height: 28px;
                line-height: 28px;
                text-align: center;
                border-radius: 3px;
                font-size: 14px;
                transform: rotate(36deg);
                transform-origin: center;
                &.type_dz {
                    color: $color;
                    border: 1px solid $color;
                }
                &.type_zs {
                    color: #ff5a00;
                    border: 1px solid #ff5a00;
                }
            }
        }
        .pbox3_list_reply {
            color: #ff5a00;
            padding: 10px 0;
            line-height: 1.5;
        }
        .pbox3_list_result {
            color: $color;
            line-height: 1.5;
        }
    }
</style>
