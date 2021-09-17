import store from "../store/store";
import Token from "./Token";
import PKCE from "./PKCE"
import config from "../config";
import {apolloClient} from "../http/apollo";
import {router} from "../routes/routes";

function resolveCallback() {
    const {protocol, host, pathname, hash} = window.location
    return `${protocol}//${host}${pathname}${hash}`
}

function error(type, message) {
    return {
        error: type,
        error_description: message,
        message
    }
}
class Authentication {

    /**
     * @type {null|Token}
     */
    token = null

    constructor() {
        const self = this
    }

    getToken() {
        if (!this.token) {
            this.token = store.getters.token
        }

        return this.token
    }

    authorizeUrl() {
        const verifier = PKCE.createVerifier()
        const state = `${Math.random() * 10000000}`

        store.commit('updateAuthRequest', {verifier, state})

        const query = {
            response_type: "code",
            client_id: 'hosting',
            grant_type: "authorization_code",
            redirect_uri: resolveCallback(),
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
            state: state,
            code_challenge_method: "S256",
            code_challenge: PKCE.createChallenge(verifier),
        };

        const queryString = Object.keys(query)
            .map((key) => key + "=" + encodeURIComponent(query[key]))
            .join("&");

        return `${config.apiBaseUrl}/oauth/authorize` + "?" + queryString;
    }

    async requestToken(code, state) {
        if (state !== store.state.authRequest.state) {
            throw error('invalid_state', 'Invalid state provided, please try again.');
        }

        const data = {
            client_id: 'hosting',
            grant_type: "authorization_code",
            redirect_uri: resolveCallback(),
            code_verifier: store.state.authRequest.verifier,
            code,
        };

        const formData = new FormData();
        for (let key in data) {
            formData.set(key, data[key]);
        }

        const result = await fetch(`${config.apiBaseUrl}/oauth/token`, {
            method: "POST",
            body: formData,
        }).then(result => result.json());

        if (result.error) {
            throw result
        }

        const token = new Token(`${result.token_type}`, 0 + result.expires_in, `${result.access_token}`, `${result.refresh_token}`)
        this.token = token
        store.commit('updateToken', token)

        return this.token
    }

    async refresh() {
        const token = this.getToken()
        if (!token || !token.refresh) {
            throw error('missing_refresh', 'No refresh token available.')
        }

        const data = {
            client_id: 'hosting',
            grant_type: "refresh_token",
            refresh_token: token.refresh,
            redirect_uri: resolveCallback(),
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
            code_verifier: store.state.authRequest.verifier,
            code_challenge: PKCE.createChallenge(store.state.authRequest.verifier),
        };

        const formData = new FormData();
        for (let key in data) {
            formData.set(key, data[key]);
        }

        const result = await fetch(`${config.apiBaseUrl}/oauth/token`, {
            method: "POST",
            body: formData,
        }).then(result => result.json());

        if (result.error) {
            throw result
        }

        const newToken = new Token(`${result.token_type}`, 0 + result.expires_in, `${result.access_token}`, `${result.refresh_token}`)
        this.token = newToken
        store.commit('updateToken', newToken)

        return this.token
    }

    isLoggedIn() {
        return this.getToken() ? this.getToken().isActive() : false
    }

    async logout() {
        store.commit('updateToken', null)
        store.commit('setRoles', [])
        await apolloClient.resetStore();
        await router.replace('/api-login')
    }
}

export const auth = new Authentication();