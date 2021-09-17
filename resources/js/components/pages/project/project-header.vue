<template>
    <Header :project-name="projectName" :title="title">
        <template v-slot:nav>
            <li class="nav-item">
                <router-link :to="`/${$route.params.id}/environments`" class="nav-link" active-class="active">Environments</router-link>
            </li>
            <li class="nav-item">
                <router-link :to="`/${$route.params.id}/code`" class="nav-link" active-class="active">Code</router-link>
            </li>
            <li class="nav-item" v-if="isAdmin">
                <router-link :to="`/${$route.params.id}/permissions`" class="nav-link" active-class="active">Settings</router-link>
            </li>
        </template>
    </Header>
</template>
<script>
import Header from "../../basic/header";
import store from "../../../store/store";

export default {
    components: {Header},
    computed: {
        isAdmin: () => store.getters.isAdmin,
    },
    props: {
        projectName: String,
        title: String
    }
}
</script>
