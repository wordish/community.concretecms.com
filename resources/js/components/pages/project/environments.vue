<template>
    <div>
        <project-header :project-name="project ? project.name : ''" title="Environments"></project-header>
        <card :loading="$apollo.loading">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:30%">NAME</th>
                            <th style="width:30%">BRANCH</th>
                            <th style="width:30%">LOCATION TYPE</th>
                            <th style="width:10%" class="text-right">
                                <button class="btn btn-sm btn-info" v-if="showAddButton" @click="addEnvironmentModalOpen = true" title="Add Environment">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button class="btn btn-sm btn-info" v-else disabled title="Environment limit has been reached.">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tr class="ph-item border-0">
                        <td>
                            <div class="ph-row">
                                <div class="ph-col-12"></div>
                            </div>
                        </td>
                    </tr>

                    <template v-if="!$apollo.loading && project">
                        <tr v-if="project" v-for="{node: env} in environments.edges" :style="env.fulfillmentStatus === 'terminated' ? 'opacity:.333;' : ''">
                            <td><router-link :to="`env/${env.environmentName}/deploys`">{{ env.environmentName }}</router-link></td>
                            <td><div class="badge badge-light border">{{ env.environmentName }}</div></td>
                            <td>{{ env.environmentType === 'PRODUCTION' ? 'Production' : 'Development' }}</td>
                            <td class="text-right">
                                <a :title="env.services.filter((v, i, s) => s.indexOf(v) === i).join(', ')" :href="env.route" target="_blank" class="badge badge-success" v-if="env.services.length && env.fulfillmentStatus !== 'terminated'">
                                    Running <i class="fas fa-link fa-sm"></i>
                                </a>
                                <span class="badge badge-info" v-else-if="env.fulfillmentStatus !== 'terminated'">
                                    Pending
                                </span>
                                <span class="badge badge-gray" v-else>
                                    Terminated
                                </span>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr v-for="i in (new Array(3)).keys()" :key="i">
                            <td><blink-box></blink-box></td>
                            <td><blink-box></blink-box></td>
                            <td><blink-box></blink-box></td>
                            <td><blink-box :min="4" :max="4"></blink-box></td>
                        </tr>
                    </template>
                </table>
            </div>
        </card>
        <pagination
            @next="nextPage"
            @previous="previousPage"
            :current="this.currentPage"
            :total="environments ? environments.totalCount : 1"
            :page-size="this.count"
            :disabled="$apollo.loading"
        ></pagination>
        <modal v-model="addEnvironmentModalOpen">
            <template v-slot:title="{close}">
                Add an environment
            </template>
            <template v-slot:body="{state}">
                <p>
                    Select a branch to add an environment
                </p>
                <branch-selector v-model="state.branch" :project-id="$route.params.id"></branch-selector>
            </template>
            <template v-slot:footer="{state, close}">
                <button v-if="state.branch" @click="() => addEnvironment(state.branch, close)" class="btn btn-primary btn-sm">Add "{{state.branch}}" Environment</button>
                <button v-else disabled class="btn btn-primary btn-sm">Add Environment</button>
                <button type="button" class="btn btn-secondary btn-sm" @click="close">Cancel</button>
            </template>
        </modal>
    </div>
</template>

<script>
import { Q_PROJECT_FULL } from "../../../queries/project";
import Card from "../../basic/card";
import {hostingProjectId} from "../../../helpers";
import Header from "../../basic/header"
import ProjectHeader from "./project-header";
import BlinkBox from "../../basic/blink-box";
import {M_ENVIRONMENT_CREATE, Q_ENVIRONMENTS_BY_PROJECT} from "../../../queries/environment";
import Pagination from "../../basic/pagination";
import Modal from "../../basic/modal";
import BranchSelector from "../../basic/branch-selector";

export default {
    name: "environments",
    components: {BranchSelector, Modal, Pagination, BlinkBox, ProjectHeader, Header, Card},
    computed: {
        showAddButton() {
            if (this.loading !== 0 || !this.environments || !this.project) {
                return false;
            }

            let environmentsLeft = this.project.developmentEnvironmentsLimit;
            for (const {node: {environmentType}} of this.environments.edges) {
                if (environmentType === 'DEVELOPMENT') {
                    environmentsLeft--;
                }
            }

            return environmentsLeft > 0
        }
    },
    apollo: {
        project: {
            query: Q_PROJECT_FULL,
            variables: function() {
                return {
                    projectId: hostingProjectId(this.$route.params.id)
                }
            },
            loadingKey: 'loading',
            fetchPolicy: 'cache-and-network',
        },
        environments: {
            query: Q_ENVIRONMENTS_BY_PROJECT,
            variables: function() {
                return {
                    projectId: hostingProjectId(this.$route.params.id),
                    perPage: this.count
                }
            }
        },
    },
    methods: {
        urlFor(env) {
            if (typeof env['routes'] !== 'object') {
                return ''
            }

            let result = '';
            if (typeof env['routes']['main'] === 'string') {
                result = env['routes']['main']
            }

            if (typeof env['routes']['routes'] === 'object' && typeof env['routes']['routes'][0] === 'string') {
                result = env['routes']['routes'][0]
            }

            return result === 'undefined' ? '' : result;
        },
        async addEnvironment(name, close) {
            await this.$apollo.mutate({
                mutation: M_ENVIRONMENT_CREATE,
                variables: {
                    projectId: hostingProjectId(this.$route.params.id),
                    environmentName: name
                }
            })

            await this.$apollo.queries.environments.refetch()
            close()
        },

        async changePage(after, before, difference) {
            const self = this
            await this.$apollo.queries.environments.fetchMore({
                variables: {
                    perPage: self.count,
                    after: after,
                    before: before,
                },
                updateQuery: (previousResult, { fetchMoreResult }) => {
                    const newEdges = fetchMoreResult.environments.edges
                    const pageInfo = fetchMoreResult.environments.pageInfo

                    self.currentPage += difference
                    return newEdges.length ? {
                        ...previousResult,
                        environments: {
                            ...previousResult.environments,
                            // Concat edges
                            edges: [
                                ...newEdges,
                            ],
                            // Override with new pageInfo
                            pageInfo,
                        }
                    } : previousResult
                }
            })
        },
        async nextPage() {
            await this.changePage(this.environments.pageInfo.endCursor, null,1);
        },
        async previousPage() {
            await this.changePage(null, this.environments.pageInfo.startCursor, -1);
        },
    },
    data: () => ({
        selectedBranch: null,
        loading: 0,
        currentPage: 1,
        count: 10,
        project: null,
        environments: null,
        addEnvironmentModalOpen: false,
    })
}
</script>

<style scoped>

</style>
