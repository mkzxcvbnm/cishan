<template>
    <div class="case">
    </div>
</template>

<script>
    import { mapState, mapGetters, mapMutations, mapActions } from 'vuex'
    export default {
        name: 'case',
        props: [],
        components: {
        },
        data() {
            return {
            }
        },
        computed: {
            ...mapState([
            ]),
            ...mapGetters([
            ])
        },
        watch: {
            '$route' (to, from) {
                const toDepth = to.path.split('/').length
                const fromDepth = from.path.split('/').length
                this.transitionName = toDepth < fromDepth ? 'slide-right' : 'slide-left'
            }
        },
        methods: {
            ...mapMutations([
            ]),
            ...mapActions([
            ])
        },
        beforeCreate() {
        },
        created() {
            this.$http({
                method: 'POST',
                url: 'http://www.yunucms.cn/index.php/api/config',
                data: {
                    name: 'site_logo'
                }
            })
            .then(function (res) {
            })
            .catch(function (error) {
            })
        },
        beforeMount() {
        },
        mounted() {
            this.$nextTick(() => {
            });
        },
        beforeUpdate() {
        },
        updated() {
        },
        beforeDestroy() {
        },
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
</style>
