<template>
    <div class="donation">
        <img class="top_pic" src="~img/top_pic.jpg">
        <group>
            <x-input placeholder="请输入您的姓名" ref="name" v-model="name" required></x-input>
        </group>
        <group>
            <x-input placeholder="请输入您的手机号码" ref="tel" v-model="tel" type="tel" is-type="china-mobile" required></x-input>
        </group>
        <group>
            <x-input placeholder="请输入您想要捐赠轮椅的数量" ref="num" v-model="num" type="number" required></x-input>
        </group>
        <div class="center mt20">
            <x-button text="提交" :disabled="disabled" @click.native="submit" type="primary"></x-button>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import { XInput, Group, XButton } from 'vux'
    export default {
        name: 'donation',
        components: {
            XInput,
            XButton,
            Group
        },
        data() {
            return {
                name: null,
                tel: null,
                num: null,
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
            submit() {
                if (!this.validation([this.$refs.name, this.$refs.tel, this.$refs.num])) {
                    this.$vux.toast.show({
                        type: 'warn',
                        text: '请填写正确'
                    })
                    return
                }
                // if (this.name === null || this.tel === null || this.num === null) {
                //     this.$vux.toast.show({
                //         type: 'warn',
                //         text: '请输入完整'
                //     })
                //     return
                // }
                // if (!this.tel.match(/^1\d{10}$/)) {
                //     this.$vux.toast.show({
                //         type: 'warn',
                //         text: '手机号码错误'
                //     })
                //     return
                // }
                let t = this
                if (this.num === '0' || !this.num.match(/^\d+$/)) {
                    this.$vux.toast.show({
                        type: 'warn',
                        text: '捐赠数量必须为正整数',
                        onHide() {
                            t.$refs.num.focus()
                        }
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
                        content: {name: this.name, tel: this.tel, num: this.num},
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
            this.Data({title: '捐赠闲置轮椅'})
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
</style>
