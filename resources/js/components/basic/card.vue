<template>
    <div class="card" :class="loading || accessDenied ? 'position-relative' : ''">
        <slot ></slot>
        <transition name="deny">
            <slot v-if="accessDenied" name="accessDenied">
                <div class="d-flex justify-content-center align-items-center access-denied">
                    <div><h2 class="text-center text-white text-shadow">Access Denied.</h2></div>
                </div>
            </slot>
        </transition>
    </div>
</template>

<script>
export default {
    name: "card",
    props: {
        loading: Boolean,
        accessDenied: {
            type: Boolean,
            default: false
        },
    }
}
</script>
<style scoped>
.loading-indicator {
    background-color: rgba(77,77,77, 0.3);
    color: #111
}

.access-denied {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,.5);
}

.text-shadow {
    text-shadow: 0 0 3px #000;
}

.deny-enter-active {
    transition: opacity .5s;
}
.deny-enter, .deny-leave-to {
    opacity: 0;
}
</style>
