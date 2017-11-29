<template>
    <div class="verifyPhone">
        <group>
            <x-input placeholder="请输入您的手机号码" v-model="tel">
                <img class="icon" slot="label" src="~img/vicon1.png" width="13">
            </x-input>
            <x-input placeholder="请输入您的验证码" v-model="vcode">
                <img class="icon" slot="label" src="~img/vicon2.png" width="15.5">
                <font slot="right">
                    <span class="color" v-if="vcode_time">重新发送 {{vcode_time}}s</span>
                    <span class="color" v-else @click="getvcode">获取验证码</span>
                </font>
            </x-input>
        </group>
        <div class="center mt20">
            <x-button text="提交" :disabled="disabled || !tel || !vcode" @click.native="submit" type="primary"></x-button>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex'
    import { Group, Cell, XInput, XButton } from 'vux'
    export default {
        name: 'verifyPhone',
        components: {
            Group,
            Cell,
            XInput,
            XButton
        },
        data() {
            return {
                tel: null,
                vcode_time: null,
                vcode: null,
                disabled: false
            }
        },
        computed: {
            ...mapState([
                'backurl'
            ])
        },
        methods: {
            getvcode() {
                if (!this.tel || !this.tel.match(/^1\d{10}$/)) {
                    this.$vux.alert.show('手机号码错误')
                    return;
                }
                this.vcode_time = 60
                let timer = setInterval(() => {
                    this.vcode_time--
                    if (this.vcode_time === 0) {
                        clearInterval(timer)
                    }
                }, 1000)
                this.post(this.api + 'api/action/MobileCode', {
                    tel: this.tel
                }).then(res => {
                    let info = res.data
                    if (info.code === 0) {
                        this.$vux.alert.show({
                            title: '成功',
                            content: '发送成功'
                        })
                    } else {
                        this.$vux.alert.show({
                            title: '错误',
                            content: info.msg
                        })
                        this.$set(this, 'vcode_time', 3)
                    }
                }).catch(error => {
                    this.$vux.alert.show({
                        title: '错误',
                        content: '请求错误'
                    })
                    console.log(error)
                    this.$set(this, 'vcode_time', 3)
                })
            },
            submit() {
                if (!this.tel || !this.tel.match(/^1\d{10}$/)) {
                    this.$vux.alert.show({
                        title: '提示',
                        content: '手机号码错误'
                    })
                    return;
                }
                if (!this.vcode) {
                    this.$vux.alert.show({
                        title: '提示',
                        content: '请输入验证码'
                    })
                    return
                }
                this.disabled = true
                this.post(this.api + 'api/action/Bind', {
                    uid: this.user.uid,
                    tel: this.tel,
                    telcode: this.vcode
                }).then(res => {
                    let info = res.data
                    if (info.code === '0') {
                        this.user.mobile = this.tel
                        this.User(this.user)
                        this.$vux.alert.show({
                            title: '成功',
                            content: '绑定成功',
                            onHide: function() {
                                if (this.backurl) {
                                    this.$router.replace(this.backurl)
                                } else {
                                    this.$router.replace('user')
                                }
                            }.bind(this)
                        })
                    } else {
                        this.$vux.alert.show({
                            title: '错误',
                            content: info.msg
                        })
                        this.disabled = false
                    }
                }).catch(error => {
                    this.$vux.alert.show({
                        title: '错误',
                        content: '请求错误'
                    })
                    console.log(error)
                    this.disabled = false
                })
            }
        },
        created() {
            this.Data({title: '验证手机'})
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
    .verifyPhone {
        .icon {
            padding: 0 15px 0 12px;
            display: block;
        }
    }
</style>
