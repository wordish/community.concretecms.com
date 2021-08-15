<template>
    <div>
        <environments-header :project-name="project ? project.name : null" title="Backups"></environments-header>
        <card :loading="$apollo.loading || loading">
            <div class="card-body">
                <div>
                    <div class="mb-5">
                        <h4>Backups and Restores</h4>
                    </div>
                    <div class="border-bottom border-top py-2 px-2">
                        <div class="container">
                            <div class="row align-items-center justify-content-between">
                                <div class="pr-3">
                                    <router-link :to="`/${$route.params.id}/`"><i style="cursor: pointer" class="fas fa-chevron-left"></i></router-link>
                                </div>
                                <div>
                                    <span>Backup the</span>
                                    <strong>{{ selectedEnvironment }}</strong>
                                    <span>environment</span>
                                </div>
                                <button @click="triggerBackup" class="btn btn-primary btn-sm">
                                    Start Backup
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-if="environmentTasks" class="mt-5">
                        <table class="table w-100">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th class="text-center">Status</th>
                                <th>Duration</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <deployment-row :key="node.id" v-for="{node} of environmentTasks.edges"
                                            :deploy-id="node.id"
                                            :created="node.dateCreated"
                                            :started="node.dateStarted"
                                            :ended="node.dateFulfilled"
                                            :status="node.fulfillmentStatus"
                            >
                                <template v-slot:actions>
                                    <td>
                                        <button class="btn-text btn btn-sm" v-if="node.status === 'fulfilled'">Restore</button>
                                    </td>
                                </template>
                            </deployment-row>
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
import {M_TASK_CREATE_BACKUP, M_TASK_CREATE_INSTALL, Q_TASKS_BY_PROJECT} from "../../../../queries/tasks";
import EnvironmentsHeader from "./environments-header";
import {store} from "../../../../store/store";

export default {
    components: {EnvironmentsHeader, Header, Card, DeploymentRow},
    apollo: {
        project: {
            query: Q_PROJECT_FULL,
            variables: function() {
                return {
                    projectId: `/hosting_projects/${this.$route.params.id}`
                }
            },
        },
        environmentTasks: {
            query: Q_TASKS_BY_PROJECT,
            variables: function() {
                return {
                    projectId: `/hosting_projects/${this.$route.params.id}`,
                    environment: this.$route.params.environment,
                    group: 'backup'
                }
            }
        }
    },
    computed: {
        selectedEnvironment() {
            return this.$route.params.environment
        }
    },
    data: () => ({
        action: 'backup',
        project: null,
        environmentTasks: null,
        loading: false,
        eventSource: null,
    }),
    methods: {
        startMonitoring() {
            store.commit('setEventSourceListener', {key: 'env/backups', listener: (e) => {
                const data = JSON.parse(e.data)
                if (
                    data["project"] === hostingProjectId(this.$route.params.id)
                    && data['environmentName'] === this.$route.params.environment
                    && data['@type'] === 'Backup'
                ) {
                    this.handleUpdate(data)
                }
            }});
        },
        handleUpdate(data) {
            let task = null
            for (let { node } of this.environmentTasks.edges) {
                if (node._id === data.id) {
                    task = node
                    break;
                }
            }

            if (task) {
                for (let key in task) {
                    if (task.hasOwnProperty(key)) {
                        task[key] = data[key]
                    }
                }

                task._id = data.id
                task.id = data['@id']
            } else {
                this.environmentTasks.edges.unshift({
                    node: {
                        environmentName: data.environmentName,
                        taskType: data.taskType,
                        taskId: data.taskId,
                        group: data.group,
                        dateCreated: data.dateCreated,
                        dateUpdated: data.dateUpdated,
                        fulfillmentStatus: data.fulfillmentStatus,
                        dateFulfilled: data.dateFulfilled,
                        dateStarted: data.dateStarted,
                        status: data.status,
                        id: data['@id'],
                        _id: data.id,
                    }
                });
            }
        },
        async triggerBackup() {
            await this.$apollo.mutate({
                mutation: M_TASK_CREATE_BACKUP,
                variables: {
                    projectId: `/hosting_projects/${this.$route.params.id}`,
                    environment: this.$route.params.environment,
                }
            })
        }
    },
    mounted() {
        this.startMonitoring()
    },
    beforeRouteLeave(to, from, next) {
        store.commit('setEventSourceListener', {key: 'env/backups', listener: null});
        next(vm => vm)
    },
    beforeRouteEnter(to, from, next) {
        next(vm => vm.$apollo.queries.environmentTasks.refetch())
    }
}
</script>
