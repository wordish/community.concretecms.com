<template>
    <div>
        <card :loading="$apollo.loading">
            <div class="card-body">

                <table class="table">
                    <thead>
                    <tr>
                        <th>NAME</th>
                        <th>Type</th>
                    </tr>
                    </thead>

                    <tr v-for="{node} in projects.edges">
                        <td><router-link :to='node.id'>{{ node.name }}</router-link></td>
                        <td>Concrete Hosting</td>
                    </tr>
                </table>
            </div>
        </card>

        <div class="ccm-search-results-pagination">

        </div>
    </div>
</template>

<script>
import gql from 'graphql-tag'
import Card from "../basic/card";

export default {
    name: "projects",
    components: {Card},
    apollo: {
        projects: gql`
           query {
               projects {
                   edges {
                       node {
                           name
                           id
                           gitUrl
                           productionBranch
                           stageBranches
                           dateCreated
                           dateUpdated
                       }
                   }
               }
           }
        `
    },
    data: () => ({
        projects: [{name: 'foo'}]
    })
}
</script>

<style scoped>

</style>
