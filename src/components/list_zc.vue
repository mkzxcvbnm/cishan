<template>
    <div class="zz_list">
        <div class="zz_list_item" v-for="(item, index) in list" :key="index" @click="link(item)">
            <div class="zz_list_l">
                <div class="zz_list_img cover" :style="{backgroundImage: 'url('+item.cover+')'}"></div>
            </div>
            <div class="zz_list_r">
                <div class="zz_list_text">
                    <div class="zz_list_tit"><h2>{{item.title}}</h2><span v-if="item.stick == 1">置顶</span></div>
                    <div class="zz_list_con">
                        <div class="zz_list_type">
                            <span v-if="item.status == 1" class="type_conduct">进行中</span>
                            <span v-else-if="item.status == 2" class="type_end">已结束</span>
                            <span v-else>预告中</span>
                        </div>
                        <div>进度：{{percent(item).toFixed(2)}}%</div>
                    </div>
                </div>
                <x-progress class="zz_list_progress" :percent="percent(item)" :show-cancel="false"></x-progress>
                <div class="zz_list_info">
                    <div v-if="item.status == 1">
                        <span>剩余时间：</span>
                        <clocker :time="dateFormat(item.end_time * 1000, 'YYYY-MM-DD HH:mm:ss')" @on-finish="item.status = 2">
                            <span>%H小时</span><span>%M分钟</span><span>%S秒</span>
                        </clocker>
                    </div>
                    <div v-else-if="item.status == 2">{{dateFormat(item.end_time * 1000, 'MM月DD日 HH:mm')}} 结束</div>
                    <div v-else>{{dateFormat(item.begin_time * 1000, 'MM月DD日 HH:mm')}} 开始</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { XProgress, Clocker, dateFormat } from 'vux'
    export default {
        name: 'list_zc',
        components: {
            XProgress,
            Clocker
        },
        props: ['list'],
        methods: {
            dateFormat(time, format) {
                return dateFormat(time, format)
            },
            percent(item) {
                return item.now_money / item.need_money * 100
            },
            link(item) {
                this.$router.push({ name: 'zc', params: { id: item.id, tid: item.tid } })
            }
        }
    }
</script>

<style lang="scss" scoped>
</style>
