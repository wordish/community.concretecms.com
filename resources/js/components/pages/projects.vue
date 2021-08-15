<template>
    <div>
        <Header>
            <template v-slot:extraContent>
                <div class="d-flex flex-column justify-content-center">
                    <button @click="showModal=true" class="btn btn-primary text-nowrap">
                        New Hosting Project
                    </button>
                </div>
            </template>
        </Header>
        <card :loading="$apollo.loading">
            <div class="card-body">
                <table class="table" v-if="projects">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th class="text-center">Handle</th>
                    </tr>
                    </thead>
                    <tr v-for="{node} in projects.edges">
                        <td><router-link :to="node._id + ''">{{ node.name }}</router-link></td>
                        <td>{{ node.startingPoint.name }}</td>
                        <td class="text-center">
                            <span class="badge badge-light-gray border pointer" @click="$copyText(node.lagoonName)">{{node.lagoonName}} <i class="ml-2 far fa-copy"></i></span>
                        </td>
                    </tr>
                </table>
                <div class="" v-else>
                    <h4 class="text-center text-muted">You don't have any projects!</h4>
                </div>
            </div>
        </card>
        <pagination @next="nextPage" @previous="previousPage" :current="this.currentPage" :total="this.projects.totalCount" :page-size="this.count"></pagination>
        <create-project-modal v-model="showModal" @create="handleProjectCreate"></create-project-modal>
    </div>
</template>

<script>
import Card from "../basic/card";
import {store} from "../../store/store";
import {Q_PROJECT_LIST, Q_PROJECT_LIST_LIGHT} from "../../queries/project";
import Header from "../basic/header";
import CreateProjectModal from "../basic/create-project-modal";
import Pagination from "../basic/pagination";

export default {
    name: "projects",
    components: {Pagination, CreateProjectModal, Header, Card},
    apollo: {
        projects: {
            query: Q_PROJECT_LIST_LIGHT,
            variables() {
                return {
                    before: null,
                    after: null,
                    perPage: this.count
                }
            }
        }
    },
    data: () => ({
        projects: [],
        showModal: false,
        extraProject: null,
        currentPage: 1,
        count: 15,
        totalCount: 100,
    }),
    watch: {
        projects(newProjects, oldProjects) {
            if (this.extraProject) {
                for (const {node} of newProjects.edges) {
                    if (node.id === this.extraProject.id) {
                        this.extraProject = null
                        break
                    }
                }
            }
        },
        async pendingProject(newProject, oldProject) {
            this.extraProject = newProject
            await this.$apollo.queries.projects.refetch()
        }
    },
    computed: {
        pendingProject() {
            return store.state.addedProject
        },
    },
    methods: {
        async changePage(after, before, difference) {
            const self = this
            await this.$apollo.queries.projects.fetchMore({
                variables: {
                    perPage: self.count,
                    after: after,
                    before: before,
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
            await this.changePage(this.projects.pageInfo.endCursor, null,1);
        },
        async previousPage() {
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
