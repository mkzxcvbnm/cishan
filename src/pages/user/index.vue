<template>
    <div class="user">
        <div class="user_info">
            <router-link class="setting" to="/setting"></router-link>
            <div class="info">
                <x-img :src="require('img/avatar.png')" :default-src="require('img/avatar.png')" class="avatar"></x-img>
                <div class="name f18 pt5">PeterPan张三</div>
                <div class="tel">138****5175</div>
            </div>
        </div>
        <group class="mt15">
            <cell title="我参与的一对一资助项目" is-link link="/myYdy">
                <img class="mr15 vam" slot="icon" width="19" src="~img/uicon1.png">
            </cell>
            <cell title="我参与的众筹资助项目" is-link link="/myZc">
                <img class="mr15 vam" slot="icon" width="21.5" src="~img/uicon2.png">
            </cell>
        </group>
        <group class="mt15">
            <cell title="我参与的爱心轮椅项目">
                <img class="mr15 vam" slot="icon" width="19.5" src="~img/uicon3.png">
            </cell>
            <div class="user_pro">
                <router-link to="/myApply/2"><img src="~img/uicon5.png" width="30">轮椅申请</router-link>
                <router-link to="/myApply/1"><img src="~img/uicon6.png" width="28.5">拐杖申请</router-link>
                <router-link to="/myDonation"><img src="~img/uicon7.png" width="29">实物捐赠</router-link>
                <router-link to=""><img src="~img/uicon8.png" width="27.5">现金捐赠</router-link>
            </div>
        </group>
        <group class="mt15">
            <cell title="我的求助" is-link link="/myHelp">
                <img class="mr15 vam" slot="icon" width="21" src="~img/uicon4.png">
                <badge text="1"></badge>
            </cell>
        </group>
    </div>
</template>

<script>
    import { XImg, Group, Cell, Badge } from 'vux'
    export default {
        name: 'user',
        components: {
            XImg,
            Group,
            Cell,
            Badge
        },
        data() {
            return {
            }
        },
        created() {
            this.Data({title: '个人中心'})
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
    .user {
        .weui-cell {
            font-size: 16px;
            line-height: 28px;
            color: #757575;
        }
        .vux-badge {
            font-size: 16px;
            background-color: #f00;
            width: 22px;
            height: 22px;
            line-height: 22px;
            border-radius: 11px;
            margin-right: 20px;
            vertical-align: baseline;
        }
        .user_pro {
            padding: 25px 15px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            a {
                width: 70px;
                text-align: center;
                color: #757575;
                font-size: 13px;
            }
            img {
                display: block;
                margin: 0 auto 10px;
            }
        }
    }
    .user_info {
        height: 150px;
        background: $color url(~img/user_bg.jpg) no-repeat left bottom / 100%;
        position: relative;
        .setting {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 23px;
            height: 23px;
            background: url(~img/user_set.png) no-repeat center / 100%;
        }
        .info {
            padding-top: 35px;
            padding-left: 15px;
            color: #fff;
        }
        .tel {
            display: inline-block;
            padding: 0 15px 0 27px;
            margin-top: 5px;
            height: 25px;
            line-height: 25px;
            border-radius: 12.5px;
            background: rgba(0,0,0,.16) url(~img/phone.png) no-repeat 12px center / 9px 16px;
            overflow: hidden;
        }
        .avatar {
            width: 69.5px;
            height: 69.5px;
            border-radius: 50%;
            float: left;
            margin-left: 12.5px;
            margin-right: 12.5px;
        }
    }
</style>
