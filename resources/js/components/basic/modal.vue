<template>
    <transition name="modal">
        <div :class="{danger, show: visible}" class="modal fade" style="display:block" v-if="visible"  tabindex="-1" role="dialog">
            <div class="modal-dialog shadow" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <slot name="header" v-bind="slotProps">
                            <h5 class="modal-title">
                                <slot name="title"></slot>
                            </h5>
                            <button @click="close" type="button" class="close" data-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </slot>
                    </div>
                    <div class="modal-body">
                        <slot name="body" v-bind="slotProps"></slot>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer" v-bind="slotProps"></slot>
                    </div>
                    <div class="modal-content-cover" v-if="loading">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    name: "modal",
    model: {
        prop: 'visible',
        event: 'change',
    },
    props: {
        visible: Boolean,
        loading: Boolean,
        danger: {
            type: Boolean,
            default: false
        },
        initialState: {
            type: Object,
            default: () => ({})
        }
    },
    data: () => ({
        state: {}
    }),
    computed: {
        slotProps() {
            return {
                close: this.close,
                state: this.state
            }
        }
    },
    methods: {
        close() {
            this.state = {}
            this.$emit('change', false)
        },
    }
}
</script>
<style scoped>
.danger .modal-header {
    background: linear-gradient(90deg, #b01c00, #f70800 75%)
}

.modal-body {
    position: relative;
}
.modal-content-cover {
    position: absolute;
    top:0;
    left:0;
    right:0;
    bottom:0;
    background-color: #11111111;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
