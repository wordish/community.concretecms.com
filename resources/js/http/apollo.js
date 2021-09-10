
import { ApolloClient } from 'apollo-client'
import { createHttpLink } from 'apollo-link-http'
import { InMemoryCache } from 'apollo-cache-inmemory'
import { setContext } from 'apollo-link-context'
import { onError } from 'apollo-link-error'
import {store} from '../store/store'
import { router } from '../routes/routes'
import config from '../config'
import {addDevToast, addToast, io} from "../helpers";

const httpLink = createHttpLink({
    uri: `${config.apiBaseUrl}/graphql`,
})

const authLink = setContext(async (_, { headers }) => {
    // get the authentication token from local storage if it exists
    const token = store.state.jwt

    // Return the headers to the context so httpLink can read them
    return {
        headers: {
            ...headers,
            Authorization: token ? `Bearer ${token}` : 'Bearer foo'
        }
    }
})

const errorLink = onError(({graphQLErrors, networkError}) => {
    let needsAuth = false
    if (graphQLErrors)
        graphQLErrors.forEach(({ message, locations, path, extensions }) => {
            needsAuth = needsAuth ? needsAuth : (message === 'Access Denied.')

            const allowedMessages = [
                'Access Denied.',
            ]
            if (allowedMessages.indexOf(message) !== -1 || extensions.category === 'user') {
                addToast(message, 10, extensions.status < 500 ? 'warning' : 'danger')
            } else {
                addDevToast('Graphql Error: ' + message, 60, 'danger')
            }
        });

    if (networkError) {
        io.log(`[Network error ${networkError.statusCode}]: ${networkError}`);
        addDevToast(`Network Error ${networkError.statusCode}: ${networkError.message}`, 60, 'danger')
    }

    if ((networkError && networkError.statusCode === 401) || needsAuth) {
        if (store.getters.isLoggedIn) {
            store.commit('setPostLoginRedirect', router.currentRoute.fullPath)
            store.commit('logout')
            router.replace('/api-login');
        }
    }
})

// Create the apollo client
export const apolloClient = new ApolloClient({
    link: errorLink.concat(authLink.concat(httpLink)),
    cache: new InMemoryCache(),
    connectToDevTools: true
})

