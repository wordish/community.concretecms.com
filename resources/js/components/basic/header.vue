<template>
    <div>
        <div class="row">
            <div class="col">
                <div class="d-flex mb-3 mt-4">
                    <div class="w-100">
                        <h1 class="highlight"><router-link to="/">Hosting <span v-if="title"> - {{ title }}</span></router-link></h1>
                    </div>
                    <slot name="extraContent"></slot>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <ul class="breadcrumb">
                            <router-link
                                custom
                                v-for="[name, link] in breadcrumb"
                                :key="link"
                                :to="link"
                                v-slot="{ href, route, navigate, isActive, isExactActive }">
                                <li class="breadcrumb-item" :class="{active: isActive}">
                                    <a v-if="!isExactActive" :href="href">{{name}}</a>
                                    <span v-else>{{name}}</span>
                                </li>
                            </router-link>
                        </ul>
                    </div>
                    <div>
                        <span class="badge badge-accent">
                            <router-link to="/api-login" class="text-white">{{ user }}</router-link>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <ul class="nav nav-tabs">
            <slot name="nav"></slot>
        </ul>
    </div>
</template>
<script>
import {store} from "../../store/store"
import Modal from "./create-project-modal";
import gql from "graphql-tag";

export default {
    apollo: {
        currentSession: {
            query: gql`
                query currentSession {
                    currentSession {
                        username
                        email
                        id
                        _id
                    }
                }
            `,
            pollInterval: 10000,
            update({currentSession}) {
                if (!currentSession || parseInt(currentSession._id) !== parseInt(store.state.userData.id)) {
                    store.commit('logout')
                }
            }
        }
    },
    props: {
        project: {
            type: {
                id: Number,
                name: String,
            },
            default: null
        },
        title: {
            type: String,
            default: null
        },
        projectName: {
            type: String,
            default: null
        },
        projectLink: {
            type: String,
            default: null
        },
        environmentName: {
            type: String,
            default: null
        },
        environmentLink: {
            type: String,
            default: null
        },
        showButton: {
            default: false,
            type: Boolean
        }
    },
    data: () => ({
        showModal: false
    }),
    computed: {
        user: () => store.state.userData.email,
        breadcrumb: function() {
            if (!this.$route.params) {
                return []
            }
            const breadcrumbs = []

            breadcrumbs.push([
                'Hosting Projects',
                '/'
            ])

            let path = '/'
            if (this.$route.params.id) {
                path = `/${this.$route.params.id}`
            }

            const environment = this.$route.params.environment
            if (environment) {
                breadcrumbs.push([
                    this.projectName ? this.projectName : 'Project',
                    path = `/${this.$route.params.id}`
                ])
                path = `/${this.$route.params.id}/env/${environment}`
            }

            if (this.title) {
                breadcrumbs.push([
                    this.title,
                    `${path}/${this.title.toLowerCase()}`,
                ]);
            }

            return breadcrumbs
        }
    },
    methods: {
        async onCreate(project) {
            store.commit('createProject', project)
        },
        breadcrumbs: function(id, environment, projectName, title) {

        }
    },
    components: {
        Modal
    }
}
</script>
