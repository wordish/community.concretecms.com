<template>
    <div>
        <project-header :project-name="project ? project.name : ''" title="Code"></project-header>
        <card :loading="$apollo.loading" :access-denied="accessDenied">
            <div class="card-body">
                <div class="mb-5">
                    <h4>Local Editing</h4>
                    <p>Edit on your local machine by cloning our git repository</p>
                    <div class="row">
                        <strong class="col-sm-2">Git Repository</strong>
                        <span class="px-4" v-if='project'>
                            <a :href="`https://git.concretecms.com/${project.gitPath}`" target="_blank">
                                {{ project.gitUrl }}
                            </a>
                        </span>
                        <blink-box v-else></blink-box>
                    </div>
                    <div class="row">
                        <strong class="col-sm-2">Site Type</strong>
                        <span v-if="project" class="px-4">{{ project.startingPoint.name }}</span>
                        <blink-box v-else></blink-box>
                    </div>
                </div>
                <div v-if="project">
                    <h4>Code Editor</h4>
                    <p>Edit live with our code editor</p>
                    <state :initial="{visible: false, branch: null}">
                        <template v-slot="{state, setState, reset}">
                            <button class="btn btn-sm" @click.prevent="setState({visible: true})">
                                Launch Editor
                            </button>
                            <modal v-model="state.visible">
                                <template v-slot:title>
                                    Choose a branch to edit
                                </template>
                                <template v-slot:body>
                                    <branch-selector :project-id="project.id" :disable-existing="false" v-model="state.branch"></branch-selector>
                                </template>
                                <template v-slot:footer="{close}">
                                    <a
                                        v-if="state.branch"
                                        :href="`https://git.concretecms.com/-/ide/project/${project.gitPath}/edit/${state.branch}/-/src/`"
                                        target="_blank"
                                        class="btn btn-sm btn-primary"
                                        @click="close">
                                        Open Editor
                                    </a>
                                    <button class="btn btn-sm btn-primary" v-else disabled="true">
                                        Open Editor
                                    </button>
                                </template>
                            </modal>
                        </template>
                    </state>
                </div>
            </div>
        </card>
    </div>
</template>

<script>
import Header from "../../basic/header";
import Card from "../../basic/card";
import {Q_PROJECT_FULL} from "../../../queries/project";
import {hostingProjectId, validateSession, validateSessionUpdate} from "../../../helpers";
import ProjectHeader from "./project-header";
import State from "../../powerplug/state";
import Modal from "../../basic/modal";
import BranchSelector from "../../basic/branch-selector";
import BlinkBox from "../../basic/blink-box";
import {Q_PROJECT_SESSION} from "../../../graphql/project";
export default {
    components: {BlinkBox, BranchSelector, Modal, State, ProjectHeader, Card, Header},
    data: () => ({
        project: null,
        accessDenied: false,
    }),
    apollo: {
        project: {
            query: Q_PROJECT_SESSION,
            variables: function() {
                return {
                    projectId: hostingProjectId(this.$route.params.id)
                }
            },
            update: validateSessionUpdate('project'),
            error(error) {
                this.accessDenied = error.gqlError.message === 'Access Denied.'
            },
            fetchPolicy: "cache-and-network"
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
