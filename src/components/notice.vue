<template>
    <div class="notice center_box bf">
        <swiper loop auto height="50px" direction="vertical" :interval=2000 :show-dots="false">
            <swiper-item v-for="(item, index) in list" :key="index">
                <router-link :to="{ name: 'show', params: { id: item.id } }"><p class="ellipsis"><span>通知</span>{{ item.title }}</p></router-link>
            </swiper-item>
        </swiper>
    </div>
</template>
<script>
    import { Swiper, SwiperItem } from 'vux'
    export default {
        name: 'notice',
        components: {
            Swiper,
            SwiperItem
        },
        data() {
            return {
                list: []
            }
        },
        created() {
            this.get(this.api + 'api/index/Article', {
                limit: 10,
                classify: 1,
                page: 1,
                order: 1
            }).then(res => {
                this.$set(this, 'list', res.data.data)
            })
        }
    }
</script>
<style lang="scss" scoped>
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
