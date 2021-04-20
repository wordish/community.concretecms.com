<template>
    <div>
        <card :loading="$apollo.loading">
            <div class="card-body">

                <table class="table">
                    <thead>
                    <tr>
                        <th>NAME</th>
                        <th>Type</th>
                    </tr>
                    </thead>

                    <tr v-if="extraProject">
                        <td><router-link :to="extraProject.id">{{ extraProject.name }}</router-link></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr v-for="{node} in projects.edges">
                        <td><router-link :to='node.id'>{{ node.name }}</router-link></td>
                        <td>{{ node.projectType }}</td>
                    </tr>
                </table>
            </div>
        </card>

        <div v-if="this.projects && this.projects.pageInfo" class="ccm-search-results-pagination">
            <div class="d-flex justify-content-center w-100">
                <div>
                    <ul class="pagination">
                        <li class="page-item" :class="{disabled: !this.projects.pageInfo.hasPreviousPage}">
                            <a @click="previousPage" class="page-link" href="#">Previous</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link">{{ currentPage }} <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="page-item" :class="{disabled: !this.projects.pageInfo.hasNextPage}">
                            <a @click="nextPage" class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import gql from 'graphql-tag'
import Card from "../basic/card";
import {store} from "../../store/store";

const QUERY = gql`
query($after: String, $before: String, $perPage: Int!) {
    projects: projects(after: $after, before: $before, first: $perPage) {
        totalCount
        edges {
            cursor
            node {
                name
                id
                projectType
                dateCreated
                dateUpdated
            }
        }
        pageInfo {
            hasNextPage
            hasPreviousPage
            endCursor
            startCursor
        }
    }
}
`;

export default {
    name: "projects",
    components: {Card},
    apollo: {
        projects: {
            query: QUERY,
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
        extraProject: null,
        currentPage: 1,
        count: 20
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
        }
    }
}
</script>

<style scoped>

</style>
