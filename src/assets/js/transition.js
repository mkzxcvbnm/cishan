import Vue from 'vue'
import { mapState, mapActions } from 'vuex'
// 可复用过渡组件(通用)
Vue.component('mktransition', {
    template: `<transition-group
        name="fade"
        mode="out-in"
        appear
        @before-leave="beforeLeave"
        @after-enter="afterEnter"
    >
        <slot></slot>
    </transition-group>`,
    data() {
        return {
            back: false
        }
    },
    computed: {
        ...mapState([
        ]),
        transitionName (state) {
        }
    },
    methods: {
        ...mapActions([
        ]),
        beforeLeave: function () {
        },
        afterEnter: function () {
        }
    }
})
