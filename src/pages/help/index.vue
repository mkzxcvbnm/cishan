<template>
    <div class="chairFree">
        <div class="center">
            <div class="font">
                {{this.notice}}
            </div>
            <checker
            class="between"
            v-model="type"
            default-item-class="check"
            selected-item-class="check_active"
            >
                <checker-item v-for="v in type_list" :key="v.key" :value="v.key">{{v.value}}</checker-item>
            </checker>
            <group>
                <x-input placeholder="请输入您的姓名" ref="name" v-model="name" required></x-input>
            </group>
            <group>
                <x-input placeholder="请输入您的手机号码" ref="tel" v-model="tel" type="tel" is-type="china-mobile" required></x-input>
            </group>
            <group>
                <x-textarea placeholder="请输入真实的困难情况和具体求助内容，尽量详细描述" ref="content" v-model="content" v :show-counter="false" :rows="5" autosize></x-textarea>
            </group>
            <div class="mt25 mb20">
                <x-button text="提交" :disabled="disabled || !name || !tel || !content" @click.native="submit" type="primary"></x-button>
            </div>
            <div class="help_list">
                <div class="tit">
                    <span>求助内容</span>
                </div>
                <div class="help_list_item" v-for="(item, index) in list" :key="index">
                    <div class="item_name">姓名：{{item.name.toString().substr(0,1)+item.name.toString().substr(1).replace(/./g, '*')}}</div>
                    <div class="item_time">申请时间：{{dateFormat(item.create_time * 1000, 'YYYY年MM月DD日')}}</div>
                    <div class="item_content">求助内容：{{item.content}}</div>
                    <div v-if="!!item.mark" class="color">官方回复：{{item.mark}}</div>
                    <div class="item_type">{{['助学求助', '轮椅求助', '心愿求助', '其他求助'][item.type]}}</div>
                </div>
                <div class="no_data" v-if="!params.page && !list.length">暂无数据</div>
                <div class="more" v-if="params.page" @click="getlist">
                    <i v-show="!loading" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                    查看更多<i class="fa fa-angle-down" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex'
    import { Group, XButton, Cell, Checklist, XInput, XTextarea, Checker, CheckerItem, dateFormat } from 'vux'
    export default {
        name: 'chairFree',
        components: {
            XButton,
            Group,
            Cell,
            Checklist,
            XInput,
            XTextarea,
            Checker,
            CheckerItem
        },
        data() {
            return {
                type_list: [
                    {key: 0, value: '助学求助'},
                    {key: 1, value: '轮椅求助'},
                    {key: 2, value: '心愿求助'},
                    {key: 3, value: '其他求助'}
                ],
                type: 0,
                name: null,
                tel: null,
                content: null,
                disabled: false,
                list: [],
                loading: true,
                params: {
                    limit: 10,
                    page: 1,
                    order: 1,
                    type: 'all'
                },
                notice: `以前每逢夏季，村民就淌水过河，冬季则搭着简
                易浮桥。2006年村民听说要修高一座便民桥了甭
                提多高兴，谁知修了一半却停下了。由于是修建
                高速公路辅助工程，去年高速公路就已经通车了，
                而留下半截桥却无人问津。`
            }
        },
        computed: {
            ...mapState([
                'love_type'
            ])
        },
        methods: {
            dateFormat(time, format) {
                return dateFormat(time, format)
            },
            getlist() {
                if (this.loading) {
                    this.loading = false
                    this.get(this.api + 'api/index/HelpItem', this.params).then(res => {
                        let info = res.data.data
                        info.forEach(item => {
                            this.list.push(item)
                        })
                        this.loading = true
                        this.params.page++
                        if (!info.length || !this.params.limit || info.length < this.params.limit) {
                            this.params.page = false
                            return false
                        }
                    })
                }
            },
            submit() {
                if (!this.validation([this.$refs.name, this.$refs.tel])) {
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
                this.post(this.api + 'api/action/HelpDo', {
                    uid: this.user.uid,
                    type: this.type,
                    name: this.name,
                    mobile: this.tel,
                    content: this.content
                }).then(res => {
                    let info = res.data
                    if (info.code === '0') {
                        this.$vux.alert.show({
                            title: '成功',
                            content: '发布求助成功',
                            onHide: function() {
                                this.$router.go(0)
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
            this.Data({title: '我要求助'})
            this.getlist()
            this.get(this.api + 'api/index/Notice', {id: 2}).then(res => {
                let info = res.data.data
                this.$set(this, 'notice', info)
            })
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
    .font {
        border: 1px solid #e5e5e5;
        margin-top: 8px;
        margin-bottom: 8px;
        background-color: #fff;
        color: #000;
        line-height: 24px;
        padding: 10px;
    }
    .check {
        width: 79px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        border-radius: 8px;
        border: 1px solid #dadada;
        background-color: #fff;
    }
    .check_active {
        background: #fff url(~img/check.png) no-repeat right top / 20px 20px;
        border-color: $color;
    }
    .help_list {
        .tit {
            padding: 15px;
            border: 1px solid #dfdfdf;
            background-color: #fff;
            margin-bottom: 5px;
            span {
                padding-left: 27px;
                line-height: 24px;
                display: block;
                background: url(~img/bulb.png) no-repeat left center / 15px 22px;
                font-size: 17px;
                color: #000;
            }
        }
    }
    .help_list_item {
        background-color: #fff;
        border: 1px solid #e5e5e5;
        border-bottom: 0;
        padding: 20px 15px;
        position: relative;
        color: #2e2e2e;
        line-height: 24px;
        div[class^=item_] {
            margin-bottom: 3px;
        }
        .item_time {
            color: #8a8a8a;
        }
        .item_type {
            position: absolute;
            right: 15px;
            top: 20px;
            font-size: 14px;
            color: $color;
            border: 1px solid $color;
            border-radius: 5px;
            padding: 3px 6px;
        }
    }
    .no_data {
        font-size: 20px;
        color: #dbdbdb;
        height: 86px;
        line-height: 86px;
        text-align: center;
    }
    .more {
        background-color: #fff;
        text-align: center;
        line-height: 40px;
        color: $color;
        border-top: 1px solid #e5e5e5;
        border-bottom: 1px solid #e5e5e5;
        i {
            font-size: 25px;
            margin-left: 10px;
            position: relative;
            top: 3px;
        }
    }
</style>
