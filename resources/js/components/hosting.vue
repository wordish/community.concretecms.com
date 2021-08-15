<template>
    <div class="container">
        <router-view></router-view>
    </div>
</template>

<script>
import {store} from "../store/store"
import {Q_PROJECT_FULL, Q_PROJECT_LIST} from "../queries/project";
import {hostingProjectId} from "../helpers";

export default {
    computed: {
        isLoggedIn() {
            return store.getters.isLoggedIn
        },
        user() {
            return store.state.userData?.username
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
        },
        title() {
            return this.project ? this.project.name : 'Hosting'
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
        project: null,
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
