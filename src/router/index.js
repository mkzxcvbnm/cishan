import Vue from 'vue'
import Router from 'vue-router'
const index = () => import('@/index/index')
const project = () => import('@/project/index')
const projectShow1 = () => import('@/project/show1')
const projectShow2 = () => import('@/project/show2')
const student = () => import('@/student/index')

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
            component: project,
            children: [
            ]
        },
        {
            path: '/projectShow1/:id',
            name: 'projectShow1',
            component: projectShow1
        },
        {
            path: '/projectShow2/:id',
            name: 'projectShow2',
            component: projectShow2
        },
        {
            path: '/student/:id',
            name: 'student',
            component: student
        }
    ]
})

export default router
