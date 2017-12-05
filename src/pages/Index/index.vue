<template>
    <div class="index">
        <mk-banner></mk-banner>
        <div class="interval"></div>
        <mk-notice></mk-notice>
        <div class="interval"></div>
        <mk-ibox1></mk-ibox1>
        <div class="interval"></div>
        <mk-ibox2></mk-ibox2>
        <div class="interval"></div>
        <mk-ibox3></mk-ibox3>
        <div class="interval"></div>
        <mk-ibox4></mk-ibox4>
    </div>
</template>

<script>
    import mkBanner from '@/components/banner'
    import mkNotice from '@/components/notice'
    import mkIbox1 from '@/components/ibox1'
    import mkIbox2 from '@/components/ibox2'
    import mkIbox3 from '@/components/ibox3'
    import mkIbox4 from '@/components/ibox4'
    export default {
        name: 'index',
        components: {
            mkBanner,
            mkNotice,
            mkIbox1,
            mkIbox2,
            mkIbox3,
            mkIbox4
        },
        data() {
            return {
            }
        },
        methods: {
            siteInfo() {
                return new Promise((resolve, reject) => {
                    this.get(this.api + 'api/index/SiteInfo').then(res => {
                        let info = res.data.data
                        resolve(info)
                    }).catch(error => {
                        reject(error)
                    })
                })
            }
        },
        created() {
            Promise.all([this.getWxConfig(), this.siteInfo()]).then(([config, info]) => {
                this.Data({title: info.site_title})
                this.wxConfig(config)
                this.wxShare(info)
            }).catch(error => {
                console.log(error)
            })
        }
    }
</script>

<style lang="scss" scoped>
    @import '~css/mk.scss';
</style>
