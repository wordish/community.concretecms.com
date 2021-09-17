<template>
    <div class="container">
        <router-view></router-view>
        <div class="toasts" name="toast">
            <transition-group tag="div" name="toast" class="d-flex flex-column">
                <div
                    class="mb-1 align-self-end"
                    v-for="toast of toasts"
                    :key="toast.id">
                    <toast
                        :title="toast.title"
                        :message="toast.message"
                        :type="toast.type"
                        :can-dismiss="toast.canDismiss"
                        @close="() => dismissToast(toast.id)"
                    ></toast>
                </div>

            </transition-group>
        </div>
    </div>
</template>

<script>
import store from "../store/store"
import {Q_PROJECT_FULL, Q_PROJECT_LIST} from "../queries/project";
import {hostingProjectId} from "../helpers";
import Toast from "./basic/toast";
import gql from "graphql-tag";

export default {
    components: {Toast},
    computed: {
        toasts: () => store.state.toasts
    },
    methods: {
        dismissToast(toastId) {
            store.commit('hideToast', toastId)
        }
    }
}
</script>
<style scoped>
    .toasts {
        position: fixed;
        bottom: 1rem;
        right: 1rem;
    }
    .toasts > div > .toast {
        width: auto;
    }
    .toast {
        opacity: 1;
    }

    .toast-move {
        transition: transform .125s;
    }

    .toast-enter-active {
        transition: opacity .5s;
    }

    .toast-leave-active {
        transition: opacity .5s, height .5s;
    }

    .toast-enter {
        opacity: 0 !important;
    }
    .toast-leave {
        opacity: 1;
        overflow: hidden;
        height: 100%;
    }
    .toast-leave-to {
        opacity: 0;
        overflow: hidden;
        height: 0;
    }

 </style>
