<template>
    <tr :class="normalStatus === 'running' && 'active-row'">
        <td>
            {{formattedCreationDate}}
        </td>
        <td>
            {{name}}
        </td>
        <td>
            <span :class="statusClass">{{this.normalStatus}}</span>
        </td>
        <td>
            {{duration}}
        </td>
    </tr>
</template>

<script>
import moment from "moment-timezone";
import {dateFormat} from "../../helpers";

export default {
    name: "deployment-row",
    props: {
        name: {type: String, required: true},
        created: {type: String, required: true},
        status: {type: String, required: true},
        started: {type: String, required: true},
        ended: String,
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
        normalStatus() {
            return this.status === 'active' ? 'running' : this.status
        },
        statusClass() {
            const map = {
                'complete': 'text-info',
                'succeeded': 'text-info',
                'failed': 'text-danger',
            }
            return map[this.status] ? map[this.status] : ''
        },
        formattedCreationDate() {
            return dateFormat(this.created)
        },
    },
    methods: {
        startInterval() {
            const self = this
            if (this.normalStatus === 'running' && !this.interval) {
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
            if (this.normalStatus !== 'running' && !this.ended) {
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
