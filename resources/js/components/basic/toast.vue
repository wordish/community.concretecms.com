<template>
    <div class="toast w-auto" :class="`toast-${type}`" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            <slot name="header">
                <i v-if="alertIcon" :class="alertIcon"></i>
                <span class="mr-auto" v-html="title"></span>
                <button v-if="canDismiss" type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" @click="$emit('close')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </slot>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        title: {
            type: String,
            default: ""
        },
        message: {
            type: String,
            default: "",
        },
        type: {
            type: String,
            default: "normal"
        },
        canDismiss: {
            type: Boolean,
            default: true,
        }
    },
    data: () => ({
        created: null
    }),
    computed: {
        alertIcon() {
            const map = {
                normal: null,
                warning: 'fas fa-exclamation-triangle text-warning',
                danger: 'fas fa-exclamation-triangle text-danger',
            }

            return typeof map[this.type] !== 'undefined' ? map[this.type] : map['normal']
        }
    },
    mounted() {
        this.created = new Date()
    }
}
</script>
<style lang="scss">
.toast-warning {
    background-color: rgba(255,243,205,.85);
    border-color: rgba(255,238,186,.85);
    color: #856404 !important;
}
.toast-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}
.toast-danger {
    color: #721c24;
    background-color: rgba(248,215,218,.85);
    border-bottom-color: rgba(245,198,203,.85);
}
.toast-info {
    background-color: rgba(229,244,247,.85);
    border-color: rgba(190,229,235,.85);
    color: #0c5460;
}
</style>
