<template>
    <div>
        <environments-header :project-name="project ? project.name : null" title="Installs"></environments-header>

        <card :loading="$apollo.loading || loading" :access-denied="accessDenied">
            <div class="card-body">
                <div>
                    <div class="mb-5">
                        <h4>Concrete Install</h4>
                    </div>
                    <div class="border-bottom border-top py-2 px-2">
                        <div class="container">
                            <div class="row align-items-center justify-content-between">
                                <div class="pr-3">
                                    <router-link :to="`/${$route.params.id}/`"><i style="cursor: pointer" class="fas fa-chevron-left"></i></router-link>
                                </div>
                                <div>
                                    <span>Install Concrete in the</span>
                                    <strong>{{ selectedEnvironment }}</strong>
                                    <span>environment</span>
                                </div>
                                <button @click="triggerBackup" class="btn btn-primary btn-sm">
                                    Start Install
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <table class="table w-100">
                            <thead>
                            <tr>
                                <th style="width:50%;">Date</th>
                                <th style="width:30%" class="text-center">Status</th>
                                <th>Duration</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-if="environmentTasks">
                                <deployment-row :key="node.id" v-for="{node} of environmentTasks.edges"
                                                :deploy-id="node.id"
                                                :created="node.dateCreated"
                                                :started="node.dateStarted"
                                                :ended="node.dateFulfilled"
                                                :status="node.fulfillmentStatus"
                                >
                                </deployment-row>
                            </template>
                            <template v-else>
                                <tr v-for="i in (new Array(4)).keys()" :key="i">
                                    <td>
                                        <blink-box :size="22"></blink-box>
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
import {M_TASK_CREATE_BACKUP, M_TASK_CREATE_INSTALL, Q_TASKS_BY_PROJECT} from "../../../../queries/tasks";
import EnvironmentsHeader from "./environments-header";
import store from "../../../../store/store";
import BlinkBox from "../../../basic/blink-box";
import {Q_QUERY_TASKS_LIST_SESSION_PROJECT} from "../../../../graphql/task";
import mercure from "../../../../http/Mercure";
import {strings} from "../../../../strings";

export default {
    components: {BlinkBox, EnvironmentsHeader, Header, Card, DeploymentRow},
    apollo: {
        environmentTasks: {
            query: Q_QUERY_TASKS_LIST_SESSION_PROJECT,
            variables: function() {
                return {
                    tasksProject: `/hosting_projects/${this.$route.params.id}`,
                    tasksEnvironmentName: this.$route.params.environment,
                    tasksGroup: 'install',
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
        environmentTasks: null,
        loading: false,
        eventSource: null,
        accessDenied: false,
    }),
    methods: {
        startMonitoring() {
            mercure.addListener('env/installs', (e) => {
                const data = JSON.parse(e.data)
                if (
                    data["project"] === hostingProjectId(this.$route.params.id)
                    && data['environmentName'] === this.$route.params.environment
                    && data['@type'] === 'Install'
                ) {
                    this.handleUpdate(data)
                }
            })
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
                mutation: M_TASK_CREATE_INSTALL,
                variables: {
                    projectId: `/hosting_projects/${this.$route.params.id}`,
                    environment: this.$route.params.environment,
                }
            })

            addToast(strings.toasts.addInstall)
        }
    },
    mounted() {
        this.startMonitoring()
    },
    beforeRouteLeave(to, from, next) {
        mercure.removeListener('env/deploys')
        next(vm => vm)
    },
    beforeRouteEnter(to, from, next) {
        next(vm => vm.$apollo.queries.environmentTasks.refetch())
    }
}
</script>
