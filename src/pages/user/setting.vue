<template>
    <div class="setting">
        <group>
            <cell title="头像">
                <img :src="user.avatar ? user.avatar : require('img/avatar.png')" class="avatar" height="62">
            </cell>
            <cell title="姓名" is-link><span class="default mr20">{{user.nikename}}</span></cell>
            <cell>
                <div slot="title">手机号<span class="default ml20" v-if="!user.mobile">暂未绑定手机号码</span></div>
                <router-link class="color" slot="default" to="/verifyPhone">{{user.mobile ? user.mobile.toString().replace(/^(\d{3})\d{4}(\d{4})$/, '$1****$2') : '立即绑定'}}</router-link>
            </cell>
        </group>
    </div>
</template>

<script>
    import { Group, Cell } from 'vux'
    export default {
        name: 'setting',
        components: {
            Group,
            Cell
        },
        data() {
            return {
            }
        },
        created() {
            this.Data({title: '会员资料'})
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
    .weui-cell {
        font-size: 18px;
        line-height: 28px;
        color: #000;
        .avatar {
            border-radius: 10px;
        }
        .default {
            color: #b2b2b2;
            font-size: 16px;
        }
        .color {
            font-size: 16px;
        }
    }
</style>
