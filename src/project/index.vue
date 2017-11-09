<template>
    <div class="project">
        <div class="interval"></div>
        <sticky scroll-box="vux_view_box_body" ref="sticky" :check-sticky-support="false" :offset="46">
            <tab :line-width="2" active-color="#09bb07" v-model="active">
                <tab-item class="vux-center" :selected="active === index" v-for="(item, index) in tab" :key="index">{{item.title}}</tab-item>
            </tab>
        </sticky>
        <swiper v-model="active" height="100%" :show-dots="false" @on-index-change="getlist">
            <swiper-item v-for="(item, index) in tab" :key="index">
                <scroller lock-x height="100%" @on-scroll-bottom="getlist(index)" ref="scroller" :scroll-bottom-offst="100">
                    <div class="zz_list">
                        <router-link v-for="(item2, index2) in data['project_' + type + '_' + index]" :key="index2" :to="{ name: cate[type]['router'], params: { id: item2.id } }">
                        <div class="zz_list_item">
                            <div class="zz_list_l">
                                <div class="zz_list_img cover" :style="{backgroundImage: 'url('+item2.pic+')'}"></div>
                            </div>
                            <div class="zz_list_r">
                                <div class="zz_list_text">
                                    <div class="zz_list_tit"><h2>{{item2.title}}</h2><span v-if="item2.id%2">置顶</span></div>
                                    <div class="zz_list_con">
                                        <div class="zz_list_type">
                                            <span v-if="index2%3 == 1" class="type_conduct">进行中</span>
                                            <span v-else-if="index2%3 == 2" class="type_end">已完成</span>
                                            <span v-else>预告中</span>
                                        </div>
                                        <div>还剩<span class="color_number">{{item2.cid}}</span>个名额</div>
                                    </div>
                                </div>
                                <x-progress class="zz_list_progress" :percent="item2.mid" :show-cancel="false"></x-progress>
                                <div class="zz_list_info">
                                    <div v-if="index2%3 == 1">
                                        <span>剩于时间：</span>
                                        <clocker :time="dateFormat(item2.update_time + 2e12, 'YYYY-MM-DD HH:mm:ss')">
                                            <span>%H小时</span><span>%M分钟</span><span>%S秒</span>
                                        </clocker>
                                    </div>
                                    <div v-else-if="index2%3 == 2">{{dateFormat(item2.update_time, 'MM月DD日 HH:mm')}} 结束</div>
                                    <div v-else>{{dateFormat(item2.update_time, 'MM月DD日 HH:mm')}} 开始</div>
                                </div>
                            </div>
                        </div>
                        </router-link>
                        <load-more v-if="tab[index]['params']['pages'] !== false" tip="loading"></load-more>
                        <load-more v-else :show-loading="false" tip="暂无数据" background-color="transparent"></load-more>
                    </div>
                </scroller>
            </swiper-item>
        </swiper>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import { Tab, TabItem, Sticky, Swiper, SwiperItem, XProgress, Scroller, LoadMore, dateFormat, Clocker } from 'vux'
    export default {
        name: 'project',
        components: {
            Tab,
            TabItem,
            Sticky,
            Swiper,
            SwiperItem,
            XProgress,
            Scroller,
            LoadMore,
            Clocker
        },
        data() {
            return {
                cate: [
                    {
                        url: 'YdyItem',
                        title: '一对一资助',
                        router: 'projectShow1'
                    },
                    {
                        url: 'ZcItem',
                        title: '众筹资助',
                        router: 'projectShow2'
                    }
                ],
                active: 0,
                tab: [{
                    title: '全部',
                    params: {
                        cid: 0,
                        limit: 6,
                        pages: 1
                    },
                    loading: true
                },
                {
                    title: '预告中',
                    params: {
                        cid: 35,
                        limit: 6,
                        pages: 1
                    },
                    loading: true
                },
                {
                    title: '进行中',
                    params: {
                        cid: 36,
                        limit: 6,
                        pages: 1
                    },
                    loading: true
                },
                {
                    title: '已结束',
                    params: {
                        cid: 38,
                        limit: 6,
                        pages: 1
                    },
                    loading: true
                }]
            }
        },
        computed: {
            ...mapState([
                'data'
            ]),
            type() {
                return this.$route.params.type
            }
        },
        methods: {
            ...mapActions([
                'Data',
                'PushData'
            ]),
            getlist(index) {
                let data = this.tab[index]
                let params = data.params
                if (data.loading === true) {
                    this.$set(data, 'loading', false)
                    this.post('http://www.yunucms.cn/index.php/api/list', params).then(res => {
                        let info = res.data.data
                        this.PushData({['project_' + this.type + '_' + index]: info})
                        this.$nextTick(() => {
                            this.$refs.scroller[index].reset()
                        })
                        this.$set(data, params.pages, index)
                        if (!info.length || !params.limit || info.length < params.limit) {
                            this.$set(params, 'pages', false)
                            return
                        }
                        this.$set(params, 'pages', params.pages + 1)
                        this.$set(data, 'loading', true)
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
