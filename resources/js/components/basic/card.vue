<template>
    <div class="card" :class="loading ? 'position-relative' : ''">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "card",
    props: {
        loading: Boolean
    }
}
</script>
<style scoped>
.loading-indicator {
    background-color: rgba(77,77,77, 0.3);
    color: #111
}
</style>
