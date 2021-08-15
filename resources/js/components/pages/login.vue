<template>
    <div class="login-form w-50 m-auto">
        <form @submit.prevent.stop="attemptLogin()" method="post">
            <h2 class="text-center">Log in</h2>
            <div class="form-group">
                <button :disabled="authenticating" type="submit" class="btn btn-primary btn-block">Verify Login</button>
            </div>
        </form>
    </div>
</template>

<script>
import {store} from '../../store/store'
import {router} from '../../routes/routes'
import config from '../../config'
import { createChallenge, createVerifier } from "../../auth/PKCE";

const OAUTH_URL_AUTHORIZE = config.apiBaseUrl + '/oauth/authorize'
const OAUTH_URL_TOKEN = config.apiBaseUrl + '/oauth/token'

export default {
    name: "Login",
    data: () => ({
        username: '',
        password: '',
        authenticating: false,
        error: '',
    }),
    methods: {
        async attemptLogin() {
            const code = this.$route.query.code
            const state = this.$route.query.state

            if (code && state) {

                // Clear code from query
                this.clearQuery();

                if (state !== window.localStorage.getItem(config.login.stateKey)) {
                    alert('Unable to complete authentication. Please refresh to try again.')
                    return;
                }

                // Get the access token
                return this.attemptToken(code);
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

            console.log(verifier);

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
                await this.$router.replace('/')
            }
        },

        async attemptAuthorize() {
            console.log('Authorizing');
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
                    "ROLE_USER",
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

        clearQuery() {
            let query = Object.assign({}, this.$route.query);
            delete query.code;
            delete query.error;
            delete query.error_description;
            delete query.hint;
            delete query.message;
            delete query.state;
            this.$router.replace({ query });
        },

        resolveCallback() {
            const l = window.location
            return `${l.protocol}//${l.host}${l.pathname}${l.hash}`
        }

    },
    mounted() {
        this.attemptLogin()
    }
}
</script>

<style scoped>

</style>
