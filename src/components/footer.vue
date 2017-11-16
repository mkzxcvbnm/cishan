<template>
    <div class="footer" :class="{footer_none: data.showFoot === false}">
        <tabbar>
            <tabbar-item :selected="to.path === '/'" link="/">
                <img slot="icon" src="~img/icon_nav1.png">
                <img slot="icon-active" src="~img/icon_nav1a.png">
                <span slot="label">首页</span>
            </tabbar-item>
            <tabbar-item :selected="to.path === '/news/2'" link="/news/2">
                <img slot="icon" src="~img/icon_nav2.png">
                <img slot="icon-active" src="~img/icon_nav2a.png">
                <span slot="label">动态</span>
            </tabbar-item>
            <tabbar-item icon-class="icon_navc">
                <img slot="icon" src="~img/icon_navc.png">
                <span slot="label">关注</span>
            </tabbar-item>
            <tabbar-item :selected="to.path === '/help'" link="/help">
                <img slot="icon" src="~img/icon_nav3.png">
                <img slot="icon-active" src="~img/icon_nav3a.png">
                <span slot="label">求助</span>
            </tabbar-item>
            <tabbar-item :selected="to.path === '/user'" link="/user">
                <img slot="icon" src="~img/icon_nav4.png">
                <img slot="icon-active" src="~img/icon_nav4a.png">
                <span slot="label">我的</span>
            </tabbar-item>
        </tabbar>
    </div>
</template>
<script>
    import { mapState } from 'vuex'
    import { Tabbar, TabbarItem } from 'vux'
    export default {
        name: 'footer',
        components: {
            Tabbar,
            TabbarItem
        },
        data() {
            return {
                to: '/'
            }
        },
        computed: {
            ...mapState([
                'data'
            ])
        },
        watch: {
            '$route' (to, from) {
                this.$set(this, 'to', to)
            }
        }
    }
</script>
<style lang="scss">
    .footer {
        &.footer_none {
            .weui-tabbar {
                opacity: 0;
                transform: translate3d(0, 150%, 0);
            }
        }
        .weui-tabbar {
            will-change: transform;
            transition: all .5s;
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
