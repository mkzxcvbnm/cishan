<template>
    <div id="app">
        <view-box ref="viewBox" body-padding-top="46px" body-padding-bottom="80px">
            <mk-header></mk-header>
            <transition name="fade" mode="out-in" appear>
                <!-- <keep-alive> -->
                    <router-view></router-view>
                <!-- </keep-alive> -->
            </transition>
            <mk-footer></mk-footer>
       </view-box>
    </div>
</template>
<script>
    import { mapState, mapActions } from 'vuex'
    import mkHeader from '@/components/header'
    import mkFooter from '@/components/footer'
    import { ViewBox } from 'vux'
    export default {
        name: 'app',
        components: {
            mkHeader,
            mkFooter,
            ViewBox
        },
        data () {
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
            const _ = require('lodash');
            this.$nextTick(() => {
                let viewBox = this.$refs.viewBox
                this.Data({scroll: viewBox})
                viewBox.getScrollBody().addEventListener('scroll', _.debounce(() => {
                    this.Data({scrollTop: viewBox.getScrollTop()})
                }, 50))
            })
        }
    }
</script>
<style lang="less">
    @import '~vux/src/styles/reset.less';
</style>
<style lang="scss">
    @import '~css/public.scss';
    #app {
        height: 100%;
    }
</style>
