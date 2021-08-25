<template>
    <div class="blinky-box m-0 p-0">
        {{'a'.repeat(letterCount)}}
    </div>
</template>
<script>
export default {
    props: {
        size: {
            type: Number,
            default: null
        },
        min: {
            type: Number,
            default: 10
        },
        max: {
            type: Number,
            default: 15
        },
        blocks: Number,
        blockSize: {
            type: Number,
            default: 10
        },
        startColor: {
            type: String,
            default: 'red'
        },
        endColor: {
            type: String,
            default: 'blue'
        },
    },
    computed: {
        letterCount() {
            if (this.size > 0) {
                return this.size
            }
            const random = (this.max - this.min) * Math.random()
            return Math.round(random + this.min)
        }
    },
    data: () => ({
        test: '#111',
    })
}
</script>
<style scoped>
@keyframes blinkyBox {
    0% {
        background: #eee;
    }
    50% {
        background: #ddd;
    }
    100% {
        background: #eee;
    }
}

.blinky-box {
    color: transparent;
    animation-name: blinkyBox;
    animation-duration: .75s;
    animation-iteration-count: infinite;
    min-width: 20px;
    display: inline-block;
    height: 1rem;
    border-radius: 3px;
}
</style>
