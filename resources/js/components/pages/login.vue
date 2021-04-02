<template>
    <div class="login-form w-50 m-auto">
        <form @submit.prevent.stop="attemptLogin()" method="post">
            <h2 class="text-center">Log in</h2>
            <div class="help-block error" v-if="error">{{ error }}</div>
            <div class="form-group">
                <input name="email" v-model="username" type="email" class="form-control" placeholder="Email" required="required" autocomplete="off">
            </div>
            <div class="form-group">
                <input name="password" v-model="password" type="password" class="form-control" placeholder="Password" required="required" autocomplete="off">
            </div>
            <div class="form-group">
                <button :disabled="authenticating" type="submit" class="btn btn-primary btn-block">Verify Login</button>
            </div>
        </form>
    </div>
</template>

<script>
import {store} from '../../store/store'
import {router} from '../../routes/routes'

export default {
    name: "list",
    data: () => ({
        username: 'korvin@portlandlabs.com',
        password: 'password',
        authenticating: false,
        error: '',
    }),
    methods: {
        async attemptLogin() {
            const self = this
            self.authenticating = true

            const response = await fetch('http://localhost:8125/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: this.username,
                    password: this.password,
                })
            }).then((response) => response.json())

            // Start validating
            if (response.message) {
                this.error = response.message
                this.authenticating = false;
                return
            }

            if (!response.token) {
                this.error = 'Invalid login response, please try again later.'
                this.authenticating = false
                return
            }

            store.commit('login', response.token)
            self.authenticating = false

            await router.replace('/');
        }
    }
}
</script>

<style scoped>

</style>
