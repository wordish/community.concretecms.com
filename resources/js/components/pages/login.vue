<template>
    <div>
        <Header :showBreadcrumbs="false" :showUser="false" title='Login'>

        </Header>
        <Card>
            <div class="card-body">
                <form @submit.prevent.stop="attemptLogin()" method="post" class="text-center" v-if="!$route.query.code && !authenticating && !error">
                    <h3 class="text-center">Log in</h3>
                    <p>You don't have permission to view this page. Please log in to continue.</p>
                    <div class="form-group">
                        <button :disabled="authenticating" type="submit" class="btn btn-primary btn-sm">Log In</button>
                    </div>
                </form>
                <form @submit.prevent.stop="() => null" method="post" class="text-center" v-else-if="!error">
                    <h3 class="text-center">Log in</h3>
                    <p>You don't have permission to view this page. Please log in to continue.</p>
                    <div class="form-group">
                        <button disabled type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-spinner fa-spin"></i>
                        </button>
                    </div>
                </form>
                <template v-else>
                    <h3>Error during login</h3>
                    <p>{{error.message}}</p>
                    <button class="mx-auto btn btn-sm btn-primary" @click="() => error = null">Try again</button>
                </template>
            </div>
        </Card>
    </div>
</template>

<script>
import Header from '../basic/header'
import Card from '../basic/card'
import store from '../../store/store'
import {router} from '../../routes/routes'
import {auth} from '../../auth/Authentication'
import config from '../../config'
import { createChallenge, createVerifier } from "../../auth/PKCE";

const OAUTH_URL_AUTHORIZE = config.apiBaseUrl + '/oauth/authorize'
const OAUTH_URL_TOKEN = config.apiBaseUrl + '/oauth/token'

export default {
    components: {Card, Header},
    name: "Login",
    data: () => ({
        username: '',
        password: '',
        authenticating: false,
        error: null,
    }),
    methods: {
        async attemptLogin() {
            this.authenticating = true
            if (!this.$route.query.code || !this.$route.query.state) {
                return window.location.replace(auth.authorizeUrl())
            }

            const self = this
            await auth.requestToken(this.$route.query.code, this.$route.query.state).catch(function (error) {
                self.error = error
            }).finally(() => {
                self.$router.replace({'query': null, 'state': null})
                self.authenticating = false
            })

            const redirect = store.state.postLoginRedirect
            store.commit('setPostLoginRedirect', '/')

            this.$router.replace(redirect && redirect !== '/api-login' ? redirect : '/')
        },

    },
    mounted() {
        const self = this
        if (this.$route.query.code && this.$route.query.state) {
            setTimeout(async function () {
                await self.attemptLogin()
            })
        }
    }
}
</script>

<style scoped>

</style>
