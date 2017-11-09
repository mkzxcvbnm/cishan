import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const state = {
    data: {
        title: '丹阳市九爱心社区',
        sticky: false,
        showBack: true,
        banner: [{
            img: require('img/banner.jpg')
        }],
        scroll: window,
        scrollTop: 0,
        notice: [], // 通知
        beneficiary: [] // 受益人数
    }
}

const getters = {
}

const mutations = {
    DATA (state, data) {
        for (let v in data) {
            state.data[v] = data[v];
            if (v === 'title') document.title = data[v]
        }
    },
    PUSHDATA (state, data) {
        const array = require('lodash/array')
        const _ = require('lodash/core')
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
    }
}

export default new Vuex.Store({
    state,
    getters,
    mutations,
    actions
})
