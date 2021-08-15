<template>
    <div>
        <project-header :project-name="project ? project.name : ''" title="Permissions"></project-header>
        <card :loading="$apollo.loading || loading">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>PERMISSIONS</h4>
                    <button v-if="project" class="btn btn-primary btn-sm" @click="addUser">Add Admin</button>
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
                    <tr v-for="id in project.authorizedAdmins">
                        <td class="pl-3">{{id}} <span class="badge badge-accent" v-if="userId == id">You</span></td>
                        <td>Admin</td>
                        <td class="text-right pr-3">
                            <button v-if="userId != id" @click="removeUser(id)" class="btn btn-tiny btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </card>
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

export default {
    name: "code-page",
    components: {ProjectHeader, Card, Header},
    data: () => ({
        project: {
            startingPoint: {}
        },
        loading: false,
    }),
    computed: {
        userId: () => store.state.userData.id,
        admins() {
            return this.project.authorizedAdmins.map((id) => parseInt(id))
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

        },
    },
    methods: {
        async removeUser(id) {
            if (!confirm('Are you sure you want to remove this user\'s access?')) {
                return
            }

            const userId = parseInt(id)
            let admins = this.admins

            if (this.project.userId <= 0 || admins.indexOf(userId) === -1) {
                alert('Invalid user ID.');
                return
            }

            admins.splice(admins.indexOf(userId), 1);

            this.loading = true
            await this.setAuthorizedAdmins(admins)
            this.loading = false
        },
        async addUser() {
            const userId = parseInt(prompt('Which user ID would you like to add?'))

            if (this.project.userId <= 0) {
                alert('Invalid user ID.');
                return
            }

            if (this.project.authorizedAdmins.indexOf(userId) !== -1) {
                alert('This user ID already has access.')
                return
            }
            if (userId > 0) {
                const authorizedAdmins = [
                    ...this.project.authorizedAdmins,
                    userId
                ]

                this.loading = true
                await this.setAuthorizedAdmins(authorizedAdmins)
                this.loading = false
            }
        },
        setAuthorizedAdmins(authorizedAdmins) {
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
    }
}
</script>

<style scoped>
.btn-tiny {
    padding: .25rem !important;
    font-size: .5rem;
}
</style>
