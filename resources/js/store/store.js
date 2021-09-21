import Vuex from 'vuex'
import VuexPersistence from 'vuex-persist'
import Vue from 'vue';
import {inDev, io} from "../helpers";
import Token from "../auth/Token";

Vue.use(Vuex)

const plugins = [
    (new VuexPersistence({
        key: "hosting.state",
        storage: window.localStorage
    })).plugin
];

// Add dev plugins
inDev(() => {
    plugins.push(Vuex.createLogger())
})


let lastEventId = null
let toastId = (new Date()).getTime();
const store = new Vuex.Store({
    state: {
        postLoginRedirect: null,
        eventSource: null,
        eventSourceListeners: {},
        toasts: {},
        roles: [],
        token: null,
        authRequest: null
    },
    getters: {
        isAdmin(state) {
            return state.roles.indexOf('ROLE_ADMIN') !== -1
        },
        token(state) {
            return state.token ? Token.inflate(state.token) : null
        }
    },
    mutations: {
        updateToken(state, token) {
            state.token = token ? token.deflate() : null
        },
        updateAuthRequest(state, request) {
            state.authRequest = request
        },
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
            state.eventSourceListeners[key] = listener
        },

        connectToMercure(state, backoff) {
            return state.eventSource
        },
        setPostLoginRedirect(state, redirect) {
            state.postLoginRedirect = redirect
        },
        setRoles(state, roles) {
            state.roles = [...roles];
        }
    },
    plugins
})

// Prune any toasts
store.commit('pruneToasts')

export default store