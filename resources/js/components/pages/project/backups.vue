<template>
    <card :loading="$apollo.loading || loading">
        <div class="card-body">
            <div v-if="!selectedEnvironment">
                <div class="mb-5">
                    <h4>Backups</h4>
                    <p>Backups are smart, don't be a dummy.</p>
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
                    <h4>Backup</h4>
                </div>
                <div class="border-bottom border-top py-2 px-2">
                    <div class="container">
                        <div class="row align-items-center justify-content-between">
                            <div class="pr-3"><i @click="selectedEnvironment=null" style="cursor: pointer" class="fas fa-chevron-left"></i></div>
                            <div>
                                <select v-model="action">
                                    <option value="backup">Backup</option>
                                    <option value="restore">Restore</option>
                                    <option value="install">Install</option>
                                </select>
                                <span>the</span>
                                <strong>{{ selectedEnvironment.name }}</strong>
                                <span>environment</span>
                            </div>
                            <button @click="deploy" class="btn btn-primary">Start</button>
                        </div>
                    </div>
                </div>
                <div v-if="selectedEnvironment.tasks.length" class="mt-5">
                    <table class="table w-100">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Duration</th>
                        </tr>
                        </thead>
                        <tbody>
                        <deployment-row :key="task.id" v-for="task of selectedEnvironment.tasks.slice(0, 20)"
                                        :started="task.created"
                                        :created="task.created"
                                        :ended="task.completed"
                                        :status="task.status"
                                        :name="task.name"
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
import {M_ENVIRONMENT_BACKUP, M_ENVIRONMENT_RESTORE, M_ENVIRONMENT_INSTALL} from "../../../queries/environment";
import moment from 'moment-timezone'
import DeploymentRow from "../../basic/deployment-row";
export default {
    name: "backups",
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
                        this.selectedEnvironment.tasks = newEnvironment.tasks
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
        action: 'backup',
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
                mutation: {
                    'backup': M_ENVIRONMENT_BACKUP,
                    'restore': M_ENVIRONMENT_RESTORE,
                    'install': M_ENVIRONMENT_INSTALL,
                }[this.action],
                // Parameters
                variables: {
                    projectId: this.project._id,
                    environment: environment.name,
                },
                update: (store, { data: { deployProject } }) => {
                    self.project.lagoonProject = {
                        ...(self.project.lagoonProject ? self.project.lagoonProject : {}),
                        environments: deployProject.project.lagoonProject.environments
                    }

                    for (const newEnvironment of deployProject.project.lagoonProject.environments) {
                        if (newEnvironment.deployBaseRef === self.selectedEnvironment.branch) {
                            self.selectedEnvironment.tasks = newEnvironment.tasks
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
