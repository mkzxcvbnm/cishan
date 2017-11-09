<template>
    <div class="ibox4 bf">
        <sticky scroll-box="vux_view_box_body" ref="sticky" :check-sticky-support="false" :offset="46">
            <tab :line-width="2" active-color="#09bb07">
                <tab-item class="vux-center" :selected="active === index" v-for="(item, index) in tab" @on-item-click="getlist(index)" :key="index">{{item.title}}</tab-item>
            </tab>
        </sticky>
        <div class="minh tac pd20" v-if="tab[active].loading === false">
            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
        </div>
        <div class="tac pd20 pb50" v-if="tab[active].loading === -1 && !data['ibox4_' + active].length">
            没有数据
        </div>
        <transition-group name="fade" mode="out-in" appear>
        <ul class="zz_list" v-show="active === index" v-for="(item, index) in tab" :key="index">
            <li v-for="(item2, index2) in data['ibox4_' + index]" :key="index">
                <div class="zz_list_l">
                    <div class="zz_list_img cover" :style="{backgroundImage: 'url('+require('img/banner.jpg')+')'}"></div>
                </div>
                <div class="zz_list_r">
                    <div class="zz_list_text">
                        <div class="zz_list_tit"><h2>九九助学第六期资助20名孩子九九助学第六期资助20名孩子</h2><span>置顶</span></div>
                        <div class="zz_list_con">
                            <div class="zz_list_type">
                                <span v-if="Math.random() > 0.5" class="type_conduct">进行中</span>
                                <span v-else-if="Math.random() > 0.5" class="type_end">已完成</span>
                                <span v-else>预告中</span>
                            </div>
                            <div>还剩<span class="color_number">5</span>个名额</div>
                        </div>
                    </div>
                    <x-progress class="zz_list_progress" :percent="60" :show-cancel="false"></x-progress>
                    <div class="zz_list_info">
                        <div>完成进度：75%</div>
                        <div>还剩<span class="color_number">5</span>个名额</div>
                    </div>
                </div>
            </li>
        </ul>
        </transition-group>
    </div>
</template>
<script>
    import { mapState, mapActions } from 'vuex'
    import { Tab, TabItem, Sticky, Swiper, SwiperItem, XProgress } from 'vux'
    export default {
        name: 'ibox4',
        components: {
            Tab,
            TabItem,
            Sticky,
            Swiper,
            SwiperItem,
            XProgress
        },
        data() {
            return {
                active: 0,
                tab: [{
                    title: '一对一资助',
                    params: {
                        id: 35,
                        limit: 6
                    },
                    loading: true
                },
                {
                    title: '众筹资助',
                    params: {
                        id: 36,
                        limit: 6
                    },
                    loading: true
                }]
            }
        },
        computed: {
            ...mapState([
                'data'
            ])
        },
        methods: {
            ...mapActions([
                'Data'
            ]),
            getlist(index) {
                this.$set(this, 'active', index)
                let data = this.tab[index]
                if (data.loading === true) {
                    this.$set(data, 'loading', false)
                    this.post('http://www.yunucms.cn/index.php/api/list', data.params).then(res => {
                        this.Data({['ibox4_' + index]: res.data.data})
                        this.$set(data, 'loading', -1)
                        this.$refs.sticky.bindSticky()
                    })
                }
            }
        },
        created() {
            this.getlist(0)
        }
    }
</script>
<style lang="scss" scoped>
    @import '~css/mk.scss';
    .ibox4 {
    }
    .minh {
        min-height: 1000px;
    }
</style>
