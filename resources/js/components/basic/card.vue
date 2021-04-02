<template>
    <div class="card" :class="loading ? 'position-relative' : ''">
        <slot></slot>
        <div v-if="loading" class="position-absolute top-left w-100 h-100 d-flex loading-indicator align-items-center justify-content-center">
            <i class="fas fa-spinner fa-spin fa-lg" />
        </div>
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
