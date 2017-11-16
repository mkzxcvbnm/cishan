import Vue from 'vue'
import Router from 'vue-router'
const index = () => import('@/pages/index/index')
const project = () => import('@/pages/project/index')
const ydy = () => import('@/pages/ydy/index')
const zc = () => import('@/pages/zc/index')
const zcPay = () => import('@/pages/zc/zcPay')
const student = () => import('@/pages/student/index')
const love = () => import('@/pages/love/index')
const donation = () => import('@/pages/love/donation')
const apply = () => import('@/pages/love/apply')
const rent = () => import('@/pages/love/rent')
const free = () => import('@/pages/love/free')
const lovelist = () => import('@/pages/lovelist/index')
const news = () => import('@/pages/news/index')
const show = () => import('@/pages/news/show')
const help = () => import('@/pages/help/index')
const user = () => import('@/pages/user/index')
const myYdy = () => import('@/pages/user/myYdy')
const myZc = () => import('@/pages/user/myZc')
const myHelp = () => import('@/pages/user/myHelp')
const myDonation = () => import('@/pages/user/myDonation')
const setting = () => import('@/pages/user/setting')
const verifyPhone = () => import('@/pages/user/verifyPhone')
const myApply = () => import('@/pages/user/myApply')

Vue.use(Router)

const router = new Router({
    routes: [
        {
            path: '/',
            name: 'index',
            component: index,
            children: [
            ]
        },
        {
            path: '/project/:type',
            name: 'project',
            component: project
        },
        {
            path: '/ydy/:id',
            name: 'ydy',
            component: ydy
        },
        {
            path: '/student/:id',
            name: 'student',
            component: student
        },
        {
            path: '/zc/:id',
            name: 'zc',
            component: zc
        },
        {
            path: '/zcPay/:id/:quick_payment',
            name: 'zcPay',
            component: zcPay
        },
        {
            path: '/love',
            name: 'love',
            component: love
        },
        {
            path: '/donation',
            name: 'donation',
            component: donation
        },
        {
            path: '/apply/:type',
            name: 'apply',
            component: apply
        },
        {
            path: '/rent/:type',
            name: 'rent',
            component: rent
        },
        {
            path: '/free/:type',
            name: 'free',
            component: free
        },
        {
            path: '/news/:cid',
            name: 'news',
            component: news
        },
        {
            path: '/show/:id',
            name: 'show',
            component: show
        },
        {
            path: '/lovelist',
            name: 'lovelist',
            component: lovelist
        },
        {
            path: '/help',
            name: 'help',
            component: help
        },
        {
            path: '/user',
            name: 'user',
            component: user
        },
        {
            path: '/myYdy',
            name: 'myYdy',
            component: myYdy
        },
        {
            path: '/myZc',
            name: 'myZc',
            component: myZc
        },
        {
            path: '/myHelp',
            name: 'myHelp',
            component: myHelp
        },
        {
            path: '/myDonation',
            name: 'myDonation',
            component: myDonation
        },
        {
            path: '/setting',
            name: 'setting',
            component: setting
        },
        {
            path: '/verifyPhone',
            name: 'verifyPhone',
            component: verifyPhone
        },
        {
            path: '/myApply/:type',
            name: 'myApply',
            component: myApply
        }
    ]
})

export default router
