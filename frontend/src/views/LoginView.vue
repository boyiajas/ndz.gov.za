<template>
  <div class="auth-page">
    <div class="container">
      <div class="auth-card">
        <div class="auth-card-header">
          <router-link to="/" style="color:var(--accent);font-size:0.8rem;font-weight:600;display:block;margin-bottom:0.5rem;">← Back to Home</router-link>
          <h1>Sign In</h1>
          <p>Access your NDZ Portal account</p>
        </div>
        <div class="auth-card-body">
          <div v-if="auth.error" class="alert-gov-danger mb-4">{{ auth.error }}</div>

          <form @submit.prevent="handleSubmit" novalidate>
            <div class="mb-3">
              <label for="email" class="form-label-gov">Email Address</label>
              <input id="email" v-model="form.email" type="email" class="form-control-gov" placeholder="you@example.com" required />
            </div>
            <div class="mb-4">
              <label for="password" class="form-label-gov">Password</label>
              <input id="password" v-model="form.password" type="password" class="form-control-gov" placeholder="••••••••" required />
            </div>
            <button type="submit" class="btn-gov w-100 py-2 text-center" :disabled="auth.loading" style="width:100%;display:block;">
              <span v-if="auth.loading">Signing in...</span>
              <span v-else>Sign In</span>
            </button>
          </form>

          <div class="auth-divider my-4"><span>Don't have an account?</span></div>

          <router-link to="/register" class="btn-gov-outline w-100 py-2 text-center" style="display:block;text-align:center;">
            Create an Account
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from '../stores/auth'
import { useRouter, useRoute } from 'vue-router'
export default {
  name: 'LoginView',
  setup() {
    const auth   = useAuthStore()
    const router = useRouter()
    const route  = useRoute()
    return { auth, router, route }
  },
  data() { return { form: { email: '', password: '' } } },
  methods: {
    async handleSubmit() {
      const ok = await this.auth.login(this.form.email, this.form.password)
      if (ok) this.router.push(this.route.query.redirect || '/dashboard')
    },
  },
}
</script>
