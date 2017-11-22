<template>
    <div id="app">
        <transition name="fade" mode="out-in" appear>
            <loading v-model="isloading" position="absolute"></loading>
        </transition>
        <view-box class="oh" ref="viewBox" body-padding-top="46px" body-padding-bottom="80px">
            <mk-header></mk-header>
            <transition
            @after-enter="afterEnter"
            :name="'translateX-' + (isback ? 'out' : 'in')"
            appear
            >
                <router-view></router-view>
            </transition>
            <mk-footer></mk-footer>
       </view-box>
    </div>
</template>
<script>
    import { mapState } from 'vuex'
    import mkHeader from '@/components/header'
    import mkFooter from '@/components/footer'
    import { ViewBox, Loading, Masker } from 'vux'

    export default {
        name: 'app',
        components: {
            mkHeader,
            mkFooter,
            ViewBox,
            Loading,
            Masker
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
                'isloading'
            ])
        },
        watch: {
            '$route' (to, from) {
                this.$set(this, 'to', to)
                this.$set(this, 'from', from)
            }
        },
        methods: {
            afterEnter() {
                const array = require('lodash/array')
                this.$store.commit('ISBACK', false)
                if (array.findIndex(['zc'], o => { return o === this.to.name }) === -1) {
                    this.$store.dispatch('Data', {showFoot: true})
                }
            }
        },
        created() {
            const jsonp = require('jsonp')
            jsonp(this.api + 'api/wxauth/getAsCookie', null, (err, data) => {
                if (err) {
                    console.error(err.message)
                } else {
                    if (data.data.openid === null || data.data.access_token === null) {
                        window.location.href = this.api + 'api/wxauth?return_url=' + window.location.href
                    } else {
                        this.post(this.api + 'api/wxauth/getUserInfo', {openid: data.data.openid, access_token: data.data.access_token}).then(res => {
                            console.log(res)
                        })
                    }
                }
            })
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
