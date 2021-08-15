<template>
    <div>
        <project-header :project-name="project ? project.name : ''" title="Environments"></project-header>
        <card :loading="$apollo.loading">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>BRANCH</th>
                            <th>LOCATION TYPE</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tr class="ph-item border-0">
                        <td>
                            <div class="ph-row">
                                <div class="ph-col-12"></div>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="project" v-for="env in project.environments">
                        <td><router-link :to="`env/${env.name}`">{{ env.name }}</router-link></td>
                        <td>{{ env.name }}</td>
                        <td>{{ env.name === project.productionBranch ? 'Production' : 'Development' }}</td>
                        <td><a target="_blank" :href="urlFor(env)" v-if="urlFor(env)"><i class="fas fa-link"></i></a></td>
                    </tr>
                </table>
            </div>
        </card>
    </div>
</template>

<script>
import gql from "graphql-tag";
import { Q_PROJECT_FULL } from "../../../queries/project";
import Card from "../../basic/card";
import {expectedEnvironments, hostingProjectId} from "../../../helpers";
import Header from "../../basic/header"
import ProjectHeader from "./project-header";

export default {
    name: "environments",
    components: {ProjectHeader, Header, Card},
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
        urlFor(env) {
            console.log('getting url: ', env)
            if (typeof env['routes'] !== 'object') {
                return ''
            }

            let result = '';
            if (typeof env['routes']['main'] === 'string') {
                result = env['routes']['main']
            }

            if (typeof env['routes']['routes'] === 'object' && typeof env['routes']['routes'][0] === 'string') {
                result = env['routes']['routes'][0]
            }

            return result === 'undefined' ? '' : result;
        }
    },
    computed: {
        expectedEnvironments() {
            return expectedEnvironments(this.project)
        }
    },
    data: () => ({
        selectedProject: null,
        project: {environments:[]}
    })
}
</script>

<style scoped>

</style>
