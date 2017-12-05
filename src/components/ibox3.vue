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
                    已有<span>{{number}}</span>人领用过爱心轮椅/拐杖
                </div>
            </flexbox-item>
        </flexbox>
        <div class="ibox3_list">
            <transition-group name="fade" mode="out-in" appear>
                <flexbox v-for="(item, index) in list" :key="index" class="ibox3_list_item center_box_full" :gutter="0" wrap="wrap" justify="space-around">
                        <flexbox-item :span="2/3">
                            <div class="ibox3_list_l">
                                <span>{{item.name.toString().substr(0,1)+item.name.toString().substr(1).replace(/./g, '*')}}</span><span>{{!user.mobile ? '' : (item.mobile.toString().substr(0,4) + "****" + item.mobile.toString().substr(8))}}</span>
                                <p>已于{{item_date(item.begin_time)}}前&nbsp;&nbsp;&nbsp;成功申请{{item.type == 1 ? '拐杖' : '轮椅'}}</p>
                            </div>
                        </flexbox-item>
                        <flexbox-item :span="1/3">
                            <div class="ibox3_tit_r">
                                <a v-if="item.status == 0" class="ibox3_list_btn type_dz" href="javascript:;">短租</a>
                                <a v-else class="ibox3_list_btn type_zs" href="javascript:;">赠送</a>
                            </div>
                        </flexbox-item>
                </flexbox>
            </transition-group>
        </div>
        <div class="ibox3_more" v-if="params.page" @click="getlist">
            <i v-show="!loading" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
            查看更多<i class="fa fa-angle-down" aria-hidden="true"></i>
        </div>
    </div>
</template>
<script>
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
                number: 0,
                list: [],
                params: {
                    limit: 3,
                    page: 1,
                    order: 1
                }
            }
        },
        methods: {
            item_date(time) {
                let day = Math.ceil((new Date().getTime() / 1000 - time) / 86400)
                return day > 30 && day <= 90 ? '三个月' : day + '天'
            },
            getlist() {
                if (this.loading) {
                    this.loading = false
                    this.get(this.api + 'api/index/SyrItem', this.params).then(res => {
                        let info = res.data.data
                        this.$set(this, 'number', info.num)
                        info.list.forEach(item => {
                            this.list.push(item)
                        })
                        if (this.transfer._events.success) {
                            this.transfer.$emit('bindSticky')
                        }
                        this.loading = true
                        this.params.page++
                        if (!info.list.length || !this.params.limit || info.list.length < this.params.limit) {
                            this.params.page = false
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
            white-space: nowrap;
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
