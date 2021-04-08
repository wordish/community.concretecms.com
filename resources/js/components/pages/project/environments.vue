<template>
    <card :loading="$apollo.loading">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>NAME</th>
                        <th>URL</th>
                        <th>BRANCH</th>
                        <th>LOCATION TYPE</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tr class="ph-item border-0">
                    <td>
                        <div class="ph-row">
                            <div class="ph-col-12"></div>
                        </div>
                    </td>
                </tr>

                <tr v-for="env in expectedEnvironments">
                    <td>{{ env.name }}</td>
                    <td class="w-80"><a :href="env.url" target="_blank">{{ env.url }}</a></td>
                    <td>{{ env.branch }}</td>
                    <td>{{ env.type }}</td>
                    <td>{{ env.status }}</td>
                </tr>
            </table>
        </div>
    </card>
</template>

<script>
import gql from "graphql-tag";
import { Q_PROJECT_FULL } from "../../../queries/project";
import Card from "../../basic/card";
import {expectedEnvironments} from "../../../helpers";

export default {
    name: "environments",
    components: {Card},
    apollo: {
        project: {
            query: Q_PROJECT_FULL,
            variables: function() {
                return {
                    projectId: `projects/${this.$route.params.id}`
                }
            },
        },
    },
    computed: {
        expectedEnvironments() {
            return expectedEnvironments(this.project)
        }
    },
    data: () => ({
        selectedProject: null,
        project: null
    })
}
</script>

<style scoped>

</style>
