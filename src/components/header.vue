<template>
    <div class="header">
        <x-header
        :left-options="{backText: '', showBack: data.showBack}"
        :transition="'header-fade-in-' + (isback ? 'left' : 'right')"
        :title="data.title">
        </x-header>
    </div>
</template>
<script>
    import { mapState } from 'vuex'
    import { XHeader } from 'vux'
    export default {
        name: 'header',
        components: {
            XHeader
        },
        data() {
            return {
            }
        },
        computed: {
            ...mapState([
                'isback',
                'data'
            ])
        }
    }
</script>
<style lang="scss">
    .header {
        width: 100%;
        position: absolute;
        left: 0px;
        top: 0px;
        z-index: 100;
        .vux-header {
            background-color: #fff;
            .vux-header-title {
                color: #000;
                font-size: 17px;
                font-weight: bold;
            }
            .vux-header-left .left-arrow {
                &:before {
                    border-color: #000;
                    border-width: 3px 0 0 3px;
                }
            }
        }
    }
</style>
