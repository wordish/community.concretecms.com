<template>
    <div>
        <project-header :project-name="project ? project.name : ''" title="Code"></project-header>
        <card :loading="$apollo.loading">
            <div class="card-body">
                <div class="mb-5">
                    <h4>LOCAL EDITING</h4>
                    <p>Edit on your local machine by cloning our git repository</p>
                    <div class="row">
                        <strong class="col-sm-2">Git Repository</strong>
                        <span class="px-4">
                            <a :href="`https://git.concretecms.com/concretehosting/${project.lagoonName}`" target="_blank">
                                {{ project.gitUrl }}
                            </a>
                        </span>
                    </div>
                    <div class="row">
                        <strong class="col-sm-2">Site Type</strong>
                        <span class="px-4">{{ project.startingPoint.name }}</span>
                    </div>
                </div>
                <div>
                    <h4>CODE EDITOR</h4>
                    <p>Edit live with our code editor</p>
                    <a :href="`https://git.concretecms.com/-/ide/project/concretehosting/${project.lagoonName}/edit/main/-/`" class="btn btn-sm" target="_blank">
                        Launch Editor
                    </a>
                </div>
            </div>
        </card>
    </div>
</template>

<script>
import Header from "../../basic/header";
import Card from "../../basic/card";
import {Q_PROJECT_FULL} from "../../../queries/project";
import {hostingProjectId} from "../../../helpers";
import ProjectHeader from "./project-header";
export default {
    name: "code-page",
    components: {ProjectHeader, Card, Header},
    data: () => ({
        project: {
            startingPoint: {}
        }
    }),
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
        notImplemented() {
            alert('Not implemented.')
        }
    }
}
</script>

<style scoped>

</style>
