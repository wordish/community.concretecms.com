<template>
    <div>
        <environments-header :project-name="project ? project.name : null" title="Deploys"></environments-header>

        <card :loading="$apollo.loading || loading">
            <div class="card-body">
                <div>
                    <div class="mb-5">
                        <h4>Begin a Deployment</h4>
                    </div>
                    <div class="border-bottom border-top py-2 px-2">
                        <div class="container">
                            <div class="row align-items-center justify-content-between">
                                <div class="pr-3">
                                    <router-link :to="`/${$route.params.id}/`"><i style="cursor: pointer" class="fas fa-chevron-left"></i></router-link>
                                </div>
                                <div>
                                    <span>Deploy the</span>
                                    <strong>{{ selectedEnvironment }}</strong>
                                    <span>environment</span>
                                </div>
                                <button @click="triggerDeploy" class="btn btn-sm btn-primary">Start Deploy</button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <table class="table w-100">
                            <thead>
                            <tr>
                                <th style="width:30%">Date</th>
                                <th style="width:30%" class="text-center">Name</th>
                                <th style="width:30%" class="text-center">Status</th>
                                <th style="width:10%">Duration</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-if="deploys !== null">
                                <deployment-row :key="node.id" v-for="{node} of deploys.edges"
                                                :deploy-id="node.id"
                                                :created="node.dateCreated"
                                                :started="node.dateStarted"
                                                :ended="node.dateFulfilled"
                                                :status="node.fulfillmentStatus"
                                                :name="node.deployName ? node.deployName : '...'"
                                >
                                </deployment-row>
                            </template>
                            <template v-else>
                                <tr v-for="i in (new Array(4)).keys()" :key="i">
                                    <td>
                                        <blink-box :size="22"></blink-box>
                                    </td>
                                    <td class="text-center">
                                        <blink-box></blink-box>
                                    </td>
                                    <td class="text-center">
                                        <blink-box :size="7"></blink-box>
                                    </td>
                                    <td>
                                        <blink-box :min="2" :max="5"></blink-box>
                                    </td>
                                </tr>
                            </template>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </card>
    </div>
</template>
<script>
import Header from "../../../basic/header";
import Card from "../../../basic/card";
import DeploymentRow from "../../../basic/deployment-row";
import {Q_PROJECT_FULL} from "../../../../queries/project";
import {M_DEPLOY_CREATE, Q_DEPLOYS_BY_PROJECT} from "../../../../queries/deploys";
import {deployId, hostingProjectId} from "../../../../helpers";
import EnvironmentsHeader from "./environments-header";
import {store} from "../../../../store/store";
import BlinkBox from "../../../basic/blink-box";

export default {
    components: {BlinkBox, EnvironmentsHeader, Header, Card, DeploymentRow},
    apollo: {
        project: {
            query: Q_PROJECT_FULL,
            variables: function() {
                return {
                    projectId: `/hosting_projects/${this.$route.params.id}`
                }
            },
        },
        deploys: {
            query: Q_DEPLOYS_BY_PROJECT,
            variables: function() {
                return {
                    projectId: `/hosting_projects/${this.$route.params.id}`,
                    environment: this.$route.params.environment
                }
            },
            pollInterval: 30000
        }
    },
    computed: {
        selectedEnvironment() {
            return this.$route.params.environment
        }
    },
    data: () => ({
        project: null,
        deploys: null,
        loading: false,
        eventSource: null,
    }),
    methods: {
        startMonitoring() {
            if (this.status === 'fulfilled' || this.status === 'cancelled') {
                return
            }

            store.commit('setEventSourceListener', {key: 'env/deploys', listener: (e) => {
                const data = JSON.parse(e.data)
                if (data['@type'] !== 'Deploy') {
                    return
                }
                if (data["project"] === hostingProjectId(this.$route.params.id) && data['environmentName'] === this.$route.params.environment) {
                    this.handleUpdate(data)
                }
            }})
        },
        handleUpdate(data) {
            let deploy = null
            for (let { node } of this.deploys.edges) {
                if (node._id === data.id) {
                    deploy = node
                    break;
                }
            }

            if (deploy) {
                for (let key in deploy) {
                    if (deploy.hasOwnProperty(key)) {
                        deploy[key] = data[key]
                    }
                }

                deploy._id = deploy.id
                deploy.id = deployId(deploy.id)
            } else {
                this.deploys.edges.unshift({
                    node: {
                        id: deployId(data.id),
                        _id: data.id,
                        dateCreated: data.dateCreated,
                        dateFulfilled: data.dateFulfilled,
                        dateStarted: data.dateStarted,
                        dateUpdated: data.dateUpdated,
                        deployName: data.deployName,
                        environmentName: data.environmentName,
                        fulfillmentStatus: data.fulfillmentStatus
                    }
                });
            }
        },
        async triggerDeploy() {
            await this.$apollo.mutate({
                mutation: M_DEPLOY_CREATE,
                variables: {
                    projectId: `/hosting_projects/${this.$route.params.id}`,
                    environment: this.$route.params.environment
                }
            })
        }
    },
    mounted() {
        this.startMonitoring()
    },
    beforeRouteLeave(to, from, next) {
        store.commit('setEventSourceListener', {key: 'env/deploys', listener: null});
        next(vm => vm)
    },
    beforeRouteEnter(to, from, next) {
        next(vm => vm.$apollo.queries.deploys.refetch())
    }
}
</script>
