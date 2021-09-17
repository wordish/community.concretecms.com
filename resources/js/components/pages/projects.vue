<template>
    <div>
        <Header>
            <template v-slot:extraContent>
                <div class="d-flex flex-column justify-content-center">
                    <button @click="showModal=true" class="btn btn-primary text-nowrap" v-if="isAdmin">
                        {{ $t.buttons.addProject }}
                    </button>
                </div>
            </template>
        </Header>
        <card :loading="$apollo.loading && projects === null" :access-denied="accessDenied">
            <div class="card-body">
                <table class="table" v-if="$apollo.loading || (projects !== null && projects.edges.length !== 0)">
                    <thead>
                    <tr>
                        <th style="width:33%">Name</th>
                        <th style="width:33%">Type</th>
                        <th class="text-center">Handle</th>
                    </tr>
                    </thead>
                    <template v-if="!$apollo.loading && projects !== null">
                        <tr v-for="{node} in projects.edges" :style="node.fulfillmentStatus === 'terminated' ? 'opacity:.333' : ''">
                            <td><router-link :to="node._id + ''">{{ node.name }}</router-link></td>
                            <td>{{ node.startingPoint.name }}</td>
                            <td class="text-center">
                                <a href="#" class="badge badge-light-gray border pointer" @click.prevent="$copyText(node.lagoonName)">{{node.lagoonName}} <i class="ml-2 far fa-copy"></i></a>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr v-for="i in (new Array(loadingNodes)).keys()" :key="i">
                            <td>
                                <blink-box></blink-box>
                            </td>
                            <td>
                                <blink-box></blink-box>
                            </td>
                            <td class="text-center">
                                <blink-box></blink-box>
                            </td>
                        </tr>
                    </template>
                </table>
                <div class="" v-else>
                    <h4 class="text-center text-muted">You don't have any projects!</h4>
                </div>
            </div>
        </card>
        <pagination @next="nextPage" @previous="previousPage" :current="this.currentPage" :total="projects !== null ? projects.totalCount : 1" :page-size="this.count"></pagination>
        <create-project-modal v-model="showModal" @create="handleProjectCreate"></create-project-modal>
    </div>
</template>

<script>
import Card from "../basic/card";
import store from "../../store/store";
import Header from "../basic/header";
import CreateProjectModal from "../basic/create-project-modal";
import BlinkBox from "../basic/blink-box";
import Pagination from "../basic/pagination";
import StatusBadge from "../basic/status-badge";
import {Q_PROJECT_LIST_SESSION} from "../../graphql/project";
import {validateSessionUpdate} from "../../helpers";

export default {
    name: "projects",
    components: {StatusBadge, Pagination, CreateProjectModal, Header, Card, BlinkBox},
    apollo: {
        projects: {
            query: Q_PROJECT_LIST_SESSION,
            update: validateSessionUpdate('projects'),
            variables() {
                return {
                    projectsBefore: null,
                    projectsAfter: null,
                    projectsFirst: this.count
                }
            },
        }
    },
    data: () => ({
        accessDenied: false,
        projects: null,
        showModal: false,
        currentPage: 1,
        count: 15,
        totalCount: 15,
        loadingNodes: 5,
    }),
    computed: {
        isAdmin() {
            return store.getters.isAdmin
        }
    },
    methods: {
        async changePage(after, before, difference) {
            const self = this
            await this.$apollo.queries.projects.fetchMore({
                variables: {
                    projectsBefore: before,
                    projectsAfter: after,
                    projectsFirst: self.count
                },
                updateQuery: (previousResult, { fetchMoreResult }) => {
                    const newEdges = fetchMoreResult.projects.edges
                    const pageInfo = fetchMoreResult.projects.pageInfo

                    self.currentPage += difference
                    return newEdges.length ? {
                        ...previousResult,
                        projects: {
                            ...previousResult.projects,
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
            if (this.projects === null) {
                return;
            }
            this.loadingNodes = this.projects.edges.length
            await this.changePage(this.projects.pageInfo.endCursor, null,1);
        },
        async previousPage() {
            if (this.projects === null) {
                return;
            }
            this.loadingNodes = this.projects.edges.length
            await this.changePage(null, this.projects.pageInfo.startCursor, -1);
        },
        async handleProjectCreate(e) {
            if (this.currentPage > 1) {
                await this.changePage(null, null, -(this.currentPage - 1))
            } else {
                this.$apollo.queries.projects.refetch()
            }
        }
    }
}
</script>

<style scoped>
.pointer {
    cursor: pointer
}
</style>
