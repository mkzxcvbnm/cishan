<template>
    <div class="init">
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    export default {
        name: 'init',
        components: {
        },
        data() {
            return {
            }
        },
        computed: {
            ...mapState([
                'data'
            ])
        },
        methods: {
            ...mapActions([
                'Data'
            ])
        },
        created() {
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
</style>
