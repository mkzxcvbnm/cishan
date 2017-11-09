<template>
    <div class="ibox3 bf">
        <flexbox class="ibox3_tit center_box_full" :gutter="0" wrap="wrap" justify="space-around">
            <flexbox-item :span="1/3">
                <div class="ibox3_tit_l">
                    受益人数
                </div>
            </flexbox-item>
            <flexbox-item :span="2/3">
                <div class="ibox3_tit_r">
                    已有<span>66</span>人领用过爱心轮椅/拐杖
                </div>
            </flexbox-item>
        </flexbox>
        <div class="ibox3_list">
            <transition-group name="fade" mode="out-in" appear>
                <flexbox v-for="(item, index) in data['beneficiary']" :key="item.id" class="ibox3_list_item center_box_full" :gutter="0" wrap="wrap" justify="space-around">
                        <flexbox-item :span="2/3">
                            <div class="ibox3_list_l">
                                <span>{{item.title.toString().substr(0,1)+item.title.toString().substr(1).replace(/./g, '*')}}</span><span>{{item.title.toString().substr(0,4)+"****"+item.title.toString().substr(8)}}</span>
                                <p>已于{{item_date(item.update_time)}}前&nbsp;&nbsp;&nbsp;成功申请轮椅</p>
                            </div>
                        </flexbox-item>
                        <flexbox-item :span="1/3">
                            <div class="ibox3_tit_r">
                                <a :class="['ibox3_list_btn', item.id % 2 == 1 ? 'type_dz' : 'type_zs']" href="javascript:;">{{item.id % 2 == 1 ? '短租' : '赠送'}}</a>
                            </div>
                        </flexbox-item>
                </flexbox>
            </transition-group>
        </div>
        <div class="ibox3_more" v-if="params.pages" @click="getlist">
            <i v-show="!loading" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
            查看更多<i class="fa fa-angle-down" aria-hidden="true"></i>
        </div>
    </div>
</template>
<script>
    import { mapState, mapActions } from 'vuex'
    import { Flexbox, FlexboxItem } from 'vux'
    export default {
        name: 'ibox3',
        components: {
            Flexbox,
            FlexboxItem
        },
        data() {
            return {
                loading: true,
                params: {
                    cid: 35,
                    limit: 3,
                    pages: 1
                }
            }
        },
        computed: {
            ...mapState([
                'data'
            ])
        },
        methods: {
            ...mapActions([
                'PushData'
            ]),
            item_date(time) {
                let day = Math.ceil((new Date().getTime() / 1000 - time) / 86400)
                return day > 30 && day <= 90 ? '三个月' : day + '天'
            },
            getlist() {
                if (this.loading) {
                    this.loading = false
                    this.post('http://www.yunucms.cn/index.php/api/list', this.params).then(res => {
                        this.PushData({beneficiary: res.data.data})
                        try {
                            window.mk.$emit('bindSticky')
                        } catch (error) {
                            console.log(error)
                        }
                        this.loading = true
                        this.params.pages++
                        if (!res.data.data.length || !this.params.limit || res.data.data.length < this.params.limit) {
                            this.$set(this.params, 'pages', false)
                            return
                        }
                    })
                }
            }
        },
        created() {
            this.getlist()
        }
    }
</script>
<style lang="scss" scoped>
    @import '~css/mk.scss';
    .ibox3 {
    }
    .ibox3_tit {
        line-height: 40px;
        .ibox3_tit_l {
            padding-left: 30px;
            background: url(~img/ibox3_1.png) no-repeat left center / 21.5px 16px;
            font-size: 17px;
            color: #000;
        }
        .ibox3_tit_r {
            text-align: right;
            span {
                color: $color;
            }
        }
    }
    .ibox3_list {
        .ibox3_list_item {
            height: 102.5px;
            border-top: 1px solid #f1f1f1;
        }
        .ibox3_list_l {
            padding-left: 10px;
            span + span {
                padding-left: 25px;
            }
            p {
                font-size: 13px;
                color: #9d9d9d;
            }
        }
        .ibox3_list_btn {
            float: right;
            width: 60px;
            line-height: 24px;
            text-align: center;
            font-size: 14px;
            border-radius: 3px;
            &.type_dz {
                border: 1px solid $color;
                color: $color;
            }
            &.type_zs {
                border: 1px solid #ff5a00;
                color: #ff5a00;
            }
        }
    }
    .ibox3_more {
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
</style>
