<template>
    <div v-bind="wrapperAttributes">
        <slot v-bind="{state, setState, reset}"></slot>
    </div>
</template>

<script>
export default {
    props: {
        initial: {
            type: Object,
        },
        wrapperAttributes: {
            type: Object,
            default: () => ({}),
        }
    },
    data: function() {
        return {
            version: Math.random(),
            state: this.initial
        }
    },
    computed: () => ({
        slotProps() {
            return {
                state: {...this.state},
                setState: newState => this.setState(newState)
            }
        }
    }),
    methods: {
        setState(newState) {
            this.state = {
                ...this.state,
                ...newState,
            }
        },
        reset() {
            this.state = this.initial
        }
    }
}
</script>
