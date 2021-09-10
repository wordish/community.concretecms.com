<template>
    <div>
        <Header :showBreadcrumbs="false" :showUser="false" title='Login'>

        </Header>
        <Card>
            <div class="card-body">
                <form @submit.prevent.stop="attemptLogin()" method="post" class="text-center" v-if="!$route.query.code && !authenticating">
                    <h3 class="text-center">Log in</h3>
                    <p>You don't have permission to view this page. Please log in to continue.</p>
                    <div class="form-group">
                        <button :disabled="authenticating" type="submit" class="btn btn-primary btn-sm">Log In</button>
                    </div>
                </form>
                <form @submit.prevent.stop="() => null" method="post" class="text-center" v-else>
                    <h3 class="text-center">Log in</h3>
                    <p>You don't have permission to view this page. Please log in to continue.</p>
                    <div class="form-group">
                        <button disabled type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-spinner fa-spin"></i>
                        </button>
                    </div>
                </form>
            </div>
        </Card>
    </div>
</template>

<script>
import Header from '../basic/header'
import Card from '../basic/card'
import {store} from '../../store/store'
import {router} from '../../routes/routes'
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
        error: '',
    }),
    methods: {
        async attemptLogin() {
            this.authenticating = true
            if (this.$route.query.code && state) {
                if (state !== window.localStorage.getItem(config.login.stateKey)) {
                    alert('Unable to complete authentication. Please refresh to try again.')
                    return;
                }

                // Get the access token
                return this.attemptToken(this.$route.query.code);
            }

            return this.attemptAuthorize()
        },

        async attemptToken(code) {
            const verifier = window.localStorage.getItem(config.login.pkceKey);

            if (!code || !verifier) {
                alert("Invalid state, please try logging in again.");

                return;
            }

            const request = new XMLHttpRequest();
            const data = {
                client_id: config.login.oauthClient,
                grant_type: "authorization_code",
                redirect_uri: this.resolveCallback(),
                code_verifier: verifier,
                code,
            };

            const formData = new FormData();
            for (let key in data) {
                formData.set(key, data[key]);
            }

            const result = await fetch(OAUTH_URL_TOKEN, {
                method: "POST",
                body: formData,
            }).then(result => result.json());

            if (result.error) {
                this.oauthError = result.error;
                this.oauthErrorDescription = result.error_description;
                this.oauthHint = result.hint;
            } else {
                store.commit('login', result.access_token)
                await this.$router.replace('/').catch(() => {})
            }
        },

        async attemptAuthorize() {
            const verifier = createVerifier();
            const randomState = Math.random();

            // Store verifier
            window.localStorage.setItem(config.login.pkceKey, verifier);

            // Store expected state
            window.localStorage.setItem(config.login.stateKey, randomState.toString());

            const query = {
                response_type: "code",
                client_id: config.login.oauthClient,
                redirect_uri: this.resolveCallback(),
                scope: [
                    "PROJECT_VIEW",
                    "PROJECT_CREATE",
                    "PROJECT_EDIT",
                    "PROJECT_DELETE",
                    "PROJECT_BACKUP",
                    "PROJECT_RESTORE",
                    "PROJECT_DEPLOY",
                    "PROJECT_INSTALL",
                    "PROJECT_LIST",
                ].join(" "),
                state: randomState,
                code_challenge_method: "S256",
                code_challenge: createChallenge(verifier),
            };

            const queryString = Object.keys(query)
                .map((key) => key + "=" + encodeURIComponent(query[key]))
                .join("&");

            window.location = OAUTH_URL_AUTHORIZE + "?" + queryString;
        },

        resolveCallback() {
            const l = window.location
            return `${l.protocol}//${l.host}${l.pathname}${l.hash}`
        }

    },
    mounted() {
        const code = this.$route.query.code
        if (code) {
            const self = this
            setTimeout(function() {
                self.attemptToken(code)
            })
        }
    }
}
</script>

<style scoped>

</style>
