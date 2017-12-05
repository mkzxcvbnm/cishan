<template>
    <div id="app">
        <transition name="fade" mode="out-in" appear>
            <loading v-model="isloading" position="absolute"></loading>
        </transition>
        <mk-header></mk-header>
        <view-box class="conditioner" :style="{opacity: user.openid ? 1 : 0}" ref="viewBox" body-padding-top="46px" body-padding-bottom="80px">
            <transition
            v-if="user.openid !== undefined"
            @before-enter="beforeEnter"
            @after-enter="afterEnter"
            :name="'translateX-' + (isback ? 'out' : 'in')"
            appear
            >
                <router-view></router-view>
            </transition>
        </view-box>
        <mk-footer></mk-footer>
    </div>
</template>
<script>
    import { mapState } from 'vuex'
    import mkHeader from '@/components/header'
    import mkFooter from '@/components/footer'
    import { ViewBox, Loading, Masker } from 'vux'
    const _ = require('lodash/function')
    const array = require('lodash/array')
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
            beforeEnter() {
                this.$store.dispatch('Data', {showBack: this.to.path !== '/', showFoot: false})
            },
            afterEnter() {
                this.$store.commit('ISBACK', false)
                if (array.findIndex(['zc'], o => { return o === this.to.name }) === -1) {
                    this.$store.dispatch('Data', {showFoot: true})
                }
            },
            wxauth() {
                window.location.href = this.api + 'api/wxauth?return_url=' + encodeURIComponent(window.location.href)
            }
        },
        created() {
            sessionStorage.getUserInfoCount ? sessionStorage.getUserInfoCount++ : sessionStorage.getUserInfoCount = 1
            // this.get(this.api + 'api/index/SiteInfo').then(res => {
            //     res.data.data.openid = 1
            this.post(this.api + 'api/wxauth/getUserInfo').then(res => {
                let info = res.data.data
                if (res.data.code === '0' && info.openid !== null) {
                    sessionStorage.getUserInfoCount = 0
                    this.User(info)
                    this.$store.commit('ISLOADING', false)
                    console.log(this.user)
                } else {
                    if (sessionStorage.getUserInfoCount > 3) {
                        this.$vux.alert.show({
                            title: '错误',
                            content: '获取用户授权失败',
                            buttonText: '重试',
                            onHide: function() {
                                this.wxauth()
                            }.bind(this)
                        })
                    } else {
                        this.wxauth()
                    }
                }
            })
            // this.get(this.api + 'api/wxauth/getAsCookie').then(res => {
            //     let info = res.data.data
            //     if (info.openid === null || info.access_token === null) {
            //         window.location.href = this.api + 'api/wxauth?return_url=' + window.location.href
            //     } else {
            //         this.post(this.api + 'api/wxauth/getUserInfo', {openid: info.openid, access_token: info.access_token}).then(res => {
            //             let info = res.data.data
            //             this.User(info)
            //             this.$store.commit('ISLOADING', false)
            //             console.log(this.user)
            //         })
            //     }
            // })
            this.$nextTick(() => {
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
</style>
<style lang="scss" scoped>
    #app {
        height: 100%;
        overflow: hidden;
    }
    .conditioner {
        opacity: 0;
        transition: opacity 1.5s;
        overflow: hidden;
    }
</style>
