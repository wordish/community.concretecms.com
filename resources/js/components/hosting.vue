<template>
    <div class="container">
        <div class="row">
            <div class="col">

                <div class="d-flex">
                    <h1 class="highlight mb-3 w-100"><span @click="goHome">Hosting</span></h1>
                    <div v-if="this.$route.params.id && projects && projects.edges && projects.edges.length" class="d-flex flex-column justify-content-center">
                        <select v-model="selectedProject">
                            <option :value="node._id" :key="node.id" v-for="{node} in projects.edges">{{ node.name }}</option>
                        </select>
                    </div>
                </div>
                <div v-if="this.$route.params.id && selectedProject">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <router-link :to="`/projects/${selectedProject}/environments`" class="nav-link" exact-active-class="active">Environments</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="`/projects/${selectedProject}/code`" class="nav-link" active-class="active">Code</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="`/projects/${selectedProject}/deployments`" class="nav-link" active-class="active">Deployments</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="`/projects/${selectedProject}/backups`" class="nav-link" active-class="active">Backups</router-link>
                        </li>
                    </ul>
                    <hr class="mt-0">
                </div>
            </div>
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
import gql from "graphql-tag"
import {store} from "../store/store"

export default {
    apollo: {
        projects: {
            query: gql`
                query {
                    projects {
                        edges {
                            node {
                                name
                                id
                                _id
                            }
                        }
                    }
                }
            `
        }
    },
    computed: {
        selectedProject: {
            get () {
                return Number(store.state.selectedProject)
            },
            set (value) {
                store.commit('selectProject', value)
            }
        }
    },
    watch: {
        $route(to, from) {
            if (to.params.id && to.params.id !== this.selectedProject) {
                this.selectedProject = to.params.id
            }

            if (!to.params.id) {
                this.selectedProject = null
            }
        }
    },
    mounted() {
        if (!this.$route.params.id && this.selectedProject) {
            store.commit('selectProject', '')
        } else if (this.$route.params.id !== this.selectedProject) {
            store.commit('selectProject', this.$route.params.id)
        }
    },
    data: () => ({
        projects: [],
    }),
    methods: {
        goHome() {
            this.$router.replace('/')
        }
    }
}
</script>
