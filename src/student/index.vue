<template>
    <div class="student bf">
        <div class="interval"></div>
        <div class="content pd15" v-html="sdata.content"></div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    export default {
        name: 'student',
        components: {
        },
        data() {
            return {
                sdata: {}
            }
        },
        computed: {
            ...mapState([
                'data'
            ]),
            id() {
                return this.$route.params.id
            }
        },
        methods: {
            ...mapActions([
                'Data',
                'PushData'
            ])
        },
        created() {
            this.post('http://www.yunucms.cn/index.php/api/content', {id: this.id}).then(res => {
                let info = res.data.data
                this.Data({['student_' + this.id]: info, title: info.title})
                this.$set(this, 'sdata', info)
            })
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
</style>
