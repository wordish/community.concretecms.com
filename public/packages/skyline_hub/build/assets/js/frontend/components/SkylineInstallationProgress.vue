<template>
    <div class="container" v-cloak>
        <div class="row">
            <div class="col-md-6 d-flex">
                <div class="align-self-center ms-auto me-auto ml-auto mr-auto">
                    <vue-ellipse-progress
                        :loading="currentProgress === null"
                        :progress="currentProgress"
                        color="#017ddd"
                        empty-color="#f7f7f7"
                        :thickness="4"
                        animation="loop 700 1000"
                        fontSize="1.5rem"
                        :size="300"
                    >
                        <span slot="legend-value" v-if="currentProgress !== null">
                            <span></span>%
                      </span>

                        <span slot="legend-caption"  v-if="currentProgress !== null"> COMPLETE </span>
                    </vue-ellipse-progress>
                </div>
            </div>
            <div class="col-md-6 d-flex">
                <div class="w-100 align-self-center align-items-center">
                    <div class="mb-4">
                        <h4>URL</h4>
                        <div class="bg-light text-center p-3 mb-1">
                            <b><span v-if="!isComplete" style="cursor: not-allowed">{{site.publicDomain}}</span>
                            <a v-else :href="site.publicUrl" target="_blank">{{site.publicDomain}}</a></b>
                        </div>
                        <div class="text-muted mb-3" v-if="!isComplete">
                            Your site is not quite ready. Please wait for installation to complete before visiting this URL.
                        </div>
                        <div v-else class="mb-3 mt-3 text-center">
                            <div class="btn-group">
                                <a :href="site.controlPanelUrl" class="btn btn-secondary" target="_blank">Control Panel & Billing</a>
                                <a :href="site.publicUrl" class="btn btn-primary" target="_blank">View/Edit Site</a>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h4>Username</h4>
                        <input type="text" class="form-control bg-white" readonly onclick="this.select()" value="admin">
                    </div>
                    <div class="mb-4" v-if="site.password !== null">
                        <h4>Password</h4>
                        <div class="input-group">
                            <input type="text" @click="handlePasswordClick" class="form-control bg-white" readonly :value="sitePassword">
                            <div class="input-group-append">
                                <span class="input-group-text"><a @click="togglePasswordShown" href="javascript:void(0)" class="text-gray">
                                    <i class="fa fa-eye-slash" v-if="isPasswordShown"></i>
                                    <i class="fa fa-eye" v-else></i>
                                </a></span>
                            </div>
                        </div>
                        <div class="text-muted mt-3">
                            Make a note of this password! It will not be available after the site is fully installed. If you forget this password you can reset it from your Concrete installation.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    props: {
        site: {
            type: Object,
            required: true,
            description: "The hosting site."
        }
    },
    mounted() {
        const eventSourceUrl = new URL('http://localhost:9090/.well-known/mercure')
        eventSourceUrl.searchParams.append('topic', 'https://community.concretecms.com/sites/' + this.site.handle)
        const eventSource = new EventSource(eventSourceUrl)
        eventSource.onmessage = event => {
            // Will be called every time an update is published by the server
            var data = JSON.parse(event.data)
            if (data.progress) {
                this.currentProgress = parseInt(data.progress)
            }
        }
    },
    data: () => ({
        isPasswordShown: false,
        currentProgress: null
    }),
    methods: {
        handlePasswordClick(e) {
            if (this.isPasswordShown) {
                e.target.select()
            }
        },
        togglePasswordShown() {
            if (!this.isPasswordShown) {
                this.isPasswordShown = true
            } else {
                this.isPasswordShown = false
            }
        }
    },
    computed: {
        sitePassword() {
            return this.isPasswordShown ? this.site.password : '(Hidden)'
        },

        isComplete() {
            return this.currentProgress == 100
        }
    }
}
</script>

