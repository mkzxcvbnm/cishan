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
        watch: {
            '$route' (to, from) {
                this.setSdata()
            }
        },
        methods: {
            setSdata() {
                this.get(this.api + 'api/index/StudentShow', {id: this.id}).then(res => {
                    let info = res.data.data
                    if (this.user.uid !== info.uid) {
                        this.$vux.alert.show({
                            title: '通知',
                            content: '认捐人才可以查看',
                            buttonText: '我知道了',
                            onHide: function() {
                                this.$router.go(-1)
                            }.bind(this)
                        })
                    } else {
                        this.$set(this, 'sdata', info)
                    }
                })
            }
        },
        created() {
            this.setSdata()
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
</style>
