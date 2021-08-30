<template>
    <div class="input-group">
        <select :disabled="loading !== 0" @change="$emit('change', $event.target.value === '--select' ? null : $event.target.value)"
                :class="selectClass" class="custom-select">
            <template>
                <option :value="null">Select a Branch</option>
                <optgroup label="Branches">
                <option
                    v-for="branch in branches"
                    :key="branch.name"
                    :value="branch.name"
                    :disabled="disableExisting && branch.hasEnvironment"
                >
                    {{ branch.name }}
                </option>
                </optgroup>
            </template>
        </select>
        <div class="input-group-append">
            <div class="input-group-text" style="width:2.5rem" :class="{'bg-white': loading === 0}">
                <i :class="spinnerClass" v-if="loading !== 0"></i>
                <a v-else class="mr-1" href="#" @click.prevent.stop="$apollo.queries.branches.refetch()" title="Refresh Branches">
                    <i class="fas fa-spinner fa-sync"></i>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
import {Q_PROJECT_BRANCHES} from "../../queries/project";
import {hostingProjectId} from "../../helpers";

export default {
    name: "BranchSelector",
    model: {
        prop: "branch",
        event: "change",
    },
    apollo: {
        branches: {
            query: Q_PROJECT_BRANCHES,
            variables: function () {
                return {
                    projectId: hostingProjectId(this.projectId)
                }
            },
            update: data => data.hostingProject.branches,
            loadingKey: 'loading',
        },
    },
    data: () => ({
        loading: 0,
        branches: [],
    }),
    props: {
        branch: String,
        selectClass: {
            type: String,
            default: "form-select"
        },
        spinnerClass: {
            type: String,
            default: "fas fa-spinner fa-spin"
        },
        projectId: {
            type: String,
            required: true,
        },
        disableExisting: {
            type: Boolean,
            default: true,
        }
    },
    mounted() {
        this.$apollo.queries.branches.refresh()
    }
}
</script>
