<template>
    <div class="myZc" ref="scroll_el">
        <div class="interval"></div>
        <list-zc :list="list"></list-zc>
        <load-more v-if="params.page && !loading" tip="正在加载"></load-more>
        <load-more v-if="!params.page" :show-loading="false" tip="暂无数据" background-color="transparent"></load-more>
    </div>
</template>

<script>
    import { LoadMore } from 'vux'
    import listZc from '@/components/list_zc'
    export default {
        name: 'myZc',
        components: {
            LoadMore,
            listZc
        },
        data() {
            return {
                list: [],
                loading: true,
                params: {
                    limit: 6,
                    page: 1,
                    order: 1,
                    status: 'all'
                }
            }
        },
        computed: {
            scroll() {
                const _ = require('lodash/function')
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
                this.get(this.api + 'api/index/ZcItem', this.params).then(res => {
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
        },
        created() {
            this.Data({title: '我参与的众筹资助项目'})
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
</style>
