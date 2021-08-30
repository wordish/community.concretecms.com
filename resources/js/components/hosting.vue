<template>
    <div class="container">
        <router-view></router-view>
        <div class="toasts" name="toast">
            <transition-group tag="div" name="toast" class="d-flex flex-column">
                <div
                    class="mb-1 align-self-end"
                    v-for="toast of toasts"
                    :key="toast.id">
                    <toast
                        :title="toast.title"
                        :message="toast.message"
                        :type="toast.type"
                        :dismissable="toast.canDismiss"
                        @close="() => dismissToast(toast.id)"
                    ></toast>
                </div>

            </transition-group>
        </div>
    </div>
</template>

<script>
import {store} from "../store/store"
import {Q_PROJECT_FULL, Q_PROJECT_LIST} from "../queries/project";
import {hostingProjectId} from "../helpers";
import Toast from "./basic/toast";

export default {
    components: {Toast},
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
        },
        toasts() {
            return store.state.toasts
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
        },
        dismissToast(toastId) {
            store.commit('hideToast', toastId)
        }
    }
}
</script>
<style scoped>
    .toasts {
        position: fixed;
        bottom: 1rem;
        right: 1rem;
    }
    .toasts > div > .toast {
        width: auto;
    }
    .toast {
        opacity: 1;
    }

    .toast-move {
        transition: transform .125s;
    }

    .toast-enter-active {
        transition: opacity .5s;
    }

    .toast-leave-active {
        transition: opacity .5s, height .5s;
    }

    .toast-enter {
        opacity: 0 !important;
    }
    .toast-leave {
        opacity: 1;
        overflow: hidden;
        height: 100%;
    }
    .toast-leave-to {
        opacity: 0;
        overflow: hidden;
        height: 0;
    }

 </style>
