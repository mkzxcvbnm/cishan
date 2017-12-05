<template>
    <div class="chairFree" ref="scroll_el">
        <div class="interval"></div>
        <div class="help_list">
            <div class="center">
                <div class="help_list_item" v-for="(item, index) in list" :key="index">
                    <div class="item_name">求助类型：{{['助学求助', '轮椅求助', '心愿求助', '其他求助'][item.type]}}</div>
                    <div class="item_time">申请时间：{{dateFormat(item.create_time * 1000, 'YYYY年MM月DD日')}}</div>
                    <div class="item_content">求助内容：{{item.content}}</div>
                    <div v-if="!!item.mark" class="mark">官方回复：{{item.mark}}</div>
                </div>
                <load-more v-if="params.page && !loading" tip="正在加载"></load-more>
                <load-more v-if="!params.page" :show-loading="false" tip="暂无数据" background-color="transparent"></load-more>
            </div>
        </div>
    </div>
</template>

<script>
    import { dateFormat, LoadMore } from 'vux'
    const _ = require('lodash/function')
    export default {
        name: 'chairFree',
        components: {
            LoadMore
        },
        data() {
            return {
                list: [],
                loading: true,
                params: {
                    limit: false,
                    page: 1,
                    order: 1,
                    type: 'all'
                }
            }
        },
        computed: {
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
                    this.get(this.api + 'api/index/MemberItem', {uid: this.user.uid, item: 'help'}).then(res => {
                        let info = res.data.data
                        info.forEach(item => {
                            this.list.push(item)
                        })
                        if (!info.length || !this.params.limit || info.length < this.params.limit) {
                            this.user.read_num = 0
                            this.params.page = false
                            this.data.scrollBox.getScrollBody().removeEventListener('scroll', this.scroll)
                            return false
                        }
                        this.loading = true
                        this.params.page++
                    })
                }
            }
        },
        created() {
            this.Data({title: '我的求助'})
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
    .help_list {
        background: #fff;
        padding: 15px 0;
    }
    .help_list_item {
        background-color: #f1f1f1;
        border: 1px solid #e5e5e5;
        padding: 20px 15px;
        position: relative;
        color: #2e2e2e;
        line-height: 24px;
        margin-bottom: 15px;
        div[class^=item_] {
            margin-bottom: 3px;
        }
        .item_type {
            position: absolute;
            right: 15px;
            top: 20px;
            font-size: 14px;
            color: $color;
            border: 1px solid $color;
            border-radius: 5px;
            padding: 3px 6px;
        }
        .mark {
            color: #ff5a00;
            margin-top: 10px;
        }
    }
</style>
