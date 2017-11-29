import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const array = require('lodash/array')
const _ = require('lodash/core')

const state = {
    isback: false,
    isloading: true,
    backurl: false,
    love_type: ['', '拐杖', '轮椅'],
    news_type: ['信息', '公告', '动态', '财务公开'],
    data: {
        title: '丹阳市九爱心社区',
        // sticky: false,
        showBack: true,
        showFoot: true,
        scrollBox: window,
        scrollTop: 0
    },
    user: {}
}

const getters = {
}

const mutations = {
    ISBACK (state, bool) {
        state.isback = bool
    },
    ISLOADING (state, bool) {
        state.isloading = bool
    },
    BACKURL (state, data) {
        state.backurl = data
    },
    DATA (state, data) {
        for (let v in data) {
            state.data[v] = data[v];
            if (v === 'title') document.title = data[v]
        }
    },
    PUSHDATA (state, data) {
        for (let v in data) {
            // for (let item of data[v]) {
            //     state.data[v].push(item);
            // }
            state.data[v] = array.unionWith(state.data[v], data[v], _.isEqual)
        }
    },
    UNIQDATA (state, data) {
        // const array = require('lodash/array')
        // const _ = require('lodash/core')
        // let uniqdata = state.data[data]
        // uniqdata = array.uniqWith(uniqdata, _.isEqual)
        // array.reverse(uniqdata)
        // uniqdata = array.uniqBy(uniqdata, 'id')
        // array.reverse(uniqdata)
        // state.data[data] = uniqdata
    },
    USER (state, data) {
        state.user = data
    }
}

const actions = {
    Data ({ commit }, data) {
        commit('DATA', data)
    },
    PushData ({ commit }, data) {
        commit('PUSHDATA', data)
    },
    UniqData ({ commit }, data) {
        commit('UNIQDATA', data)
    },
    User ({ commit }, data) {
        commit('USER', data)
    }
}

export default new Vuex.Store({
    state,
    getters,
    mutations,
    actions
})
