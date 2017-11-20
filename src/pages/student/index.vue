<template>
    <div class="student bf">
        <div class="interval"></div>
        <div class="content pd15" v-html="sdata.depict"></div>
    </div>
</template>

<script>
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
            id() {
                return this.$route.params.id
            }
        },
        created() {
            this.get(this.api + 'api/index/StudentShow', {id: this.id}).then(res => {
                let info = res.data.data
                this.$set(this, 'sdata', info)
            })
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
</style>
