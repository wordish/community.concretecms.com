<template>
    <div>
        <form class="form-inline">
            <label class="mr-2" for="selectProjects">Projects</label>
            <select class="custom-select mr-sm-2" id="selectProjects" v-model="selectedProject" @change="changeProject">
                <option v-for="projectEntry in projects" :value="projectEntry">{{projectEntry.name}}</option>
            </select>
        </form>
    </div>
</template>
<script>
export default {
    props: {
        project: Object,
        projects: Array
    },
    data: function () {
        return {
            selectedProject: this.project
        }
    },
    methods: {
        changeProject() {
            window.location.href = CCM_DISPATCHER_FILENAME + '/account/projects/panel/' + this.selectedProject.id
        }
    },
    computed: {
    }
}
</script>

