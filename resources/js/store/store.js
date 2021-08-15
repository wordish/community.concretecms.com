import Vuex from 'vuex'
import VuexPersistence from 'vuex-persist'
import {apolloClient} from '../http/apollo'
import {router} from '../routes/routes'
import Vue from 'vue';
import config from "../config";

Vue.use(Vuex)

const vuexLocal = new VuexPersistence({
    storage: window.localStorage
})

export const store = new Vuex.Store({
    state: {
        jwt: null,
        jwtData: null,
        userData: null,
        jwtExpiry: null,
        count: 0,
        selectedProject: null,
        postLoginRedirect: null,
        eventSource: null,
    },
    getters: {
        isLoggedIn(state) {
            return !!state.jwt && !!state.jwtExpiry && new Date(state.jwtExpiry) > new Date()
        }
    },
    mutations: {
        connectToMercure(state, topics) {
            const url = config.mercureUrl + '?topic=' + topics;
            if (state.eventSource && typeof state.eventSource.close === 'function') {
                state.eventSource.close()
            }

            state.eventSource = new EventSource(url, {
                headers: {
                    Authorization: "Bearer " + config.mercureToken,
                }
            })

            return state.eventSource
        },
        async selectProject(state, project) {
            state.selectedProject = project
        },
        async login(state, jwt) {
            state.jwt = jwt

            const base64 = jwt.split('.')[1].replace(/-/g, '+').replace(/_/g, '/');
            const data = JSON.parse(decodeURIComponent(atob(base64).split('').map(function(c) {
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join('')));

            const jwtData = {};
            const userData = {};
            for (let key in data) {
                if (data.hasOwnProperty(key) && key.substr(0, 3) === 'u::') {
                    userData[key.substr(3)] = data[key]
                } else {
                    jwtData[key] = data[key]
                }
            }

            state.jwtData = jwtData;
            state.userData = userData;

            if (!state.jwtData.exp) {
                return store.commit('logout')
            }

            state.jwtExpiry = state.jwtData.exp * 1000

            await apolloClient.resetStore();

            if (state.postLoginRedirect) {
                state.postLoginRedirect = null
                await router.replace(state.postLoginRedirect)
            }
        },
        async logout(state) {
            state.jwt = null
            state.jwtData = null
            state.userData = null
            state.postLoginRedirect = router.currentRoute.fullPath

            await apolloClient.resetStore();
            await router.replace('/api-login')
        },
        updateUser(state, {id, email, username}) {
            state.userData.id = id
            state.userData.email = email
            state.userData.username = username
        }
    },
    plugins: process.env.NODE_ENV !== 'production' ?
    [
        vuexLocal.plugin,
        Vuex.createLogger(),
    ]
        :
    [
        vuexLocal.plugin,
    ],
})
