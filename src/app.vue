<template>
    <div id="app">
        <view-box class="oh" ref="viewBox" body-padding-top="46px" body-padding-bottom="80px">
            <mk-header></mk-header>
            <transition
            @after-enter="afterEnter"
            :name="'translateX-' + (isback ? 'out' : 'in')"
            appear
            >
                <router-view></router-view>
            </transition>
            <!-- <transition name="fade" mode="out-in" appear>
                <keep-alive>
                    <router-view></router-view>
                </keep-alive>
            </transition> -->
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
                to: {},
                from: {}
            }
        },
        computed: {
            ...mapState([
                'isback',
                'data'
            ])
        },
        watch: {
            '$route' (to, from) {
                this.$set(this, 'to', to)
                this.$set(this, 'from', from)
            }
        },
        methods: {
            ...mapActions([
                'Data'
            ]),
            afterEnter() {
                const array = require('lodash/array')
                this.$store.commit('ISBACK', false)
                if (array.findIndex(['zc'], o => { return o === this.to.name }) === -1) {
                    this.$store.dispatch('Data', {showFoot: true})
                }
            }
        },
        created() {
            this.$nextTick(() => {
                const _ = require('lodash/function')
                let viewBox = this.$refs.viewBox
                this.Data({scrollBox: viewBox})
                viewBox.getScrollBody().addEventListener('scroll', _.throttle(() => {
                    this.Data({scrollTop: viewBox.getScrollTop()})
                }, 500))
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
        overflow: hidden;
    }
</style>
