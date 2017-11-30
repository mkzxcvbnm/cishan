<template>
    <div class="chairFree">
        <div class="interval"></div>
        <div class="receive_obj bf border_b">
            <span class="tit">领取对象</span>
            <p>1.持有丹阳农保卡的农村居民；</p>
            <p>2.持有丹阳医保卡，生活有一定困难的城市居民；</p>
            <p>3.暂住在丹阳，生活有一定困难的外来务工居民；</p>
            <p>4.其他需要帮助的人群。</p>
        </div>
        <group>
            <x-input placeholder="请输入领取人姓名" ref="name" v-model="name" required></x-input>
        </group>
        <group>
            <x-input placeholder="请输入领取人手机" ref="tel" v-model="tel" type="tel" is-type="china-mobile" required></x-input>
        </group>
        <group>
            <x-input placeholder="请输入家庭详细地址（志愿者上门核实）" ref="address" v-model="address" required></x-input>
        </group>
        <group>
            <x-textarea placeholder="请输入家庭困难情况" ref="content" v-model="content" v :show-counter="false" :rows="5" autosize></x-textarea>
        </group>
        <div class="no_bg_check mt10">
            <checklist required :options="[ '您已同意' + love_type[type] + '申请协议' ]" ref="check" v-model="agree"></checklist>
            <a class="check_btn" href="javascript:;">查看协议</a>
        </div>
        <div class="center mt20">
            <x-button text="提交" :disabled="disabled" @click.native="submit" type="primary"></x-button>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex'
    import { Group, XButton, Cell, Checklist, XInput, XTextarea } from 'vux'
    export default {
        name: 'chairFree',
        components: {
            XButton,
            Group,
            Cell,
            Checklist,
            XInput,
            XTextarea
        },
        data() {
            return {
                name: null,
                tel: null,
                address: null,
                content: null,
                disabled: false,
                agree: []
            }
        },
        computed: {
            ...mapState([
                'love_type'
            ]),
            type() {
                return this.$route.params.type
            }
        },
        methods: {
            submit() {
                if (!this.agree.length) {
                    this.$vux.toast.show({
                        type: 'warn',
                        text: '需要同意协议'
                    })
                    return false
                }
                if (!this.validation([this.$refs.name, this.$refs.tel, this.$refs.address])) {
                    this.$vux.toast.show({
                        type: 'warn',
                        text: '请填写正确'
                    })
                    return false
                }
                if (this.content === null) {
                    this.$vux.toast.show({
                        type: 'warn',
                        text: '请填写困难情况'
                    })
                    return false
                }
                this.disabled = true
                this.post(this.api + 'api/action/ApplyZs', {
                    uid: this.user.uid,
                    name: this.name,
                    mobile: this.tel,
                    address: this.address,
                    depict: this.content,
                    begin_time: Math.round(new Date().getTime() / 1000),
                    type: this.type
                }).then(res => {
                    let info = res.data.data
                    if (res.data.code === '0') {
                        this.$vux.alert.show({
                            title: '成功',
                            content: '申请成功，等待审核',
                            onHide: function() {
                                this.$router.go(-1)
                            }.bind(this)
                        })
                    } else {
                        this.$vux.alert.show({
                            title: '错误',
                            content: info.msg
                        })
                    }
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
            this.Data({title: '爱心' + this.love_type[this.type] + '申请'})
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
    .chairFree {
        color: #000;
    }
    .receive_obj {
        padding: 13px 4%;
        .tit {
            line-height: 1.2;
            display: block;
            padding-bottom: 10px;
        }
    }
</style>
