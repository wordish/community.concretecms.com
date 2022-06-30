<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 d-flex">
                <div class="align-self-center ml-auto mr-auto">
                    <vue-ellipse-progress
                        :loading="currentProgress === null"
                        :progress="currentProgress"
                        :size="300"
                    />
                </div>
            </div>
            <div class="col-md-6 d-flex">
                <div class="w-100 align-self-center align-items-center">
                    <div class="mb-2">
                        <h3>View/Edit Your Site</h3>
                        <div class="bg-light text-center p-3"><b><a target="_blank" :href="site.publicUrl">{{site.publicDomain}}</a></b></div>
                    </div>
                    <div class="mb-4">
                        <h4>Username</h4>
                        <input type="text" class="form-control bg-white" readonly onclick="this.select()" value="admin">
                    </div>
                    <div class="mb-4" v-if="site.password !== null">
                        <h4>Temporary Password</h4>
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
        }
    }
}
</script>

