<template>
    <div v-show="data.showFoot === false" class="rbtn">
        <router-link :to="{ path: '/'}" class="rbtn1"><img src="~img/to_top.png" width="53" height="53"></router-link>
    </div>
</template>

<script>
    import { mapState } from 'vuex'
    export default {
        name: 'rbtn',
        data() {
            return {
            }
        },
        computed: {
            ...mapState([
                'data'
            ])
        }
    }
</script>

<style lang="scss" scoped>
    .rbtn {
        position: fixed;
        right: 0;
        bottom: 100px;
        z-index: 1001;
    }
</style>
