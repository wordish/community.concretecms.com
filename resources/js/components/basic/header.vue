<template>
    <div class="hosting-header">
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
                        <ul class="breadcrumb" v-if="showBreadcrumbs">
                            <router-link
                                custom
                                v-for="[name, link, partialMatch] in breadcrumb"
                                :key="link"
                                :to="link"
                                v-slot="{ href, route, navigate, isActive, isExactActive }">
                                <li class="breadcrumb-item" :class="{active: isActive}">
                                    <a v-if="partialMatch ? !isActive : !isExactActive" :href="href" @click="navigate">{{name}}</a>
                                    <span v-else>{{name}}</span>
                                </li>
                            </router-link>
                        </ul>
                    </div>
                    <div>
                        <span class="badge badge-danger" v-if="showUser && isAdmin">Admin</span>
                        <span class="badge badge-accent" v-if="showUser">
                            {{ user }}
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
import store from "../../store/store"
import Modal from "./create-project-modal";
import gql from "graphql-tag";
import {auth} from "../../auth/Authentication";

export default {
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
        showBreadcrumbs: {
            type: Boolean,
            default: true,
        },
        showUser: {
            type: Boolean,
            default: true,
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
        user: () => auth.token?.email,
        isAdmin: () => store.getters.isAdmin,
        breadcrumb: function() {
            if (!this.$route.params) {
                return []
            }
            const breadcrumbs = []

            breadcrumbs.push([
                'Hosting Projects',
                '/'
            ])

            const environment = this.$route.params.environment

            let path = '/'
            if (this.$route.params.id) {
                breadcrumbs.push([
                    this.projectName ? this.projectName : 'Project',
                    path = `/${this.$route.params.id}`,
                    !environment
                ])
                path = `/${this.$route.params.id}`
            }

            if (environment) {
                path = `/${this.$route.params.id}/env/${environment}`
                breadcrumbs.push([
                    environment,
                    `${path}/${this.title.toLowerCase()}`,
                ]);
            }

            return breadcrumbs
        }
    },
    components: {
        Modal
    }
}
</script>
<style>
div.ccm-page .hosting-header .nav-tabs > li > a.active:hover {
    color: #017ddd;
    border-bottom:solid 3px #017ddd;
}
</style>