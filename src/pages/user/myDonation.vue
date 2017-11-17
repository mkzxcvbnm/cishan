<template>
    <div class="myDonation" ref="scroll_el">
        <div class="interval"></div>
        <div class="donation_list">
            <div class="donation_item" v-for="(item, index) in list" :key="index">
                <div>捐赠项目：{{['', '拐杖', '轮椅'][item.type]}}</div>
                <div>捐赠数量：{{item.type}}</div>
                <div>捐赠时间：{{dateFormat(item.begin_time * 1000, 'YYYY年MM月DD日')}}</div>
            </div>
            <load-more v-if="params.page && !loading" tip="正在加载"></load-more>
            <load-more v-if="!params.page" :show-loading="false" tip="暂无数据" background-color="transparent"></load-more>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import { dateFormat, LoadMore } from 'vux'
    export default {
        name: 'myDonation',
        components: {
            LoadMore
        },
        data() {
            return {
                list: [],
                loading: true,
                params: {
                    limit: 10,
                    page: 1,
                    order: 1,
                    type: 'all'
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
                    if (this.$refs.scroll_el.offsetHeight - view.offsetHeight < viewBox.getScrollTop()) {
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
                    this.get(this.api + 'api/index/SyrItem', this.params).then(res => {
                        let info = res.data.data.list
                        info.forEach(item => {
                            this.list.push(item)
                        })
                        if (!info.length || !this.params.limit || info.length < this.params.limit) {
                            this.params.page = false
                            return
                        }
                        this.loading = true
                        this.params.page++
                    })
                }
            }
        },
        created() {
            this.Data({title: '实物捐赠'})
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
    .donation_list {
        padding: 12px 15px;
        background: #fff;
    }
    .donation_item {
        border: 1px solid #e9e9e9;
        padding: 20px 15px;
        background-color: #f1f1f1;
        color: #2e2e2e;
        line-height: 30px;
        margin-bottom: 15px;
    }
</style>
