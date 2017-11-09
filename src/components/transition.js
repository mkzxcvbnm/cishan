//可复用过渡组件(通用)
Vue.component('mktransition', {
    template: `<transition
        :name="transitionName"
        @before-leave="beforeLeave"
        @after-enter="afterEnter"
    >
        <slot></slot>
    </transition>`,
    data(){
        return {
            back: false
        }
    },
    computed: Vuex.mapState({
        ...Vuex.mapState([
            'isback',
            'scroll',
        ]),
        transitionName (state) {
            return state.isback?state.transitionName+'-back':state.transitionName
        },
    }),
    methods: {
        ...Vuex.mapMutations([
            'ISBACK',
            'SETTRANSITIONNAME'
        ]),
        beforeLeave: function () {
            this.isback?this.back=true:this.back=false;
            this.ISBACK(false);
            this.SETTRANSITIONNAME('translateX');
        },
        afterEnter: function () {
            // if (document.body.scrollTop == 0) {
            //     //解决上一页面transition未完成 进入下一页面出现遮盖物的BUG
            //     scroll(0,1)
            //     scroll(0,0)
            // }
            // if (document.body.scrollTop == this.scroll) {
            //     //解决ios scrollTop有值但页面并没有滚动到位的BUG
            //     scroll(0, this.scroll)
            // }
            // if (this.back) {
            //     //滚动到上一个页面的位置
            //     scroll(0, this.scroll)
            // }

            if (this.back && document.body.scrollTop == 0) {
                //滚动到上一个页面的位置
                Velocity(document.body, 'scroll', {
                    offset: this.scroll,
                    duration: "fast",
                    easing: "easeOutExpo",
                    mobileHA: false
                });
            }
        },
    }
})
//可复用过渡组件(左右)
Vue.component('translateX', {
    template: `<transition
        :name="transitionName"
        @before-leave="beforeLeave"
    >
        <slot></slot>
    </transition>`,
    computed: Vuex.mapState({
        transitionName (state) {
            return state.isback?'translateX-back':'translateX'
        },
    }),
    methods: {
        ...Vuex.mapMutations([
            'ISBACK'
        ]),
        beforeLeave: function () {
            this.ISBACK(false)
        },
    }
})
//可复用过渡组件(上下)
Vue.component('translateY', {
    template: `<transition
        :name="transitionName"
        @before-leave="beforeLeave"
    >
        <slot></slot>
    </transition>`,
    computed: Vuex.mapState({
        transitionName (state) {
            return state.isback?'translateY-back':'translateY'
        },
    }),
    methods: {
        ...Vuex.mapMutations([
            'ISBACK'
        ]),
        beforeLeave: function () {
            this.ISBACK(false)
        },
    }
})
//可复用过渡组件(列表)
Vue.component('list-slide', {
    template: `<transition-group
        name="staggered-fade"
        :tag="default_tag||'ul'"
        :css="false"
        @before-enter="beforeEnter"
        @enter="enter"
        @leave="leave"
    >
        <slot></slot>
    </transition-group>`,
    props: ['tag'],
    data(){
        return {
            show: true,
            fadeInDuration: 1000,
            fadeOutDuration: 1000,
            maxFadeDuration: 1500,
            stop: false,
            default_tag: this.tag,
        }
    },
    methods: {
        beforeEnter: function (el) {
            Velocity.hook(el, "translateY", "100%");
            Velocity.hook(el, "opacity", 0);
        },
        enter: function (el, done) {
            setTimeout(function () {
                Velocity(el, {
                    translateY: 0,
                    opacity: 1
                },{
                    duration: "slow",
                    easing: "easeOutExpo",
                }, { display: "block" })
            }, el.dataset.index * 150)
        },
        leave: function (el, done) {
            el.style.display = 'none';
            // setTimeout(function () {
            //     Velocity(el, "slideUp", { display: "none" })
            // }, el.dataset.index * 200)
        }
    }
})