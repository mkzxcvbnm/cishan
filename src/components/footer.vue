<template>
    <div class="footer">
        <transition
        name="footer"
        mode="out-in"
        appear
        >
            <tabbar v-show="data.showFoot" v-model="index">
                <tabbar-item link="/">
                    <img slot="icon" src="~img/icon_nav1.png">
                    <img slot="icon-active" src="~img/icon_nav1a.png">
                    <span slot="label">首页</span>
                </tabbar-item>
                <tabbar-item link="/news/2">
                    <img slot="icon" src="~img/icon_nav2.png">
                    <img slot="icon-active" src="~img/icon_nav2a.png">
                    <span slot="label">动态</span>
                </tabbar-item>
                <tabbar-item icon-class="icon_navc">
                    <img slot="icon" src="~img/icon_navc.png">
                    <span slot="label">关注</span>
                </tabbar-item>
                <tabbar-item link="/help">
                    <img slot="icon" src="~img/icon_nav3.png">
                    <img slot="icon-active" src="~img/icon_nav3a.png">
                    <span slot="label">求助</span>
                </tabbar-item>
                <tabbar-item link="/user">
                    <img slot="icon" src="~img/icon_nav4.png">
                    <img slot="icon-active" src="~img/icon_nav4a.png">
                    <span slot="label">我的</span>
                </tabbar-item>
            </tabbar>
        </transition>
    </div>
</template>
<script>
    import { Tabbar, TabbarItem } from 'vux'
    export default {
        name: 'footer',
        components: {
            Tabbar,
            TabbarItem
        },
        data() {
            return {
                index: 0
            }
        },
        watch: {
            '$route' (to, from) {
                this.$set(this, 'index', -1)
                if (to.path === '/') {
                    this.$set(this, 'index', 0)
                }
                if (to.path === '/news/2') {
                    this.$set(this, 'index', 1)
                }
                if (to.path === '/help') {
                    this.$set(this, 'index', 3)
                }
                if (to.path === '/user') {
                    this.$set(this, 'index', 4)
                }
            }
        }
    }
</script>
<style lang="scss">
    .footer {
        .weui-tabbar {
            box-shadow: 0 -5px 25px #ccc;
        }
        .weui-tabbar__item {
            padding-top: 7px;
        }
        .weui-tabbar__icon {
            width: 26px;
            height: 26px;
        }
        .weui-tabbar__label {
            font-size: 11px;
            line-height: 2;
        }
        .icon_navc {
            img {
                position: absolute;
                left: 50%;
                top: 50%;
                width: 58px;
                height: 58px;
                margin-top: -44px;
                margin-left: -29px;
            }
        }
    }
</style>
