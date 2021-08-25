import Vue from 'vue';
import VueApollo from 'vue-apollo'
import Vuex from 'vuex';
import VueRouter from 'vue-router'
import hosting from './components/hosting'
import VueClipboard from "vue-clipboard2";

Vue.use(VueRouter)
Vue.use(Vuex)
Vue.use(VueApollo)
Vue.use(VueClipboard)

const store = require('./store/store')['store']
const apolloClient = require('./http/apollo')['apolloClient']
const router = require('./routes/routes')['router']

const apolloProvider = new VueApollo({
    defaultClient: apolloClient
})

Vue.config.devtools = process.env.NODE_ENV !== 'production'
const app = new Vue({
    apolloProvider,
    router,
    template: '<hosting></hosting>',
    components: {
        hosting
    }
}).$mount('[data-pl-hosting-container]');
