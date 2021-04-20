import Vuex from 'vuex'
import VuexPersistence from 'vuex-persist'
import {apolloClient} from '../http/apollo'
import {router} from '../routes/routes'
import Vue from 'vue';

Vue.use(Vuex)

const vuexLocal = new VuexPersistence({
    storage: window.localStorage
})

export const store = new Vuex.Store({
    state: {
        count: 0,
        jwt: '',
        selectedProject: null,
        addedProject: null,
    },
    getters: {
        isLoggedIn: (state) => !!state.jwt
    },
    mutations: {
        async selectProject(state, project) {
            console.log('CHANGING STATE')
            state.selectedProject = project
        },
        async login(state, jwt) {
            console.log('CHANGING STATE')
            state.jwt = jwt
            await apolloClient.resetStore();
        },
        async logout(state) {
            console.log('CHANGING STATE')
            state.jwt = ''
            await apolloClient.resetStore();
            await router.replace('/api-login')
        },
        createProject(state, project) {
            state.addedProject = project
        },
    },
    plugins: [vuexLocal.plugin]
})
