<template>
    <div v-if="visible">
        <div class="modal show d-block mt-5" tabindex="-1" role="dialog">
            <div class="modal-dialog shadow" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create a Hosting Project</h5>
                        <button :disabled="creating" @click="close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="siteName">Project Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="siteName" v-model="name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="exampleFormControlSelect1">Site Type</label>
                            <div class="col-sm-9">
                                <select v-model="startingPoint" class="form-control" id="exampleFormControlSelect1">
                                    <option v-for="point of startingPoints" :name="point.name" :value="point.id">
                                        {{ point.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button :disabled="creating" type="button" class="btn btn-primary" @click="createProject">Create Project</button>
                        <button :disabled="creating" type="button" class="btn btn-secondary" @click="close">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="background"></div>
    </div>
</template>

<script>
import {Q_STARTING_POINTS_FULL} from "../../queries/starting-point";
import {M_PROJECT_CREATE} from "../../queries/project";
import store from "../../store/store";
import {auth} from "../../auth/Authentication";

export default {
    name: "create-project-modal",
    model: {
        prop: 'visible',
        event: 'change',
    },
    apollo: {
        startingPoints: {
            query: Q_STARTING_POINTS_FULL,
            result(result) {
                if (!this.startingPoint && result.data?.startingPoints?.length) {
                    this.defaultStartingPoint = result.data.startingPoints[0].id
                    this.startingPoint = this.defaultStartingPoint
                }
            }
        }
    },
    data() {
        return {
            startingPoints: [],
            startingPoint: null,
            defaultStartingPoint: null,
            name: '',
            creating: false
        }
    },
    props: {
        visible: Boolean
    },
    methods: {
        close() {
            this.name = ''
            this.startingPoint = this.defaultStartingPoint
            this.creating = false
            this.$emit('change', false)
        },
        async createProject() {
            this.creating = true

            const result = await this.$apollo.mutate({
                mutation: M_PROJECT_CREATE,
                variables: {
                    name: this.name,
                    startingPoint: this.startingPoint,
                    adminIds: [auth.token.id],
                }
            })

            if (result.data.createProject && result.data.createProject.project && result.data.createProject.project.id) {
                this.$emit('create', result.data.createProject.project)
            } else {
                alert('Unable to create project.')
            }

            this.close()
        }
    }
}
</script>

<style scoped>
.background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,.5);
    z-index: 100;
}
</style>
