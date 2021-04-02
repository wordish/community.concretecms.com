<template>
    <tr :class="status === 'running' && 'active-row'">
        <td>
            {{formattedStartDate}}
        </td>
        <td>
            {{name}}
        </td>
        <td>
            {{status}}
        </td>
        <td>
            {{duration}}
        </td>
    </tr>
</template>

<script>
import moment from "moment-timezone";

export default {
    name: "deployment-row",
    props: {
        status: {type: String, required: true},
        started: {type: String, required: true},
        ended: String,
        name: {type: Date, required: true},
    },
    mounted() {
        this.startInterval()
        this.updateDuration()
    },
    updated() {
        this.startInterval()
        this.updateDuration()
    },
    computed: {
        formattedStartDate() {
            return moment.tz(this.started, 'UTC').tz(moment.tz.guess()).format('DD MMM YYYY h:mm a')
        },
    },
    methods: {
        startInterval() {
            const self = this
            if (this.status === 'running' && !this.interval) {
                this.interval = setInterval(function () {
                    self.updateDuration()
                    if (self.ended) {
                        clearInterval(self.interval)
                        self.interval = null
                    }
                }, 1000)
            }
        },
        updateDuration() {
            if (this.status !== 'running' && !this.ended) {
                this.duration = ''
                return
            }

            const start = moment.tz(this.started, 'UTC')
            const end = this.ended ? moment.tz(this.ended, 'UTC') : moment()

            let duration = Math.floor(Math.max(0, (end - start) / 1000))
            const hours = Math.floor(duration / 3600)
            duration = duration % 3600
            const minutes = Math.floor(duration / 60)
            let seconds = duration % 60

            if (isNaN(seconds)) {
                seconds = 0
            }

            this.duration = [
                hours ? `${hours}m` : '',
                minutes ? `${minutes}m` : '',
                `${seconds}s`
            ].join('')
        }
    },
    data: () => ({
        interval: null,
        duration: '',
    })
}
</script>

<style lang="scss" scoped>
@keyframes blink {
    100% { color: dodgerblue; }
    50% { color: #333; }
    0% { color: dodgerblue; }
}
.active-row > td{
    animation: blink 1s infinite;
}
</style>
