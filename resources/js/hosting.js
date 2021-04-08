import Vue from 'vue';
import VueApollo from 'vue-apollo'
import Vuex from 'vuex';
import VueRouter from 'vue-router'
import hosting from './components/hosting'

Vue.use(VueRouter)
Vue.use(Vuex)
Vue.use(VueApollo)

const apolloClient = require('./http/apollo')['apolloClient']
const store = require('./store/store')['store']
const router = require('./routes/routes')['router']

const apolloProvider = new VueApollo({
    defaultClient: apolloClient
})

const app = new Vue({
    apolloProvider,
    router,
    template: '<hosting></hosting>',
    components: {
        hosting
    }
}).$mount('[data-pl-hosting-container]');
