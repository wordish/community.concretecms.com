<template>
    <card :loading="$apollo.loading || loading">
        <div class="card-body">
            <div v-if="!selectedEnvironment">
                <div class="mb-5">
                    <h4>Begin a Deployment</h4>
                    <p>Which environment are you deploying?</p>
                </div>
                <div class="container">
                    <div @click="selectedEnvironment=env" style="cursor: pointer" class="row py-3 border-bottom justify-content-center align-items-center" :key="env.name" v-for="env in expectedEnvironments">
                        <span class="col-3">
                            {{ env.name }}
                        </span>
                        <strong class="col-4">
                            {{env.type}}
                        </strong>
                        <i class="fas fa-chevron-right" />
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="mb-5">
                    <h4>Begin a Deployment</h4>
                </div>
                <div class="border-bottom border-top py-2 px-2">
                    <div class="container">
                        <div class="row align-items-center justify-content-between">
                            <div class="pr-3"><i @click="selectedEnvironment=null" style="cursor: pointer" class="fas fa-chevron-left"></i></div>
                            <div>
                                <span>Deploy the</span>
                                <strong>{{ selectedEnvironment.branch }}</strong>
                                <span>branch to the</span>
                                <strong>{{ selectedEnvironment.name }}</strong>
                                <span>environment</span>
                            </div>
                            <button @click="deploy" class="btn btn-primary">Start</button>
                        </div>
                    </div>
                </div>
                <div v-if="selectedEnvironment.deployments.length" class="mt-5">
                    <table class="table w-100">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <deployment-row :key="deployment.id" v-for="deployment of selectedEnvironment.deployments.slice(0, 20)"
                                            :started="deployment.created"
                                            :ended="deployment.completed"
                                            :status="deployment.status"
                                            :name="deployment.name"
                            >
                            </deployment-row>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </card>
</template>

<script>
import Card from "../../basic/card";
import {Q_PROJECT_FULL} from "../../../queries/project";
import {expectedEnvironments} from "../../../helpers";
import {M_ENVIRONMENT_DEPLOY} from "../../../queries/environment";
import moment from 'moment-timezone'
import DeploymentRow from "../../basic/deployment-row";
export default {
    name: "deployments",
    components: {DeploymentRow, Card},
    apollo: {
        project: {
            query: Q_PROJECT_FULL,
            variables: function() {
                return {
                    projectId: `projects/${this.$route.params.id}`
                }
            },
            result({data}) {
                for (const newEnvironment of data.project.lagoonProject.environments) {
                    if (newEnvironment.deployBaseRef === this.selectedEnvironment.branch) {
                        this.selectedEnvironment.deployments = newEnvironment.deployments
                    }
                }
            },
            pollInterval: 2000
        },
    },
    computed: {
        expectedEnvironments() {
            return expectedEnvironments(this.project)
        }
    },
    data: () => ({
        selectedEnvironment: null,
        loading: false
    }),
    methods: {
        notImplemented() {
            alert('Not implemented.')
        },
        duration(deployment) {
            if (deployment.status !== 'running' && (!deployment.created || !deployment.completed)) {
                return null
            }

            const start = moment.tz(deployment.created, 'UTC')
            const end = deployment.completed ? moment.tz(deployment.completed, 'UTC') : moment()

            let duration = Math.floor(Math.max(0, (end - start) / 1000))
            const hours = Math.floor(duration / 3600)
            duration = duration % 3600
            const minutes = Math.floor(duration / 60)
            let seconds = duration % 60

            if (isNaN(seconds)) {
                seconds = 0
            }

            return [
                hours ? `${hours}m` : '',
                minutes ? `${minutes}m` : '',
                `${seconds}s`
            ].join('')
        },
        formattedStartDate(deployment) {
            return moment.tz(deployment.created, 'UTC').tz(moment.tz.guess()).format('DD MMM YYYY h:mm a')
        },
        deploy() {
            const self = this
            this.loading = true
            const environment = this.selectedEnvironment
            this.$apollo.mutate({
                // Query
                mutation: M_ENVIRONMENT_DEPLOY,
                // Parameters
                variables: {
                    projectId: this.project._id,
                    branch: environment.branch
                },
                update: (store, { data: { deployProject } }) => {
                    self.project.lagoonProject = {
                        ...(self.project.lagoonProject ? self.project.lagoonProject : {}),
                        environments: deployProject.project.lagoonProject.environments
                    }

                    for (const newEnvironment of deployProject.project.lagoonProject.environments) {
                        if (newEnvironment.deployBaseRef === self.selectedEnvironment.branch) {
                            self.selectedEnvironment.deployments = newEnvironment.deployments
                        }
                    }
                }
            }).catch((error) => {
                debugger
            }).finally(() => {
                this.loading = false;
            })
        }
    }
}
</script>

<style scoped>
.row:last-child {
    border:none !important;
}
.table td {
    opacity: 70%;
}
.table tr:nth-child(even) {
    background-color: rgba(216, 216, 216, .1);
}
</style>
