import projects from "../components/pages/projects";
import login from "../components/pages/login";
import VueRouter from 'vue-router'
import store from '../store/store'
import environments from "../components/pages/project/environments";
import code from "../components/pages/project/code";
import deploys from "../components/pages/project/environments/deploys";
import backups from "../components/pages/project/environments/backups";
import installs from "../components/pages/project/environments/installs";
import permissions from "../components/pages/project/permissions";
import {auth} from "../auth/Authentication";
import {inDev} from "../helpers";
import dev from "../components/pages/dev";

export const routes = [
    { name: 'login', path: '/api-login', component: login },
    { name: 'projects', path: '/', component: projects, meta: { auth: true } },
    { name: 'project', path: '/:id', redirect: '/:id/environments', meta: { auth: true } },
    { path: '/:id/environments', component: environments, meta: { auth: true } },
    { path: '/:id/code', component: code, meta: { auth: true } },
    { path: '/:id/permissions', component: permissions, meta: { auth: true } },
    { path: '/:id/env/:environment+/deploys',  component: deploys, meta: { auth: true } },
    { path: '/:id/env/:environment+/backups', component: backups, meta: { auth: true } },
    { path: '/:id/env/:environment+/installs', component: installs, meta: { auth: true } },

    // Redirect everything else to home
    {path: '*', redirect: '/'},
]

inDev(() => routes.unshift({
    name: "dev", path: "/dev", component: dev
}))

export const router = new VueRouter({
    mode: 'history',
    base: '/account/projects',
    routes
})

router.beforeEach(async (to, from, next) => {
    if (to.matched.some(record => record.meta.auth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!auth.isLoggedIn()) {
            const foo = auth
            const foostore = store
            debugger
            store.commit('setPostLoginRedirect', to.fullPath)
            await auth.logout()
            next('/api-login')
        } else {
            next() // go to wherever I'm going
        }
    } else {
        next() // does not require auth, make sure to always call next()!
    }
})
