import projects from "../components/pages/projects";
import login from "../components/pages/login";
import VueRouter from 'vue-router'
import { store } from '../store/store'
import environments from "../components/pages/project/environments";
import code from "../components/pages/project/code";
import deployments from "../components/pages/project/deployments";
import backups from "../components/pages/project/backups";

export const routes = [
    { name: 'login', path: '/api-login', component: login },
    { path: '/', component: projects, meta: { auth: true } },
    { path: '/projects/:id', redirect: '/projects/:id/environments', meta: { auth: true } },
    { path: '/projects/:id/environments', component: environments, meta: { auth: true } },
    { path: '/projects/:id/code', component: code, meta: { auth: true } },
    { path: '/projects/:id/deployments', component: deployments, meta: { auth: true } },
    { path: '/projects/:id/backups', component: backups, meta: { auth: true } },

    // Redirect everything else to home
    {path: '*', redirect: '/'},
]

export const router = new VueRouter({
    routes
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.auth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!store.getters.isLoggedIn) {
            next('/api-login')
        } else {
            next() // go to wherever I'm going
        }
    } else {
        next() // does not require auth, make sure to always call next()!
    }
})
