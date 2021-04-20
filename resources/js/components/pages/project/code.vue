<template>
    <card :loading="$apollo.loading">
        <div class="card-body">
            <div class="mb-5">
                <h4>LOCAL EDITING</h4>
                <p>Edit on your local machine by cloning our git repository</p>
                <div class="row">
                    <strong class="col-sm-2">Git Repository</strong>
                    <span class="px-4">{{ project.gitUrl }}</span>
                    <div><button class="btn btn-sm" @click="notImplemented">Clone</button></div>
                </div>
                <div class="row">
                    <strong class="col-sm-2">Site Type</strong>
                    <span class="px-4">{{ project.startingPoint.name }}</span>
                </div>
            </div>
            <div>
                <h4>CODE EDITOR</h4>
                <p>Edit live with our code editor</p>
                <button class="btn btn-sm" @click="notImplemented">Launch Editor</button>
            </div>
        </div>
    </card>
</template>

<script>
import Card from "../../basic/card";
import {Q_PROJECT_FULL} from "../../../queries/project";
export default {
    name: "code",
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
    methods: {
        notImplemented() {
            alert('Not implemented.')
        }
    }
}
</script>

<style scoped>

</style>
