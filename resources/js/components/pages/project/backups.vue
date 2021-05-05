<template>
    <card :loading="$apollo.loading || loading">
        <div class="card-body">
            <div>
                <div class="mb-5">
                    <h4>Backup</h4>
                </div>
                <div class="border-bottom border-top py-2 px-2">
                    <div class="container">
                        <div class="row align-items-center justify-content-between">
                            <div>
                                Backup
                                <span>the</span>
                                <select v-model="selectedEnvironment">
                                    <option v-for="env of expectedEnvironments" :value="env.name">{{ env.name }} ({{ env.type }})</option>
                                </select>
                                <span>environment</span>
                            </div>
                            <button @click="backup" class="btn btn-primary">Start</button>
                        </div>
                    </div>
                </div>
                <div v-if="backups.length" class="mt-5">
                    <table class="table w-100">
                        <thead>
                        <tr>
                            <th>Created</th>
                            <th>Environment</th>
                            <th class="text-center">Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="backup of backups">
                            <td>{{ formatDate(backup.dateCreated) }}</td>
                            <td>{{ backup.environmentName }}</td>
                            <td class="text-center"><span :class="statusColor(backup.status)">{{ backup.status }}</span></td>
                            <td class="text-right">
                                <a class="btn btn-secondary btn-sm" :href='fullUrl(backup.downloadUrl)' v-if="backup.downloadUrl">Download</a>
                                <button class="btn btn-secondary btn-sm" @click="restore(backup)" v-if="backup.downloadUrl">Restore</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </card>
</template>

<script>
import Card from "../../basic/card";
import {Q_PROJECT_BACKUPS} from "../../../queries/project";
import {dateFormat, expectedEnvironments, timeSince} from "../../../helpers";
import {M_ENVIRONMENT_BACKUP, M_ENVIRONMENT_RESTORE, M_ENVIRONMENT_INSTALL} from "../../../queries/environment";
import moment from 'moment-timezone'
import DeploymentRow from "../../basic/deployment-row";
import config from "../../../config";
import {store} from "../../../store/store";

export default {
    name: "backups",
    components: {DeploymentRow, Card},
    apollo: {
        backups: {
            query: Q_PROJECT_BACKUPS,
            variables: function() {
                return {
                    projectId: `hosting_projects/${this.$route.params.id}`,
                    perPage: 10,
                }
            },
            update(data) {
                this.project = data.hostingProject
                if (!this.selectedEnvironment) {
                    this.selectedEnvironment = this.project.productionBranch
                }

                return data.hostingProject.backups.edges.map(edge => edge.node)
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
        project: null,
        backups: [],
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
        formatDate(date) {
            return timeSince(date)
        },
        fullUrl(path) {
            return config.apiBaseUrl + path
        },
        downloadBackup(backup) {
            alert('This isn\'t working right now, check back later.')
            return
            const request = new XMLHttpRequest();
            request.open('GET', this.fullUrl(backup.downloadUrl))
            request.setRequestHeader('Authorization', 'Bearer ' + store.state.jwt)
            request.responseType = 'blob'

            request.onload = function(e) {
                const blob = e.currentTarget.response;
                const contentDispo = e.currentTarget.getResponseHeader('Content-Disposition');
                const fileName = contentDispo.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/)[1];

                const a = document.createElement('a');
                a.href = window.URL.createObjectURL(blob);
                a.download = fileName;
                a.dispatchEvent(new MouseEvent('click'));
            }
            request.send()
        },
        statusColor(status) {
            switch(status) {
                case 'ready':
                    return 'badge text-capitalize badge-success border border-success'
                case 'uploading':
                    return 'badge text-capitalize badge-secondary border border-secondary'
                default:
                    return 'badge text-capitalize border'
            }
        },
        backup() {
            const self = this
            this.loading = true
            const environment = this.selectedEnvironment

            this.$apollo.mutate({
                // Query
                mutation: M_ENVIRONMENT_BACKUP,
                // Parameters
                variables: {
                    projectId: this.project._id,
                    environment: environment,
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
        },
        restore(backup) {
            const self = this
            this.loading = true
            const environment = this.selectedEnvironment

            this.$apollo.mutate({
                // Query
                mutation: M_ENVIRONMENT_RESTORE,
                // Parameters
                variables: {
                    projectId: this.project._id,
                    environment: environment,
                    backup: backup._id
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
.table tr:nth-child(even) {
    background-color: rgba(216, 216, 216, .1);
}
</style>
