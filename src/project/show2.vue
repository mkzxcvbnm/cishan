<template>
    <div class="projectShow">
        <div class="projectShow_top">
            <span>预告中</span>
            <span>10月27日  10：00  开始</span>
            {{sdata.title}}
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import { dateFormat } from 'vux'
    export default {
        name: 'project',
        components: {
        },
        data() {
            return {
            }
        },
        computed: {
            ...mapState([
                'data'
            ]),
            id() {
                return this.$route.params.id
            },
            sdata() {
                return this.data['project_show' + this.id]
            }
        },
        methods: {
            ...mapActions([
                'Data',
                'PushData'
            ]),
            dateFormat(time, format) {
                return dateFormat(time, format)
            }
        },
        created() {
            this.post('http://www.yunucms.cn/index.php/api/content', {id: this.id}).then(res => {
                let info = res.data.data
                this.Data({['project_show' + this.id]: info, title: info.title})
            })
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
    .projectShow_top {

    }
</style>
