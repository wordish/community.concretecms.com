import {apolloClient} from "../http/apollo";
import gql from "graphql-tag";
import {router} from "../routes/routes";

const sessionTracker = {
    interval: null,
    wait: 10,
    stop() {
        if (sessionTracker.interval) {
            clearInterval(sessionTracker.interval)
            sessionTracker.interval = null
        }

        return sessionTracker
    },
    start(store) {
        if (sessionTracker.interval) {
            sessionTracker.interval = window.setInterval(() => sessionTracker.check(store), sessionTracker.wait * 1000)
            sessionTracker.check(store)
        }

        return sessionTracker
    },
    restart() {
        this.stop().start()
    },
    check(store) {
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
    }
}

export default sessionTracker
