<template>
    <div class="ibox2 bf">
        <flexbox :gutter="0" wrap="wrap" justify="space-around">
            <flexbox-item :span="1/2">
                <router-link to="/foo">
                    <div class="ibox2_item">
                        <span>申请轮椅入口</span>
                        <img src="~img/ibox2_1.png" width="32.5" height="32.5" alt="">
                    </div>
                </router-link>
            </flexbox-item>
            <flexbox-item :span="1/2">
                <router-link to="/foo">
                    <div class="ibox2_item">
                        <span>申请拐杖入口</span>
                        <img src="~img/ibox2_2.png" width="40" height="33" alt="">
                    </div>
                </router-link>
            </flexbox-item>
            <flexbox-item :span="1/2">
                <router-link to="/foo">
                    <div class="ibox2_item">
                        <span>捐赠闲置轮椅</span>
                        <img src="~img/ibox2_3.png" width="38" height="33" alt="">
                    </div>
                </router-link>
            </flexbox-item>
            <flexbox-item :span="1/2">
                <router-link to="/foo">
                    <div class="ibox2_item">
                        <span>资助轮椅项目</span>
                        <img src="~img/ibox2_4.png" width="31.5" height="33.5" alt="">
                    </div>
                </router-link>
            </flexbox-item>
        </flexbox>
    </div>
</template>
<script>
    import { mapState, mapActions } from 'vuex'
    import { Flexbox, FlexboxItem } from 'vux'
    export default {
        name: 'ibox2',
        components: {
            Flexbox,
            FlexboxItem
        },
        data() {
            return {
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
            ])
        }
    }
</script>
<style lang="scss" scoped>
    @import '~css/mk.scss';
    .ibox2 {
        margin-bottom: -1px;
        .vux-flexbox-item {
            &:nth-of-type(odd) {
                .ibox2_item {
                    padding-left: 8%;
                    padding-right: 18px;
                    border-right: 1px solid #f1f1f1;
                }
            }
            &:nth-of-type(even) {
                .ibox2_item {
                    padding-right: 8%;
                    padding-left: 19px;
                    img {
                        margin-right: 2px;
                    }
                }
            }
        }
        .ibox2_item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 108px;
            border-bottom: 1px solid #f1f1f1;
            color: $color;
            font-size: 16px;
        }
    }
</style>
