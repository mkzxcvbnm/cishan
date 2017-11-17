<template>
    <div class="love" ref="love">
        <div class="interval"></div>
        <mk-notice></mk-notice>
        <div class="interval"></div>
        <mk-ibox2></mk-ibox2>
        <div class="interval"></div>
        <mk-ibox3></mk-ibox3>
        <div class="interval"></div>
        <div class="pbox3_list center_box bf">
            <div class="tit">申请轮椅赠送用户详情</div>
            <div class="vux-form-preview weui-form-preview pbox3_list_item" v-for="(item, index) in list" :key="index">
                <div class="weui-form-preview__bd">
                    <div class="weui-form-preview__item">
                        <label class="weui-form-preview__label">申请用户：</label>
                        <span class="weui-form-preview__value">{{item.name}}</span>
                    </div>
                    <div class="weui-form-preview__item">
                        <label class="weui-form-preview__label">申请产品：</label>
                        <span class="weui-form-preview__value">轮椅</span>
                    </div>
                    <div class="weui-form-preview__item">
                        <label class="weui-form-preview__label">申请时间：</label>
                        <span class="weui-form-preview__value">{{dateFormat(item.create_time * 1000, 'YYYY年MM月DD日')}}</span>
                    </div>
                    <div class="weui-form-preview__item">
                        <label class="weui-form-preview__label">申请原因：</label>
                        <span class="weui-form-preview__value">{{item.depict}}</span>
                    </div>
                    <div class="pbox3_list_reply">回复：您提交的申请已收到，我们会尽快派志愿者上门走访核实</div>
                    <div class="pbox3_list_result">申请结果：{{['审核中', '符合', '不符合'][item['status']]}}</div>
                    <div class="pbox3_list_result">原因：{{item.mark}}</div>
                </div>
            </div>
            <load-more v-if="params.page && !loading" tip="正在加载"></load-more>
            <load-more v-if="!params.page" :show-loading="false" tip="暂无数据" background-color="transparent"></load-more>
            <!-- <load-more v-if="params.page !== false" tip="loading"></load-more>
            <load-more v-else :show-loading="false" tip="暂无数据" background-color="transparent"></load-more> -->
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import mkNotice from '@/components/notice'
    import mkIbox2 from '@/components/ibox2'
    import mkIbox3 from '@/components/ibox3'
    import { FormPreview, LoadMore, dateFormat } from 'vux'
    export default {
        name: 'love',
        components: {
            mkNotice,
            mkIbox2,
            mkIbox3,
            FormPreview,
            LoadMore
        },
        data() {
            return {
                loading: true,
                list: [],
                params: {
                    limit: 6,
                    page: 1,
                    type: 2
                }
            }
        },
        computed: {
            ...mapState([
                'data'
            ]),
            scroll() {
                const _ = require('lodash/function')
                let viewBox = this.data.scrollBox
                let view = viewBox.getScrollBody()
                return _.throttle(() => {
                    if (this.$refs.love && this.$refs.love.offsetHeight - view.offsetHeight < viewBox.getScrollTop()) {
                        this.getlist()
                    }
                }, 500)
            }
        },
        methods: {
            ...mapActions([
                'Data'
            ]),
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
                        if (!info.length || !this.params.limit || info.length < this.params.limit) {
                            this.$set(this.params, 'page', false)
                            return
                        }
                        this.$set(this.params, 'page', this.params.page + 1)
                        this.loading = true
                    })
                }
            }
        },
        created() {
            this.Data({title: '爱心轮椅'})
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
            &:before {
                display: block;
                content: '赠送';
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
