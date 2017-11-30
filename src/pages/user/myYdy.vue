<template>
    <div class="myYdy" ref="scroll_el">
        <div class="interval"></div>
        <div class="pbox3_list">
            <div class="vux-form-preview weui-form-preview pbox3_list_item" v-for="(item, index) in list" :key="index">
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
                    <router-link class="weui-form-preview__btn weui-form-preview__btn_default" :to="{ name: 'student', params: { id: item.id } }"><span>学生详情</span></a></router-link>
                </div>
            </div>
            <load-more v-if="params.page && !loading" tip="正在加载"></load-more>
            <load-more v-if="!params.page" :show-loading="false" tip="暂无数据" background-color="transparent"></load-more>
        </div>
    </div>
</template>

<script>
    import { LoadMore, FormPreview } from 'vux'
    const _ = require('lodash/function')
    export default {
        name: 'myYdy',
        components: {
            LoadMore,
            FormPreview
        },
        data() {
            return {
                list: [],
                loading: true,
                params: {
                    limit: false,
                    page: 1,
                    type: 2
                }
            }
        },
        computed: {
            scroll() {
                let viewBox = this.data.scrollBox
                let view = viewBox.getScrollBody()
                return _.throttle(() => {
                    if (this.$refs.scroll_el && this.$refs.scroll_el.offsetHeight - view.offsetHeight < viewBox.getScrollTop()) {
                        this.getlist()
                    }
                }, 500)
            }
        },
        methods: {
            getlist() {
                this.get(this.api + 'api/index/MemberItem', {uid: this.user.uid, item: 'ydy'}).then(res => {
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
        created() {
            this.Data({title: '我参与的一对一资助项目'})
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
        padding: 15px 4%;
        background: #fff;
        .pbox3_list_item {
            background-color: #f1f1f1;
            border: 1px solid #dfdfdf;
            margin-bottom: 17px;
            .weui-form-preview__btn {
                text-align: left;
                padding-left: 15px;
            }
        }
    }
</style>
