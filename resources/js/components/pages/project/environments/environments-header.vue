<template>
    <Header
        :project-name="projectName"
        :project-link="`/${$route.params.id}/`"
        :environment-name="`${$route.params.environment}`"
        :environment-link="`/${$route.params.id}/env/${$route.params.environment}`"
        :title="title"
    >
        <template v-slot:nav>
            <li class="nav-item">
                <router-link :to="`/${$route.params.id}/env/${$route.params.environment}/deploys`" class="nav-link" active-class="active">Deploys</router-link>
            </li>
            <li class="nav-item">
                <router-link :to="`/${$route.params.id}/env/${$route.params.environment}/backups`" class="nav-link" active-class="active">Backups</router-link>
            </li>
            <li class="nav-item">
                <router-link :to="`/${$route.params.id}/env/${$route.params.environment}/installs`" class="nav-link" active-class="active">Installs</router-link>
            </li>
        </template>
    </Header></template>
<script>
import Header from "../../../basic/header";
export default {
    components: {Header},
    props:{
        projectName: String,
        title: String
    }
}
</script>
