<template>
    <div class="banner">
        <swiper loop auto dots-position="center" height="200px" :show-desc-mask="false" :list="list"></swiper>
    </div>
</template>
<script>
    import { mapState, mapActions } from 'vuex'
    import { Swiper } from 'vux'
    export default {
        name: 'banner',
        components: {
            Swiper
        },
        data() {
            return {
                list: [{
                    img: require('img/banner.jpg')
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
            ])
        },
        created() {
            this.get('http://jiujiu99.yuanhang.org/api/index/Slide').then(res => {
                let info = res.data.data.map((item, index) => ({
                    img: item.image
                }))
                this.$set(this, 'list', info)
            })
        }
    }
</script>
<style lang="scss">
    .banner {
        .vux-slider > .vux-indicator,
        .vux-slider .vux-indicator-right {
            > a > .vux-icon-dot {
                width: 11px;
                height: 11px;
                border-radius: 50%;
                background-color: rgba(255,255,255,.5);
                &.active {
                    background-color: #fff;
                }
            }
        }
    }
</style>
