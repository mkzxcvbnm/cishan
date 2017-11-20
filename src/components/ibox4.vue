<template>
    <div class="ibox4 bf">
        <sticky scroll-box="vux_view_box_body" ref="sticky" :check-sticky-support="false" :offset="46">
            <tab :line-width="2" active-color="#09bb07">
                <tab-item class="vux-center" :selected="active === index" v-for="(item, index) in tab" @on-item-click="getlist" :key="index">{{item.title}}</tab-item>
            </tab>
        </sticky>
        <transition-group name="fade" mode="out-in" appear>
        <list-ydy v-if="active == 0" key="0" :list="tab[0].list"></list-ydy>
        <list-zc v-else key="1" :list="tab[1].list"></list-zc>
        <div class="tac pd20" key="loading" v-if="tab[active].loading === false">
            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
        </div>
        <div class="tac pd20 pb50" key="none" v-if="tab[active].loading === -1 && !tab[active].list.length">
            没有数据
        </div>
        </transition-group>
    </div>
</template>
<script>
    import { Sticky, Tab, TabItem } from 'vux'
    import listYdy from '@/components/list_ydy'
    import listZc from '@/components/list_zc'
    export default {
        name: 'ibox4',
        components: {
            Sticky,
            Tab,
            TabItem,
            listYdy,
            listZc
        },
        data() {
            return {
                active: 0,
                tab: [{
                    title: '一对一资助',
                    url: this.api + 'api/index/YdyItem',
                    list: [],
                    params: {
                        page: 1,
                        limit: 6,
                        order: 1,
                        status: 'all'
                    },
                    loading: true
                },
                {
                    title: '众筹资助',
                    url: this.api + 'api/index/ZcItem',
                    list: [],
                    params: {
                        page: 1,
                        limit: 6,
                        order: 1,
                        status: 'all'
                    },
                    loading: true
                }]
            }
        },
        methods: {
            getlist(index) {
                this.$set(this, 'active', index)
                let info = this.tab[index]
                if (info.loading === true) {
                    this.$set(info, 'loading', false)
                    this.get(info.url, info.params).then(res => {
                        this.$set(info, 'list', res.data.data)
                        this.$set(info, 'loading', -1)
                    })
                }
            }
        },
        created() {
            this.getlist(0)
            this.transfer.$off('bindSticky')
            this.transfer.$on('bindSticky', () => {
                this.$refs.sticky.bindSticky()
            })
        }
    }
</script>
<style lang="scss" scoped>
    @import '~css/mk.scss';
    .ibox4 {
        min-height: 200px;
    }
    // .zz_list {
    //     .zz_list_tit {
    //         h2 {
    //             padding-right: 0;
    //         }
    //     }
    //     .zz_list_text {
    //         display: flex;
    //     }
    //     .zz_list_tit {
    //         order: 2;
    //     }
    //     .zz_list_con {
    //         flex-shrink: 0;
    //         order: 1;
    //         margin-top: 0;
    //     }
    // }
</style>
