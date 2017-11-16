<template>
    <div class="notice_popup center_box bf">
        <x-dialog v-model="open" hide-on-blur :dialog-style="{width: '75%', height: '75%', 'background-color': '#fff', 'border-radius': '10px'}">
            <div class="dialog">
                <div class="dialog_tit">
                    <span>认捐须知</span>
                    <x-icon @click="open = !open" type="ios-close-outline" style="fill:#676767;"></x-icon>
                </div>
                <div class="dialog_content">
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
    import { XDialog } from 'vux'
    export default {
        name: 'notice_popup',
        props: ['showDialog'],
        components: {
            XDialog
        },
        data() {
            return {
                open: this.showDialog
            }
        },
        watch: {
            showDialog(v) {
                this.open = v
            },
            open(v) {
                this.$emit('on-change', v)
            }
        }
    }
</script>
<style lang="scss" scoped>
    @import '~css/mk.scss';
    .dialog {
        padding: 20px;
        height: 100%;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        .dialog_tit {
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
        .dialog_content {
            padding: 10px 0;
            color: #6d6d6d;
            line-height: 30px;
            overflow-x: hidden;
        }
    }
</style>
