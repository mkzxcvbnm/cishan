<template>
    <div class="ibox1 center_box bf">
        <flexbox :gutter="0" wrap="wrap" justify="space-around">
            <flexbox-item :span="1/4">
                <router-link to="/project/0">
                    <div class="ibox1_item">
                        <img src="~img/ibox1_1.png" width="48" height="48" alt="">
                        <p>一对一资助</p>
                    </div>
                </router-link>
            </flexbox-item>
            <flexbox-item :span="1/4">
                <router-link to="/project/1">
                    <div class="ibox1_item">
                        <img src="~img/ibox1_2.png" width="48" height="48" alt="">
                        <p>众筹资助</p>
                    </div>
                </router-link>
            </flexbox-item>
            <flexbox-item :span="1/4">
                <router-link to="/love">
                    <div class="ibox1_item">
                        <img src="~img/ibox1_3.png" width="48" height="48" alt="">
                        <p>爱心轮椅</p>
                    </div>
                </router-link>
            </flexbox-item>
            <flexbox-item :span="1/4">
                <router-link to="/lovelist">
                    <div class="ibox1_item">
                        <img src="~img/ibox1_4.png" width="48" height="48" alt="">
                        <p>爱心榜</p>
                    </div>
                </router-link>
            </flexbox-item>
            <flexbox-item :span="1/4">
                <router-link to="/zc/1">
                    <div class="ibox1_item">
                        <img src="~img/ibox1_5.png" width="48" height="48" alt="">
                        <p>我要行善</p>
                    </div>
                </router-link>
            </flexbox-item>
            <flexbox-item :span="1/4">
                <router-link to="/help">
                    <div class="ibox1_item">
                        <img src="~img/ibox1_6.png" width="48" height="48" alt="">
                        <p>我要求助</p>
                    </div>
                </router-link>
            </flexbox-item>
            <flexbox-item :span="1/4">
                <router-link to="/news/3">
                    <div class="ibox1_item">
                        <img src="~img/ibox1_7.png" width="48" height="48" alt="">
                        <p>财务公开</p>
                    </div>
                </router-link>
            </flexbox-item>
            <flexbox-item :span="1/4">
                <router-link to="/show/about">
                    <div class="ibox1_item">
                        <img src="~img/ibox1_8.png" width="48" height="48" alt="">
                        <p>关于我们</p>
                    </div>
                </router-link>
            </flexbox-item>
        </flexbox>
    </div>
</template>
<script>
    import { Flexbox, FlexboxItem } from 'vux'
    export default {
        name: 'ibox1',
        components: {
            Flexbox,
            FlexboxItem
        },
        data() {
            return {
            }
        }
    }
</script>
<style lang="scss" scoped>
    @import '~css/mk.scss';
    .ibox1 {
        padding-top: 30px;
        padding-bottom: 30px;
        .vux-flexbox {
            margin-bottom: -15px;
        }
        .ibox1_item {
            margin-bottom: 15px;
            font-size: 12px;
            text-align: center;
        }
    }
</style>
