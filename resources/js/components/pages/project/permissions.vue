<template>
    <div>
        <project-header :project-name="project ? project.name : ''" title="Permissions"></project-header>
        <card :loading="loading > 0" :access-denied="accessDenied">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>PERMISSIONS</h4>
                    <button v-if="project" class="btn btn-primary btn-sm" @click="addUserModalOpen = true">Add Admin</button>
                </div>
                <p class="help-block">This is a stop-gap implementation of permissions. Check back later for more robust tools.</p>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Access Level</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="project">
                        <tr v-for="id in project.authorizedAdmins">
                            <td class="pl-3">{{id}} <span class="badge badge-accent" v-if="userId == id">You</span></td>
                            <td>Admin</td>
                            <td class="text-right pr-3">
                                <button v-if="userId != id" @click="deleteUserModalUser = id" class="btn btn-tiny btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr v-for="i in Array(5).keys()">
                            <td class="pl-3">
                                <blink-box></blink-box>
                            </td>
                            <td>
                                <blink-box></blink-box>
                            </td>
                            <td class="text-right pr-3">
                                <blink-box></blink-box>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
        </card>
        <template v-if="project">
            <modal v-model="addUserModalOpen" :loading="addingUser">
                <template v-slot:title="{close}">
                    Add user
                </template>
                <template v-slot:body="{state}">
                    <div ref="form">
                        <ul class="help-block d-block">
                            <li>Navigate to the <a href="https://community.concretecms.com/members/directory" target="_blank">Community User Search</a> page</li>
                            <li>Find the user you'd like to grant access and open their profile</li>
                            <li>Grab the user ID from the profile URL:</li>
                            <li style="list-style-type: none"><code><span class="text-muted">https://.../members/profile/</span><strong>8675309</strong></code></li>
                        </ul>
                        <label for="userid">User ID</label>
                        <input class="form-control" id="userid" placeholder="8675309" type=number min="0" v-model="state.user" />
                    </div>
                </template>
                <template v-slot:footer="{state, close}">
                    <button :disabled="addingUser || !hasValidUser(state.user)" type="button" class="btn btn-primary btn-sm align-self-start" @click="addUser(state.user, close)">Add Hosting Project Admin</button>
                    <button :disabled="addingUser" type="button" class="btn btn-secondary btn-sm" @click="close">Cancel</button>
                </template>
            </modal>
            <modal danger v-model="deleteUserModalUser" :loading="deletingUser">
                <template v-slot:title="{close}">
                    Remove user
                </template>
                <template v-slot:body="{state}">
                    <div ref="form">
                        <p>Are you sure you want to remove access for user <code>{{deleteUserModalUser}}</code>?</p>
                    </div>
                </template>
                <template v-slot:footer="{state, close}">
                    <button :disabled="addingUser || !hasValidUser(deleteUserModalUser)" type="button" class="btn btn-danger btn-sm align-self-start" @click="removeUser(deleteUserModalUser, close)">Remove Access</button>
                    <button :disabled="addingUser" type="button" class="btn btn-secondary btn-sm" @click="close">Cancel</button>
                </template>
            </modal>
            <modal danger v-model="terminateModalOpen">
                <template v-slot:title="{close}">
                    Terminate Project
                </template>
                <template v-slot:body="{state}">
                    <p>
                        Are you sure you want to delete <code>{{project.name}}</code>? This cannot be undone!
                    </p>
                    <p>
                        If you are sure, type the project name in the box below.
                    </p>
                    <input class="form-control" :class="{'is-invalid': project.name !== state.projectName}" v-model="state.projectName" />
                </template>
                <template v-slot:footer="{state, close}">
                    <button :disabled="project.name !== state.projectName" type="button" class="btn btn-danger btn-sm align-self-start">Terminate Project</button>
                    <button type="button" class="btn btn-secondary btn-sm" @click="close">Cancel</button>
                </template>
            </modal>
        </template>
    </div>
</template>

<script>
import Header from "../../basic/header";
import Card from "../../basic/card";
import {F_PROJECT_FULL, Q_PROJECT_FULL} from "../../../queries/project";
import {hostingProjectId} from "../../../helpers";
import ProjectHeader from "./project-header";
import gql from "graphql-tag";
import {store} from "../../../store/store";
import CreateProjectModal from "../../basic/create-project-modal";
import Modal from "../../basic/modal";
import BlinkBox from "../../basic/blink-box";

export default {
    components: {BlinkBox, Modal, CreateProjectModal, ProjectHeader, Card, Header},
    data: () => ({
        project: null,
        loading: 0,
        terminateModalOpen: false,
        addUserModalOpen: false,
        addingUser: false,
        deletingUser: false,
        deleteUserModalUser: null,
        accessDenied: false,
    }),
    computed: {
        userId: () => store.state.userData.id,
        admins() {
            return this.project ? this.project.authorizedAdmins.map((id) => parseInt(id)) : []
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
            loadingKey: "loading",
            fetchPolicy: "cache-and-network",
            error(error) {
                this.accessDenied = error.gqlError.message === 'Access Denied.'
            }
        },
    },
    methods: {
        hasValidUser(id) {
            return id && Math.min(Math.max(parseInt(id), 0), 999_999_999).toString() === id
        },
        async removeUser(id, close) {
            if (!store.getters.isAdmin) {
                return await this.$router.replace('/')
            }
            this.deletingUser = true

            const userId = parseInt(id)
            let admins = this.admins

            if (this.project.userId <= 0 || admins.indexOf(userId) === -1) {
                alert('Invalid user ID.');
                this.deletingUser = false
                return
            }

            admins.splice(admins.indexOf(userId), 1);

            await this.setAuthorizedAdmins(admins)
            this.deletingUser = false
            close()
        },
        async addUser(userId, close) {
            if (!store.getters.isAdmin) {
                return await this.$router.replace('/')
            }
            this.addingUser = true
            if (this.project.userId <= 0) {
                alert('Invalid user ID.');
                this.addingUser = false;
                return
            }

            if (this.project.authorizedAdmins.indexOf(userId) !== -1) {
                alert('This user ID already has access.')
                this.addingUser = false;
                return
            }
            if (userId > 0) {
                const authorizedAdmins = [
                    ...this.project.authorizedAdmins,
                    userId
                ]

                await this.setAuthorizedAdmins(authorizedAdmins)
                this.addingUser = false;
                close()
            }
        },
        setAuthorizedAdmins(authorizedAdmins) {
            if (!store.getters.isAdmin) {
                return this.$router.replace('/')
            }
            return this.$apollo.mutate({
                mutation: gql`
                    ${F_PROJECT_FULL}
                    mutation addUser($projectId: ID!) {
                        updateHostingProject(input: {
                            id: $projectId
                            authorizedAdmins: [${authorizedAdmins.join(',')}],
                        }) {
                           project: hostingProject {
                               ...HostingProjectFields
                           }
                        }
                    }
                    `,
                variables: {
                    projectId: hostingProjectId(this.project.id),
                },
            })
        }
    },
    mounted() {
        if (!store.getters.isAdmin) {
            this.$router.replace('/')
        }
    }
}
</script>

<style scoped>
.btn-tiny {
    padding: .25rem !important;
    font-size: .5rem;
}
</style>
