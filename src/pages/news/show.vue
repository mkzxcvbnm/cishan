<template>
    <div class="show bf">
        <div class="interval"></div>
        <div class="show_tit center_box pt10 pb10 border_b" v-if="id > 0">
            <h1>{{sdata.title}}</h1>
            <div class="between">
                <span>{{dateFormat(sdata.create_time * 1000, 'YYYY-MM-DD')}}</span>
                <span class="read">阅读量&nbsp;&nbsp;{{sdata.click}}&nbsp;&nbsp;</span>
            </div>
        </div>
        <div class="content center_box pt10 pb10" v-html="sdata.content"></div>
    </div>
</template>

<script>
    import { dateFormat } from 'vux'
    export default {
        name: 'show',
        components: {
        },
        data() {
            return {
                id: this.$route.params.id,
                sdata: {}
            }
        },
        watch: {
            '$route' (to, from) {
                this.setSdata()
            }
        },
        methods: {
            dateFormat(time, format) {
                return dateFormat(time, format)
            },
            setSdata() {
                console.log(this.id > 0)
                if (this.id > 0) {
                    this.get(this.api + 'api/index/ArticleShow', {id: this.id}).then(res => {
                        this.$set(this, 'sdata', res.data.data)
                        this.Data({title: res.data.data.title})
                    })
                } else {
                    this.get(this.api + 'api/index/About').then(res => {
                        this.$set(this.sdata, 'content', res.data.data)
                        this.Data({title: '关于我们'})
                    })
                }
            }
        },
        created() {
            this.setSdata()
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
    .content {
        min-height: 420px;
    }
    .show_tit {
        h1 {
            font-size: 16px;
            font-weight: normal;
        }
        .between {
            color: #a3a3a3;
            font-size: 14px;
        }
        .read {
            padding-left: 25px;
            background: url(~img/read.png) no-repeat left center / 15px 15px;
            font-size: 13px;
        }
    }
</style>
