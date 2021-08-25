import Vuex from 'vuex'
import VuexPersistence from 'vuex-persist'
import {apolloClient} from '../http/apollo'
import {router} from '../routes/routes'
import Vue from 'vue';
import config from "../config";
import gql from "graphql-tag";
import {io} from "../helpers";

Vue.use(Vuex)

const vuexLocal = new VuexPersistence({
    storage: window.localStorage
})

let lastEventId = null
let sessionListener = null
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
    },
    getters: {
        isLoggedIn(state) {
            return !!state.jwt && !!state.jwtExpiry && new Date(state.jwtExpiry) > new Date()
        }
    },
    mutations: {
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

            state.eventSource.onmessage = function(e) {
                backoff = 0
                lastEventId = e.lastEventId

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
            }
            state.eventSource.onerror = function(e) {
                io.log('MERCURE ERROR:', e)
                setTimeout(function() {
                    store.commit('connectToMercure', backoff ? backoff * 2 : 2)
                }, backoff)
            }

            return state.eventSource
        },
        async selectProject(state, project) {
            state.selectedProject = project
        },
        startPollingSession(state) {
            if (!this.isLoggedIn || sessionListener) {
                return
            }
            const fetchSession = function() {
                apolloClient.query({
                    name: 'currentSession',
                    query: gql`
                        query currentSession {
                            currentSession {
                                username
                                email
                                id
                                _id
                            }
                        }
                    `,

                    update({currentSession}) {
                        if (!currentSession || parseInt(currentSession._id) !== parseInt(store.state.userData?.id)) {
                            store.commit('logout')
                        }
                    }
                }).then(function ({data:{currentSession}}) {
                    if (currentSession === null) {
                        store.commit('setPostLoginRedirect', router.currentRoute.fullPath)
                        store.commit('logout')
                    }
                })
            };

            sessionListener = setInterval(fetchSession, 10000);
            fetchSession()
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

            store.commit('startPollingSession')
        },
        async logout(state) {
            if (sessionListener) {
                clearInterval(sessionListener)
                sessionListener = null
            }
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

// Always poll session
store.commit('startPollingSession')
