<template>
    <div>
        <environments-header :project-name="project ? project.name : null" title="Backups"></environments-header>
        <card :loading="$apollo.loading || loading" :access-denied="accessDenied">
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
                    <div class="mt-5">
                        <table class="table w-100">
                            <thead>
                            <tr>

                                <th style="width:30%">Date</th>
                                <th style="width:30%" class="text-center">Type</th>
                                <th class="text-center">Status</th>
                                <th style="width:10%">Duration</th>
                                <th style="width:10%"></th>
                            </tr>
                            </thead>
                            <tbody>
                                <template v-if="tasks">
                                <deployment-row :key="node.id" v-for="{node} of tasks.edges"
                                                :deploy-id="node.id"
                                                :created="node.dateCreated"
                                                :started="node.dateStarted"
                                                :name="node.taskType"
                                                :ended="node.dateFulfilled"
                                                :status="node.fulfillmentStatus"
                                >
                                    <template v-slot:actions>
                                        <td>
                                            <button class="btn-text btn btn-sm" v-if="node.taskType === 'Backup' && node.fulfillmentStatus === 'fulfilled'" @click="triggerRestore(node.id)">
                                                {{ $t.buttons.addRestore }}
                                            </button>
                                        </td>
                                    </template>
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
import {addToast, deployId, hostingProjectId, validateSessionUpdate} from "../../../../helpers";
import {
    M_TASK_CREATE_BACKUP,
    M_TASK_CREATE_INSTALL,
    M_TASK_CREATE_RESTORE,
    Q_TASKS_BY_PROJECT
} from "../../../../queries/tasks";
import EnvironmentsHeader from "./environments-header";
import store from "../../../../store/store";
import BlinkBox from "../../../basic/blink-box";
import {strings} from "../../../../strings";
import {Q_QUERY_TASKS_LIST_SESSION_PROJECT} from "../../../../graphql/task";
import mercure from "../../../../http/Mercure";

export default {
    components: {BlinkBox, EnvironmentsHeader, Header, Card, DeploymentRow},
    apollo: {
        tasks: {
            query: Q_QUERY_TASKS_LIST_SESSION_PROJECT,
            variables: function() {
                return {
                    tasksProject: `/hosting_projects/${this.$route.params.id}`,
                    tasksEnvironmentName: this.$route.params.environment,
                    tasksGroup: 'backup',
                    projectId: `/hosting_projects/${this.$route.params.id}`,
                    tasksOrder: [
                        {dateCreated: 'desc'}
                    ]
                }
            },
            update(result) {
                const tasks = validateSessionUpdate('tasks')(result)
                if (tasks && result.project) {
                    this.project = result.project
                }

                return tasks
            },
            error({gqlError: {message}}) {
                this.accessDenied = message === 'Access Denied.'
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
        tasks: null,
        loading: false,
        eventSource: null,
        accessDenied: false,
    }),
    methods: {
        startMonitoring() {
            mercure.addListener('env/backups', (e) => {
                const data = JSON.parse(e.data)
                if (
                    data["project"] === hostingProjectId(this.$route.params.id)
                    && data['environmentName'] === this.$route.params.environment
                    && data['group'] === 'backup'
                ) {
                    this.handleUpdate(data)
                }
            })
        },
        handleUpdate(data) {
            let task = null
            for (let { node } of this.tasks.edges) {
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
                this.tasks.edges.unshift({
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
            addToast(strings.toasts.addBackup)
        },
        async triggerRestore(id) {
            await this.$apollo.mutate({
                mutation: M_TASK_CREATE_RESTORE,
                variables: {
                    projectId: `/hosting_projects/${this.$route.params.id}`,
                    environment: this.$route.params.environment,
                    backupId: id,
                }
            })
        }
    },
    mounted() {
        this.startMonitoring()
    },
    beforeRouteLeave(to, from, next) {
        mercure.removeListener('env/backups')
        next(vm => vm)
    },
    beforeRouteEnter(to, from, next) {
        next(vm => vm.$apollo.queries.tasks.refetch())
    }
}
</script>
