import config from "../config";
import {addDevToast, addToast, io} from "../helpers";
import store from "../store/store";

class Mercure {

    eventSource
    lastEventId
    eventSourceListeners = {}

    addListener(key, listener) {
        this.connect()
        this.eventSourceListeners[key] = listener
    }

    removeListener(key) {
        delete this.eventSourceListeners[key]
    }

    connected() {
        return this.eventSource && this.eventSource.readyState === 1
    }

    connect(backoff) {
        if (this.connected()) {
            return
        }

        store.commit('addToast', {
            id: 'connecting',
            message: 'Connecting..',
        })

        const lastEventString = this.lastEventId ? `&Last-Event-ID=${this.lastEventId}` : ''
        const url = `${config.mercureUrl}?topic=deploy&topic=task${lastEventString}`

        backoff = backoff ? Math.max(0, backoff) : 0
        if (this.eventSource) {
            this.eventSource.close()
        }

        this.eventSource = new EventSource(url, {
            lastEventIdQueryParameterName: 'Last-Event-ID',
            headers: {
                Authorization: `Bearer ${config.mercureToken}`,
            }
        })

        this.eventSource.addEventListener('message', e => {
            backoff = 0
            this.lastEventId = e.lastEventId

            const data = JSON.parse(e.data)
            io.groupCollapsed('[' + data.group + '] EventSourceMessage ' + e.lastEventId, function() {
                io.group('Event', () => {
                    io.log(e)
                })
                io.group('Data', () => {
                    io.log(data)
                })
            })

            for (let i in this.eventSourceListeners) {
                if (!this.eventSourceListeners.hasOwnProperty(i) || !this.eventSourceListeners[i]) {
                    continue;
                }

                io.log('Sending to listener ' + i)
                this.eventSourceListeners[i](e)
            }
        })

        this.eventSource.addEventListener('error', e => {
            io.log('MERCURE ERROR:', e)
            setTimeout(() => {
                this.connect(backoff ? backoff * 2 : 150)
            }, backoff)
        })

        this.eventSource.onopen = function(e) {
            setTimeout(() => store.commit('hideToast', 'connecting'))
        }
    }

}

const mercure = new Mercure();

export default mercure