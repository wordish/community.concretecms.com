<template>
    <div>

        <div class="d-flex">
            <h1 class="highlight">Hosting</h1>
            <div class="d-flex ms-auto align-items-center"><project-select-list :project="project" :projects="projects"></project-select-list></div>
        </div>

        <project-panel v-if="project.siteType === 'project'"></project-panel>
        <lagoon-project-panel v-if="project.siteType === 'lagoon'"></lagoon-project-panel>

    </div>
</template>
<script>
import ProjectSelectList from "./HostingControlPanel/ProjectSelectList";
import ProjectPanel from "./HostingControlPanel/Project/ProjectPanel";
import LagoonProjectPanel from "./HostingControlPanel/LagoonProject/LagoonProjectPanel";
export default {
    components: {LagoonProjectPanel, ProjectPanel, ProjectSelectList},
    props: {
        project: Object,
        projects: Array
    },
    data: () => ({
    }),
    methods: {

    },
    computed: {
    }
}
</script>

