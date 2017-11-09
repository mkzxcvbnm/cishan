<template>
    <div class="projectShow1 bf" ref="projectShow1">
        <div class="page_top" :class="[stateClass]">
            <span class="page_top_l">预告中</span>
            <span class="page_top_r">{{dateFormat(sdata.update_time, 'MM月DD日 HH:mm')}} 开始</span>
        </div>
        <div class="interval"></div>
        <div class="pbox1">{{sdata.title}}</div>
        <div class="pbox2">
            <div class="pbox2_top">
                <span>认捐学生列表</span>
                <button @click="showDialog = true">认捐须知</button>
            </div>
            <ul class="pbox2_list">
                <li :class="[studentState(item)]" v-for="(item, index) in data['projectShow1_list' + id]" :key="index" @click="scrollToStudent(item.id)">{{index}}.{{item.title}}</li>
            </ul>
        </div>
        <div class="interval"></div>
        <div class="pbox3">
            <div class="pbox3_top">
                <span>学生资料列表</span>
            </div>
            <div class="pbox3_list">
                <div class="vux-form-preview weui-form-preview pbox3_list_item" :ref="'student_' + item.id" :class="[studentState(item)]" v-for="(item, index) in data['projectShow1_list' + id]" :key="index">
                    <div class="weui-form-preview__hd">
                        <label class="weui-form-preview__label" >{{(Array(2).join(0) + (index+1)).slice(-2)}}.{{item.title}}  </label>
                        <em class="weui-form-preview__value">{{item.title}}</em>
                    </div>
                    <div class="weui-form-preview__bd">
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">地区：</label>
                            <span class="weui-form-preview__value">{{item.id}}</span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">性别：</label>
                            <span class="weui-form-preview__value">{{item.id}}</span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">年级：</label>
                            <span class="weui-form-preview__value">{{item.id}}</span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">助学金：</label>
                            <span class="weui-form-preview__value">{{item.id}}元/学期</span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">汇款金额：</label>
                            <span class="weui-form-preview__value">{{item.id}}元/学期</span>
                        </div>
                        <div class="weui-form-preview__item">
                            <label class="weui-form-preview__label">贫困原因：</label>
                            <span class="weui-form-preview__value">{{item.id}}</span>
                        </div>
                    </div>
                    <div class="weui-form-preview__ft">
                        <router-link class="weui-form-preview__btn weui-form-preview__btn_default" :to="{ name: 'student', params: { id: item.id } }"><span>学生详情</span></a></router-link>
                        <a class="weui-form-preview__btn weui-form-preview__btn_primary" href="javascript:" @click="pay"><span>我要认捐</span></a>
                    </div>
                </div>
                <!-- <form-preview class="pbox3_list_item" :class="[studentState(item)]" v-for="(item, index) in data['projectShow1_list' + id]" :key="index" :header-label="item.title" :header-value="item.id" :body-items="preview_list(item)" :footer-buttons="preview_btn(item)" name="demo"></form-preview> -->
            </div>
        </div>
        <x-dialog v-model="showDialog" hide-on-blur :dialog-style="{width: '75%', height: '75%', 'background-color': '#fff', 'border-radius': '10px'}">
            <div class="dialog1">
                <div class="dialog1_tit">
                    <span>认捐须知</span>
                    <x-icon @click="showDialog = false" type="ios-close-outline" style="fill:#676767;"></x-icon>
                </div>
                <div class="dialog1_content">
                    在永嘉贫困山区有这么一群渴望知识、热爱学习的孩子，他们贫困，但他们坚守着自己的愿望。这群孩子随时就有可能因生活的压力而辍学，但他们渴望通过书籍掌握科学技术以改变现今窘迫的生活状态，渴望通过与书本知识的对话丰富自身的精神世界。<br>
                    公司秉持着助学帮困奉献爱心的传统美德在此向大家发出倡议，伸出你的双手，奉献一片爱心，也许你的点滴付出汇集于爱心之流中却能改变一群孩子的命运，让可能就此暗淡的生活重新迸发出灿烂的光辉，让希望的光芒映在他们稚嫩的脸上，照亮他们的前程。<br>
                    爱心需要传承，<br>
                    爱心需要接力，爱心需要奉献。“施”比“受”更容易得到快乐，洒下的是点滴温情，成就的却是高尚人生。困难的家庭需要您的爱心，和谐的社会需要您的支持。请伸出您的友爱之手，献出您的关爱之情吧！
                </div>
            </div>
        </x-dialog>
    </div>
</template>

<script>
    import velocity from 'velocity-animate'
    import { mapState, mapActions } from 'vuex'
    import { dateFormat, XDialog, FormPreview } from 'vux'
    export default {
        name: 'projectShow1',
        components: {
            XDialog,
            FormPreview
        },
        data() {
            return {
                sdata: {},
                showDialog: false
            }
        },
        computed: {
            ...mapState([
                'data'
            ]),
            id() {
                return this.$route.params.id
            },
            stateClass() {
                return 'state_' + this.sdata.id % 2
            }
        },
        methods: {
            ...mapActions([
                'Data',
                'PushData'
            ]),
            dateFormat(time, format) {
                return dateFormat(time, format)
            },
            studentState(item) {
                let classname = 'state_'
                switch (item.id % 4) {
                case 1:
                    classname += '1'
                    break
                case 2:
                    classname += '2'
                    break
                case 3:
                    classname += '3'
                    break
                default:
                    classname += '4'
                }
                return classname
            },
            scrollToStudent(id) {
                let container = this.data.scroll.getScrollBody()
                let box = this.$refs.projectShow1
                console.log(this.$refs['student_' + id][0].offsetTop)
                velocity(box, 'scroll', {
                    container: container,
                    offset: this.$refs['student_' + id][0].offsetTop - 100
                })
            },
            alert() {
                this.$vux.alert.show({
                    title: '通知',
                    content: '活动暂未开始，请开始后再来',
                    buttonText: '我知道了'
                })
            },
            pay() {

            }
        },
        created() {
            this.post('http://www.yunucms.cn/index.php/api/content', {id: this.id}).then(res => {
                let info = res.data.data
                this.Data({['projectShow1_' + this.id]: info, title: info.title})
                this.$set(this, 'sdata', info)
            })
            this.post('http://www.yunucms.cn/index.php/api/list', {limit: 8}).then(res => {
                let info = res.data.data
                this.Data({['projectShow1_list' + this.id]: info})
                this.$set(this.data, 're', true)
            })
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
    .projectShow1 {
        font-size: 14px;
    }
    .page_top {
        height: 54px;
        line-height: 54px;
        color: #2e2e2e;
        background-color: #f2f2f2;
        display: flex;
        text-align: center;
        .page_top_l {
            color: #fff;
            width: 25%;
        }
        .page_top_r {
            flex: 1;
        }
        &.state_0 {
            .page_top_l {
                background-color: #ff5a00;
            }
        }
        &.state_1 {
            .page_top_l {
                background-color: $color;
            }
        }
        &.state_2 {
            background: #afafaf;
        }
    }
    .pbox_tit {
        padding: 8px 4%;
        line-height: 34px;
        span {
            color: #000;
            font-size: 16px;
        }
    }
    .pbox1 {
        color: #6d6d6d;
        padding: 12px 4%;
    }
    .pbox2 {
        .pbox2_top {
            @extend .pbox_tit;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e5e5e5;
            border-bottom: 1px solid #e5e5e5;
            button {
                width: 94px;
                line-height: 32px;
                color: #2e2e2e;
                font-size: 14px;
                background-color: #f1f1f1;
                border-radius: 6px;
            }
        }
        .pbox2_list {
            padding: 10px 4%;
            display: flex;
            flex-flow: row wrap;
            justify-content: space-around;
            height: 100%;
            li {
                width: 161px;
                height: 31.5px;
                line-height: 31.5px;
                text-align: center;
                color: #f3f3f3;
                background-color: #bdbdbd;
                border-radius: 6px;
                margin-top: 10px;
                margin-bottom: 10px;
                border: 1px solid #bdbdbd;
                &.state_1 {
                    color: $color;
                    border-color: $color;
                    background-color: transparent;
                }
                &.state_2 {
                }
                &.state_3 {
                }
                &.state_4 {
                    color: #c9c9c9;
                    border-color: #c9c9c9;
                    background-color: transparent;
                }
            }
            &:after {
                content: '';
                width: 161px;
            }
        }
    }
    .pbox3 {
        .pbox3_top {
            @extend .pbox_tit;
        }
        .pbox3_list {
            padding: 10px 4%;
            .pbox3_list_item {
                background-color: #f1f1f1;
                border: 1px solid #dfdfdf;
                margin-bottom: 17px;
                &.state_1 {
                }
                &.state_2 {
                }
                &.state_3 {
                }
                &.state_4 {
                }
            }
        }
    }
    .dialog1 {
        padding: 20px;
        height: 100%;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        .dialog1_tit {
            position: relative;
            .vux-x-icon {
                position: absolute;
                right: 0;
                top: 0;
            }
            span {
                font-size: 17px;
                color: #000;
                font-weight: bold;
            }
        }
        .dialog1_content {
            padding: 10px 0;
            color: #6d6d6d;
            line-height: 30px;
            overflow-x: hidden;
        }
    }
</style>
<style lang="scss">
    @import '~css/mk.scss';
    .pbox3_list {
        .weui-form-preview:before,.weui-form-preview:after,.weui-form-preview__btn:after {
            display: none;
        }
        .weui-form-preview__hd:after {
            left: 0;
        }
        .weui-form-preview__hd:after,.weui-form-preview__ft:after {
            border-color: #e5e5e5;
        }
        .weui-form-preview__hd {
            padding: 5px 15px;
            .weui-form-preview__label,.weui-form-preview__value {
                color: $color;
                font-size: 14px;
            }
        }
        .weui-form-preview__bd {
            text-align: left;
            font-size: 14px;
            &,.weui-form-preview__label {
                min-width: 5em;
                color: #6d6d6d;
                margin-right: 2px;
            }
        }
        .weui-form-preview__ft {
            span {
                width: 93.5px;
                height: 31.5px;
                display: inline-block;
                text-align: center;
                line-height: 31.5px;
                color: #2e2e2e;
                border: 1px solid #e5e5e5;
                border-radius: 5px;
            }
            .weui-form-preview__btn_primary {
                span {
                    width: 115px;
                    border-color: $color;
                    color: #fff;
                    background-color: $color;
                }
            }
        }
    }
</style>
