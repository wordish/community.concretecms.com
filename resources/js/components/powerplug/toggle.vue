<template>
    <state :wrapper-attributes="wrapperAttributes" :initial="{on: initial}">
        <template v-slot="{state, setState, reset}">
            <slot v-bind="{on: state.on, reset, toggle: () => setState({on: !state.on})}"></slot>
        </template>
    </state>
</template>

<script>
import State from "./state";
export default {
    components: {State},
    props: {
        initial: {
            type: Boolean,
        },
        wrapperAttributes: {
            type: Object,
            default: () => ({}),
        }
    }
}
</script>
