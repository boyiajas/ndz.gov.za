<template>
  <div class="auth-page">
    <div class="container">
      <div class="auth-card">
        <div class="auth-card-header">
          <router-link to="/" style="color:var(--accent);font-size:0.8rem;font-weight:600;display:block;margin-bottom:0.5rem;">← Back to Home</router-link>
          <h1>Create Account</h1>
          <p>Join the NDZ Portal — access government services online</p>
        </div>
        <div class="auth-card-body">
          <div v-if="auth.error" class="alert-gov-danger mb-4">{{ auth.error }}</div>

          <form @submit.prevent="handleSubmit" novalidate>
            <div class="mb-3">
              <label for="name" class="form-label-gov">Full Name</label>
              <input id="name" v-model="form.name" type="text" class="form-control-gov" placeholder="Jane Doe" required />
            </div>
            <div class="mb-3">
              <label for="email" class="form-label-gov">Email Address</label>
              <input id="email" v-model="form.email" type="email" class="form-control-gov" placeholder="you@example.com" required />
            </div>
            <div class="mb-3">
              <label for="password" class="form-label-gov">Password</label>
              <input id="password" v-model="form.password" type="password" class="form-control-gov" placeholder="Minimum 8 characters" required />
            </div>
            <div class="mb-4">
              <label for="confirm" class="form-label-gov">Confirm Password</label>
              <input id="confirm" v-model="form.passwordConfirmation" type="password" class="form-control-gov" placeholder="••••••••" required />
            </div>
            <button type="submit" class="btn-gov w-100 py-2" :disabled="auth.loading" style="width:100%;display:block;text-align:center;">
              <span v-if="auth.loading">Creating account...</span>
              <span v-else>Register</span>
            </button>
          </form>

          <div class="auth-divider my-4"><span>Already have an account?</span></div>
          <router-link to="/login" class="btn-gov-outline w-100 py-2" style="display:block;text-align:center;">Sign In</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'
export default {
  name: 'RegisterView',
  setup() {
    const auth   = useAuthStore()
    const router = useRouter()
    return { auth, router }
  },
  data() { return { form: { name: '', email: '', password: '', passwordConfirmation: '' } } },
  methods: {
    async handleSubmit() {
      const ok = await this.auth.register(this.form.name, this.form.email, this.form.password, this.form.passwordConfirmation)
      if (ok) this.router.push('/dashboard')
    },
  },
}
</script>
