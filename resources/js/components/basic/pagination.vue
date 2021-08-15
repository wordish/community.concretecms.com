<template>
    <div class="ccm-search-results-pagination">
        <div class="d-flex justify-content-center w-100">
            <div>
                <ul class="pagination">
                    <li class="page-item border-left rounded" :class="{disabled: current <= 1}">
                        <a @click.prevent="previousPage" class="page-link" href="#">Previous</a>
                    </li>
                    <li
                        v-for="button in range"
                        :key="button"
                        class="page-item disabled"
                        :class="{active: button === current}">
                        <span
                            v-if="typeof button === 'number'"
                            class="page-link"
                            :class="{'font-weight-bold': button === current}"
                        >
                            {{button}}
                        </span>
                        <span class="page-link" v-else>{{button}}</span>
                        <li class="page-item border-right rounded" :class="{disabled: current >= (Math.ceil(this.total / this.pageSize))}">
                            <a @click.prevent="nextPage" class="page-link" href="#">Next</a>
                        </li>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        total: Number,
        current: Number,
        pageSize: Number
    },
    computed: {
        range() {
            const current = this.current,
                last = Math.ceil(this.total / this.pageSize),
                delta = 2,
                left = current - delta,
                right = current + delta + 1,
                range = [],
                rangeWithDots = []

            for (let i = 1; i <= last; i++) {
                if (i === 1 || i === last || i >= left && i < right) {
                    range.push(i);
                }
            }

            let l = null
            for (let i of range) {
                if (l) {
                    if (i - l === 2) {
                        rangeWithDots.push(l + 1);
                    } else if (i - l !== 1) {
                        rangeWithDots.push('...');
                    }
                }
                rangeWithDots.push(i);
                l = i;
            }

            return rangeWithDots;
        },
        previousPage() {
            this.$emit('previous')
        },
        nextPage() {
            this.$emit('next')
        }
    }
}
</script>
