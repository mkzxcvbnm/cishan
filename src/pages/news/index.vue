<template>
    <div class="news" ref="scroll_el">
        <div class="interval"></div>
        <div class="news_list bf">
            <router-link :to="{ name: 'show', params: { id: item.id } }" v-for="(item, index) in list" :key="index"><div class="news_item center_box pt10 pb10 border_b">
                <div class="posr">
                    <h3>{{item.title}}</h3>
                    <p>{{dateFormat(item.create_time * 1000, 'YYYY-MM-DD')}}</p>
                    <span v-if="item.stick">置顶</span>
                </div>
            </div></router-link>
        </div>
        <load-more v-if="params.page && !loading" tip="正在加载"></load-more>
        <load-more v-if="!params.page" :show-loading="false" tip="暂无数据" background-color="transparent"></load-more>
    </div>
</template>

<script>
    import { mapState } from 'vuex'
    import { dateFormat, LoadMore } from 'vux'
    export default {
        name: 'news',
        components: {
            LoadMore
        },
        data() {
            return {
                loading: true,
                list: [],
                params: {
                    limit: 3,
                    page: 1,
                    order: 1,
                    classify: this.cid()
                }
            }
        },
        computed: {
            ...mapState([
                'news_type'
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
        watch: {
            '$route' (to, from) {
                this.list = []
                this.params.page = 1
                this.params.classify = this.cid()
                this.getlist()
                this.Data({title: this.news_type[this.$route.params.cid]})
            }
        },
        methods: {
            dateFormat(time, format) {
                return dateFormat(time, format)
            },
            cid() {
                return this.$route.params.cid !== '0' ? this.$route.params.cid : 'all'
            },
            getlist() {
                if (this.loading) {
                    this.loading = false
                    this.get(this.api + 'api/index/Article', this.params).then(res => {
                        let info = res.data.data
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
            this.Data({title: this.news_type[this.$route.params.cid]})
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
    .news_item {
        h3 {
            font-weight: normal;
        }
        p {
            color: #a3a3a3;
            font-size: 12px;
        }
        span {
            position: absolute;
            right: 0;
            top: 50%;
            margin-top: -9px;
            width: 35px;
            height: 18px;
            line-height: 18px;
            text-align: center;
            color: $color;
            border: 1px solid $color;
            border-radius: 3px;
            font-size: 12px;
        }
    }
</style>
