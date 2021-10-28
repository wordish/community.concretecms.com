<template>
    <component :is="tag" :class="['badge', ...badgeClasses]">
        <slot name="content" v-bind="{statusText}">
            {{statusText}}
        </slot>
    </component>
</template>
<script>
export default {
    props: {
        tag: {
            default: "span",
            type: String,
            description: "The tag to use for the wrapper",
        },
        status: {
            type: String,
            required: true,
            description: "A fulfillment status provided by the API"
        },
        statusMap: {
            type: Object,
            required: false,
            description: "The statustext to use for given statuses"
        },
        classMap: {
            type: Object,
            required: false,
            description: "The classes to use for given statuses"
        }
    },
    computed: {
        badgeClasses() {
            const map = {
                unfulfilled: 'badge-info',
                cancelled: 'badge-gray',
                failed: 'badge-danger',
                started: 'badge-info blink',
                fulfilled: 'badge-success',
                pending_termination: 'badge-info',
                terminating: 'badge-warning blink',
                termination_failed: 'badge-danger',
                terminated: 'badge-gray',
                ...this.classMap
            }

            return map[this.status] ? map[this.status] : ''
        },
        statusText() {
            const map = {
                unfulfilled: 'Pending',
                cancelled: 'Cancelled',
                failed: 'Failed',
                started: 'Running',
                fulfilled: 'Succeeded',
                pending_termination: 'Preparing for Termination',
                terminating: 'Terminating',
                termination_failed: 'Failed to Terminate.',
                terminated: 'Terminated',
                ...this.statusMap
            }

            return map[this.status] ? map[this.status] : 'UNKNOWN STATUS'
        }
    }
}
</script>
<style scoped>

@keyframes waggle {
    0% {
        transform: rotate(-2deg);
    }
    50% {
        transform: rotate(2deg);
    }
    100% {
        transform: rotate(-2deg);
    }
}
@keyframes blink {
    0% {
        opacity: 100%;
    }
    50% {
        opacity: 50%;
    }
    100% {
        opacity: 100%;
    }
}

.blink {
    animation: blink .75s infinite;
}
</style>
