export default {
    apiBaseUrl: process.env.MIX_CONCRETE_API_URL,
    mercureUrl: process.env.MIX_MERCURE_URL,
    mercureToken: process.env.MIX_MERCURE_TOKEN,
    login: {
        pkceKey: 'PKCE_VERIFIER',
        jwtKey: 'JWT',
        stateKey: 'OAUTH_STATE',
        codeKey: 'OAUTH_CODE',
        oauthClient: 'hosting',
    }
}
