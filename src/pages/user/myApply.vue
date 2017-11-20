<template>
    <div class="myApply" ref="scroll_el">
        <div class="interval"></div>
        <div class="pbox3_list center_box bf">
            <div class="vux-form-preview weui-form-preview pbox3_list_item" v-for="(item, index) in list" :key="index">
                <span class="type">赠送</span>
                <div class="weui-form-preview__bd">
                    <div class="weui-form-preview__item">
                        <label class="weui-form-preview__label">申请产品：</label>
                        <span class="weui-form-preview__value">{{love_type[params.type]}}</span>
                    </div>
                    <div class="weui-form-preview__item">
                        <label class="weui-form-preview__label">申请时间：</label>
                        <span class="weui-form-preview__value">{{dateFormat(item.create_time * 1000, 'YYYY年MM月DD日')}}</span>
                    </div>
                    <template v-if="Math.random() > 0.5">
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">申请原因：</label>
                            <span class="weui-form-preview__value">{{item.depict}}</span>
                        </div>
                        <div class="pbox3_list_reply">回复：您提交的申请已收到，我们会尽快派志愿者上门走访核实</div>
                        <div class="pbox3_list_result">申请结果：{{['审核中', '符合', '不符合'][item['status']]}}</div>
                        <div class="pbox3_list_result" v-if="item.mark">原因：{{item.mark}}</div>
                    </template>
                    <template v-else>
                        <x-button class="mt10 mb10" type="primary" mini @click.native="return_payment(item)">退还轮椅（押金）</x-button>
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
                    limit: 6,
                    page: 1,
                    type: this.$route.params.type
                }
            }
        },
        computed: {
            ...mapState([
                'love_type'
            ]),
            scroll() {
                const _ = require('lodash/function')
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
                    this.get(this.api + 'api/index/ZsApply', this.params).then(res => {
                        let info = res.data.data
                        info.forEach(item => {
                            this.list.push(item)
                        })
                        info.forEach(item => {
                            this.list.push(item)
                        })
                        info.forEach(item => {
                            this.list.push(item)
                        })
                        info.forEach(item => {
                            this.list.push(item)
                        })
                        if (!info.length || !this.params.limit || info.length < this.params.limit) {
                            this.$set(this.params, 'page', false)
                            return
                        }
                        this.$set(this.params, 'page', this.params.page + 1)
                        this.loading = true
                    })
                }
            },
            return_payment(item) {
                let t = this
                this.$vux.confirm.show({
                    title: '通知',
                    content: '您确定退还' + this.love_type[this.params.type] + '吗，工作人员收到您的反馈后会在5个工作日内处理',
                    onConfirm () {
                        t.$vux.alert.show({
                            title: '通知',
                            content: '退还押金'
                        })
                    }
                })
            }
        },
        created() {
            this.Data({title: this.love_type[this.params.type] + '申请'})
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
                color: #ff5a00;
                width: 60px;
                height: 28px;
                line-height: 28px;
                text-align: center;
                border: 1px solid #ff5a00;
                border-radius: 3px;
                font-size: 14px;
                transform: rotate(36deg);
                transform-origin: center;
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
