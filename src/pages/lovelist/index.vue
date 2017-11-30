<template>
    <div class="lovelist bf">
        <img class="top_pic" src="~img/lovelist.jpg">
        <div class="interval"></div>
        <div class="list">
            <div class="list_item center_box">
                <span class="f16">近期捐赠</span>
            </div>
            <div class="list_item center_box between border_t">
                <span>姓名</span>
                <span>捐赠金额</span>
            </div>
            <div class="list_item center_box between border_t" v-for="(item, index) in list" :key="index">
                <span class="name">{{item.nikename}}</span>
                <span class="color">¥{{item.money}}</span>
            </div>
        </div>
        <div class="more" v-if="params.page" @click="getlist">
            <i v-show="!loading" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
            查看更多<i class="fa fa-angle-down" aria-hidden="true"></i>
        </div>
        <div class="interval"></div>
        <div class="font border_b">
            {{this.notice}}
        </div>
    </div>
</template>
<script>
    import { Flexbox, FlexboxItem } from 'vux'
    export default {
        name: 'lovelist',
        components: {
            Flexbox,
            FlexboxItem
        },
        data() {
            return {
                loading: true,
                number: 0,
                list: [],
                params: {
                    limit: 10,
                    page: 1,
                    order: 1
                },
                notice: `益起·微马全国系列公益跑”活动，是中华全国体育基
                        金会和微马体育基金会共同创办的。主办方计划在全国
                        百个城市开展公益跑活动，其目的是通过活动鼓励更多
                        的人参与跑步运动，推动全民健身。`
            }
        },
        methods: {
            getlist() {
                if (this.loading) {
                    this.loading = false
                    this.get(this.api + 'api/index/LoveItem', this.params).then(res => {
                        let info = res.data.data
                        info.forEach(item => {
                            this.list.push(item)
                        })
                        this.loading = true
                        this.params.page++
                        if (!info.length || !this.params.limit || info.length < this.params.limit) {
                            this.params.page = false
                            return false
                        }
                    })
                }
            }
        },
        created() {
            this.Data({title: '爱心榜'})
            this.getlist()
            this.get(this.api + 'api/index/Notice', {id: 3}).then(res => {
                let info = res.data.data
                this.$set(this, 'notice', info)
            })
        }
    }
</script>
<style lang="scss" scoped>
    @import '~css/mk.scss';
    .list {
    }
    .list_item {
        line-height: 50px;
    }
    .f16 {
        color: #000;
    }
    .name {
        color: #a3a3a3;
    }
    .more {
        text-align: center;
        line-height: 40px;
        color: $color;
        border-top: 1px solid #f1f1f1;
        i {
            font-size: 25px;
            margin-left: 10px;
            position: relative;
            top: 3px;
        }
    }
    .font {
        padding: 20px 4%;
        font-size: 14px;
        color: #6d6d6d;
        line-height: 30px;
    }
</style>
