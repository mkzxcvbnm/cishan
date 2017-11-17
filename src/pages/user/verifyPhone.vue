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
            <x-button text="提交" :disabled="disabled" @click.native="submit" type="primary"></x-button>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
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
                'data'
            ])
        },
        methods: {
            ...mapActions([
                'Data'
            ]),
            getvcode() {
                if (!this.tel || !this.tel.match(/^1\d{10}$/)) {
                    this.$vux.alert.show('手机号码错误')
                    return;
                }
                this.vcode_time = 60;
                let timer = setInterval(() => {
                    this.vcode_time--;
                    if (this.vcode_time === 0) {
                        clearInterval(timer)
                    }
                }, 1000)
                this.get(this.api + 'api/index/Article', {
                    limit: 10,
                    classify: 1,
                    page: 1,
                    order: 1
                }).then(res => {
                    this.$vux.alert.show({
                        title: '成功',
                        content: '发送成功'
                    })
                    this.disabled = false
                }).catch(error => {
                    this.$vux.alert.show({
                        title: '错误',
                        content: '请求错误'
                    })
                    console.log(error)
                    this.disabled = false
                    this.vcode_time = 3
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
                this.get(this.api + 'api/index/Article', {
                    limit: 10,
                    classify: 1,
                    page: 1,
                    order: 1
                }).then(res => {
                    let t = this
                    this.$vux.alert.show({
                        title: '成功',
                        content: {name: this.name, tel: this.tel},
                        onHide() {
                            t.$router.go(-1)
                        }
                    })
                    this.disabled = false
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
