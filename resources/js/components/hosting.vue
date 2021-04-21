<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="d-flex">
                    <h1 class="highlight mb-3 w-100"><span @click="goHome">Hosting</span></h1>
                    <div class="d-flex flex-column justify-content-center">
                        <select v-if="activeProject" v-model="selectedProject">
                            <option :value="node._id" :key="node.id" v-for="{node} in projects.edges">{{ node.name }}</option>
                        </select>
                        <button v-else-if="isLoggedIn" @click="showModal=true" class="btn btn-primary text-nowrap">
                            New Project
                        </button>
                    </div>
                </div>
                <div v-if="this.$route.params.id && selectedProject">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <router-link :to="`/hosting_projects/${selectedProject}/environments`" class="nav-link" exact-active-class="active">Environments</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="`/hosting_projects/${selectedProject}/code`" class="nav-link" active-class="active">Code</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="`/hosting_projects/${selectedProject}/deployments`" class="nav-link" active-class="active">Deployments</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="`/hosting_projects/${selectedProject}/backups`" class="nav-link" active-class="active">Backups</router-link>
                        </li>
                    </ul>
                    <hr class="mt-0">
                </div>
            </div>
        </div>
        <router-view></router-view>
        <modal v-model="showModal" @create="onCreate"></modal>
    </div>
</template>

<script>
import gql from "graphql-tag"
import {store} from "../store/store"
import Modal from "./basic/create-project-modal";

export default {
    components: {Modal},
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
        isLoggedIn() {
            return store.getters.isLoggedIn
        },
        selectedProject: {
            get () {
                return Number(store.state.selectedProject)
            },
            set (value) {
                store.commit('selectProject', value)
                if (value && this.$route.params.id !== value) {
                    this.$router.push(`/projects/${value}/`)
                }
            }
        },
        activeProject() {
            if (!this.$route) {
                return null
            }
            return parseInt(this.$route.params.id) > 0
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
        showModal: false,
        projects: [],
        pendingProject: null,
    }),
    methods: {
        goHome() {
            if (this.$route.fullPath !== '/') {
                this.$router.replace('/')
            }
        },
        async onCreate(project) {
            this.projects.edges = [
                { node: project },
                ...this.projects.edges
            ]
            store.commit('createProject', project)
        }
    }
}
</script>
