<template>
    <div class="student bf">
        <div class="interval"></div>
        <div class="content pd15" v-html="sdata.depict"></div>
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
                'Data'
            ])
        },
        created() {
            this.get('http://jiujiu99.yuanhang.org/api/index/StudentShow', {id: this.id}).then(res => {
                let info = res.data.data
                this.$set(this, 'sdata', info)
            })
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
</style>
