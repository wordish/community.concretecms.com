import Vuex from 'vuex'
import VuexPersistence from 'vuex-persist'
import {apolloClient} from '../http/apollo'
import {router} from '../routes/routes'
import Vue from 'vue';
import config from "../config";
import gql from "graphql-tag";
import {addDevToast, addToast, inDev, io} from "../helpers";
import sessionTracker from "../auth/sessiontracker";

Vue.use(Vuex)

const vuexLocal = new VuexPersistence({
    storage: window.localStorage
})

let lastEventId = null
let toastId = (new Date()).getTime();
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
        eventSourceListeners: {},
        toasts: {},
        roles: [],
    },
    getters: {
        isLoggedIn(state) {
            return !!state.jwt && !!state.jwtExpiry && new Date(state.jwtExpiry) > new Date()
        },
        isAdmin(state) {
            return state.roles.indexOf('ROLE_ADMIN') !== -1
        },
    },
    mutations: {
        addToast(state, {title, message, canDismiss, timeout, type, id}) {
            const expires = timeout ? (new Date).getTime() + (timeout * 1000) : 0
            id = id ? id : (new Date).getTime()
            const currentToast = id

            Vue.set(state.toasts, 'toast' + currentToast, {
                title,
                message,
                canDismiss,
                expires: expires,
                id: currentToast,
                type
            })

            if (timeout) {
                io.log('Setting toast timeout: ' + timeout)
                setTimeout(() => store.commit('hideToast', currentToast), timeout * 1000)
            }
        },
        hideToast(state, toastId) {
            io.log('Hiding Toast ' + toastId)
            if (typeof state.toasts['toast' + toastId] !== "undefined") {
                io.log('Found it')
                Vue.delete(state.toasts, 'toast' + toastId)
            }
        },
        pruneToasts(state) {
            const now = (new Date()).getTime()
            for (const toast in state.toasts) {
                if (!state.toasts.hasOwnProperty(toast) || state.toasts[toast].expires === 0) {
                    continue
                }

                if (state.toasts[toast].expires <= now) {
                    Vue.delete(state.toasts, toast)
                }
            }
        },
        setEventSourceListener(state, {key, listener}) {
            store.commit('connectToMercure');
            state.eventSourceListeners[key] = listener
        },

        connectToMercure(state, backoff) {
            if (state.eventSource && state.eventSource.readyState === 1) {
                return
            }

            const lastEventString = lastEventId ? '&Last-Event-ID=' + lastEventId : ''
            const url = config.mercureUrl + '?topic=deploy&topic=task' + lastEventString

            backoff = Math.max(0, parseInt(backoff))
            if (state.eventSource && typeof state.eventSource.close === 'function') {
                state.eventSource.close()
            }

            state.eventSource = new EventSource(url, {
                lastEventIdQueryParameterName: 'Last-Event-ID',
                headers: {
                    Authorization: "Bearer " + config.mercureToken,
                }
            })

            state.eventSource.addEventListener('message', e => {
                backoff = 0
                lastEventId = e.lastEventId

                const data = JSON.parse(e.data)
                io.groupCollapsed('[' + data.group + '] EventSourceMessage ' + e.lastEventId, function() {
                    io.group('Event', () => {
                        io.log(e)
                    })
                    io.group('Data', () => {
                        io.log(data)
                    })
                })

                for (let i in store.state.eventSourceListeners) {
                    if (!store.state.eventSourceListeners.hasOwnProperty(i) || !store.state.eventSourceListeners[i]) {
                        continue;
                    }

                    io.log('Sending to listener ' + i)
                    store.state.eventSourceListeners[i](e)
                }
            })

            state.eventSource.addEventListener('error', e => {
                addToast('Connecting...', 0, 'warning', false, 'connecting')
                io.log('MERCURE ERROR:', e)

                addDevToast('Mercure Error', 8, 'info')
                setTimeout(function () {
                    store.commit('connectToMercure', backoff ? backoff * 2 : 2)
                }, backoff)
            })

            state.eventSource.onopen = function(e) {
                setTimeout(() => store.commit('hideToast', 'connecting'))
            }

            return state.eventSource
        },
        async selectProject(state, project) {
            state.selectedProject = project
        },
        async login(state, jwt) {
            io.log('SHOULD REDIRECT:' + state.postLoginRedirect)
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
                await router.replace(state.postLoginRedirect)
                state.postLoginRedirect = null
            }
        },
        async logout(state) {
            state.jwt = null
            state.jwtData = null
            state.userData = null

            await apolloClient.resetStore();
        },
        setPostLoginRedirect(state, redirect) {
            state.postLoginRedirect = redirect
        },
        updateUser(state, {id, email, username}) {
            state.userData.id = id
            state.userData.email = email
            state.userData.username = username
        },
        setRoles(state, roles) {
            state.roles = [...roles];
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

// Prune any toasts
store.commit('pruneToasts')