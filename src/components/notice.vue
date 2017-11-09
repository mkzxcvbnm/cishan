<template>
    <div class="notice center_box bf">
        <swiper loop auto height="50px" direction="vertical" :interval=2000 :show-dots="false">
            <swiper-item v-for="(item, index) in data['notice']" :key="index">
                <p class="ellipsis"><span>通知</span>{{ item.title }}</p>
            </swiper-item>
        </swiper>
    </div>
</template>
<script>
    import { mapState, mapActions } from 'vuex'
    import { Swiper, SwiperItem } from 'vux'
    export default {
        name: 'notice',
        components: {
            Swiper,
            SwiperItem
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
        },
        created() {
            this.post('http://www.yunucms.cn/index.php/api/list', {
                limit: 5
            }).then(res => {
                this.Data({notice: res.data.data})
            })
        }
    }
</script>
<style lang="scss">
    @import '~css/mk.scss';
    .notice {
        line-height: 50px;
        span {
            margin-right: 10px;
            padding: 3px 5px;
            border: 1px solid $color;
            border-radius: 5px;
            font-size: 12px;
            color: $color;
        }
    }
</style>
