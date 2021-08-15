<template>
    <tr :class="rowStatusClass">
        <td>
            {{formattedCreationDate}}
        </td>
        <td v-if="name" class="text-center">
            <span class="badge badge-light-gray border pointer" @click="$copyText(name)">{{name}} <i class="ml-2 far fa-copy"></i></span>
        </td>
        <td class="text-center">
            <span :class="statusClass">{{this.normalStatus}}</span>
        </td>
        <td>
            {{duration}}
        </td>
        <slot name="actions"></slot>
    </tr>
</template>

<script>
import moment from "moment-timezone";
import {dateFormat} from "../../helpers";
import gql from "graphql-tag/lib/graphql-tag.umd";
import {F_DEPLOY_FULL, Q_DEPLOY_FULL} from "../../queries/deploys";

export default {
    name: "deployment-row",
    // apollo: {
    //     deploy: {
    //         query: Q_DEPLOY_FULL,
    //         variables: function() {
    //             return {
    //                 deployId: this.deployId
    //             }
    //         },
    //         skip: function() {
    //             return this.status === 'fulfilled' || this.status === 'cancelled'
    //         },
    //         // pollInterval: 2000
    //         subscribeToMore: {
    //             document: gql`
    //             ${F_DEPLOY_FULL}
    //             subscription deployChanges($deployId: ID!) {
    //                 updateDeploySubscribe(input: {
    //                     id: $deployId
    //                 }) {
    //                     deploy {
    //                         ...DeployFields
    //                     }
    //                     mercureUrl
    //                 }
    //             }
    //             `,
    //             variables: function() {
    //                 return {
    //                     deployId: this.deployId
    //                 }
    //             },
    //
    //             updateQuery: function(previousResult, {subscriptionData: {data: {updateDeploySubscribe: {deploy, mercureUrl}}}}) {
    //                 this.name = deploy.deployName
    //                 this.created = deploy.dateCreated
    //                 this.started = deploy.dateStarted
    //                 this.ended = deploy.dateEnded
    //                 this.mercureUrl = mercureUrl.replace('http://mercure:8126', 'http://api.concretecms.test:8126')
    //                 this.startMonitoring()
    //             },
    //         }
    //     },
    // },
    props: {
        name: {type: String, required: false},
        created: {type: String, required: true},
        status: {type: String, required: true},
        started: {type: String, default: null},
        ended: String,
        deployId: String,
    },
    mounted() {
        this.startInterval()
        this.updateDuration()
    },
    updated() {
        this.startInterval()
        this.updateDuration()
    },
    computed: {
        normalStatus() {
            const status = {
                fulfilled: 'succeeded',
                started: 'running',
                unfulfilled: 'pending',
            }[this.status]

            return status ? status : this.status

        },
        statusClass() {
            const statusClass = {
                'pending': 'badge badge-info',
                'running': 'badge badge-info blink',
                'succeeded': 'badge badge-success',
                'failed': 'badge badge-danger',
                'cancelled': 'badge badge-warning',
            }[this.normalStatus]

            return statusClass ? statusClass : ''
        },
        rowStatusClass() {
            return this.normalStatus
        },
        formattedCreationDate() {
            return dateFormat(this.created)
        },
    },
    methods: {
        startInterval() {
            const self = this
            if (this.normalStatus === 'running' && !this.interval) {
                this.interval = setInterval(function () {
                    self.updateDuration()
                    if (self.ended) {
                        clearInterval(self.interval)
                        self.interval = null
                    }
                }, 1000)
            }
        },
        startMonitoring() {
            if (!this.mercureUrl) {
                return;
            }

            if (this.eventSource) {
                this.eventSource.close()
                this.eventSource = null
            }

            if (this.status === 'fulfilled' || this.status === 'cancelled') {
                return
            }

            this.eventSource = new EventSource(this.mercureUrl, {
                headers: {
                    Authorization: "Bearer eyJhbGciOiJIUzI1NiJ9.eyJtZXJjdXJlIjp7InN1YnNjcmliZSI6WyJkZXBsb3kiXX19.zRDc_96jM-z3s07PFYsUW-3LXXPLzn9pM8f-LKuHrnM"
                }
            })
            this.eventSource.onmessage = function (e) {
                this.handleEventMessage(e)
            }
        },
        handleEventMessage(e) {
            alert('GOT MESSAGE')
            debugger
        },
        updateDuration() {
            if (this.normalStatus !== 'running' && !this.ended) {
                this.duration = ''
                return
            }

            const start = moment.tz(this.started, 'UTC')
            const end = this.ended ? moment.tz(this.ended, 'UTC') : moment()

            let duration = Math.floor(Math.max(0, (end - start) / 1000))
            const hours = Math.floor(duration / 3600)
            duration = duration % 3600
            const minutes = Math.floor(duration / 60)
            let seconds = duration % 60

            if (isNaN(seconds)) {
                seconds = 0
            }

            this.duration = [
                hours ? `${hours}h` : '',
                minutes ? `${minutes}m` : '',
                `${seconds}s`
            ].join('')
        }
    },
    data: () => ({
        interval: null,
        duration: '',
        eventSource: null,
        mercureUrl: null,
    }),
}
</script>

<style lang="scss" scoped>
@keyframes blink {
    100% { color: white; }
    50% { color: #17a2b8; }
    0% { color: white; }
}
.blink {
    animation: blink 1s infinite;
}
.badge {
    text-transform: uppercase;
    font-size: .5rem;
    padding: .25rem;
}
</style>
