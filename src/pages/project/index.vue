<template>
    <div class="project">
        <div class="interval"></div>
        <!-- <sticky scroll-box="vux_view_box_body" ref="sticky" :check-sticky-support="false" :offset="46"> -->
            <tab :line-width="2" active-color="#09bb07" v-model="active">
                <tab-item class="vux-center" :selected="active === index" v-for="(item, index) in tab" :key="index">{{item.title}}</tab-item>
            </tab>
        <!-- </sticky> -->
        <swiper v-model="active" height="100%" :show-dots="false" @on-index-change="getlist">
            <swiper-item v-for="(item, index) in tab" :key="index">
                <scroller lock-x height="100%" @on-scroll-bottom="getlist(index)" ref="scroller" :scroll-bottom-offst="100">
                    <div>
                        <list-ydy v-if="type == 0" key="0" :list="tab[index]['list']"></list-ydy>
                        <list-zc v-else key="1" :list="tab[index]['list']"></list-zc>
                        <load-more v-if="tab[index]['params']['page'] !== false" tip="loading"></load-more>
                        <load-more v-else :show-loading="false" tip="暂无数据" background-color="transparent"></load-more>
                    </div>
                </scroller>
            </swiper-item>
        </swiper>
    </div>
</template>

<script>
    import { Tab, TabItem, Sticky, Swiper, SwiperItem, Scroller, LoadMore, dateFormat } from 'vux'
    import listYdy from '@/components/list_ydy'
    import listZc from '@/components/list_zc'
    export default {
        name: 'project',
        components: {
            Tab,
            TabItem,
            Sticky,
            Swiper,
            SwiperItem,
            Scroller,
            LoadMore,
            listYdy,
            listZc
        },
        data() {
            return {
                active: 0,
                cate: [{
                    url: this.api + 'api/index/YdyItem',
                    title: '一对一资助'
                },
                {
                    url: this.api + 'api/index/ZcItem',
                    title: '众筹资助'
                }],
                tab: [{
                    title: '全部',
                    list: [],
                    params: {
                        limit: 6,
                        page: 1,
                        order: 1,
                        status: 'all'
                    },
                    loading: true
                },
                {
                    title: '预告中',
                    list: [],
                    params: {
                        limit: 6,
                        page: 1,
                        order: 1,
                        status: 0
                    },
                    loading: true
                },
                {
                    title: '进行中',
                    list: [],
                    params: {
                        limit: 6,
                        page: 1,
                        order: 1,
                        status: 1
                    },
                    loading: true
                },
                {
                    title: '已结束',
                    list: [],
                    params: {
                        limit: 6,
                        page: 1,
                        order: 1,
                        status: 2
                    },
                    loading: true
                }]
            }
        },
        computed: {
            type() {
                return this.$route.params.type
            }
        },
        methods: {
            getlist(index) {
                let tab = this.tab[index]
                let params = tab.params
                if (tab.loading === true) {
                    this.$set(tab, 'loading', false)
                    this.get(this.cate[this.type].url, params).then(res => {
                        let info = res.data.data
                        info.forEach(item => {
                            tab.list.push(item)
                        })
                        this.$nextTick(() => {
                            this.$refs.scroller[index].reset()
                        })
                        if (!info.length || !params.limit || info.length < params.limit) {
                            this.$set(params, 'page', false)
                            return
                        }
                        this.$set(params, 'page', params.page + 1)
                        this.$set(tab, 'loading', true)
                    })
                }
            },
            dateFormat(time, format) {
                return dateFormat(time, format)
            }
        },
        created() {
            this.getlist(0)
            this.Data({title: this.cate[this.type]['title']})
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
    .project,.vux-slider {
        height: 100%;
    }
</style>
