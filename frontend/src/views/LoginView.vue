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

          <div class="seeded-accounts">
            <div class="seeded-heading">
              <strong>Seeded login accounts</strong>
              <span>Password: Password123!</span>
            </div>
            <button
              v-for="account in seededAccounts"
              :key="account.email"
              type="button"
              class="seeded-account"
              @click="useSeededAccount(account)"
            >
              <span>{{ account.role }}</span>
              <strong>{{ account.email }}</strong>
            </button>
          </div>

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
  data() {
    return {
      form: { email: '', password: '' },
      seededAccounts: [
        { role: 'Administrator', email: 'admin@ndz.gov.za' },
        { role: 'Municipal Manager', email: 'manager@ndz.gov.za' },
        { role: 'Content Editor', email: 'editor@ndz.gov.za' },
        { role: 'Citizen', email: 'citizen@ndz.gov.za' },
      ],
    }
  },
  methods: {
    useSeededAccount(account) {
      this.form.email = account.email
      this.form.password = 'Password123!'
    },
    async handleSubmit() {
      const ok = await this.auth.login(this.form.email, this.form.password)
      if (ok) this.router.push(this.route.query.redirect || '/dashboard')
    },
  },
}
</script>

<style scoped>
.seeded-accounts {
  margin-top: 1rem;
  border: 1px solid var(--light-border);
  border-radius: 14px;
  padding: 0.9rem;
  background: #f8fbf9;
}

.seeded-heading {
  display: flex;
  justify-content: space-between;
  gap: 0.7rem;
  margin-bottom: 0.6rem;
  font-size: 0.78rem;
}

.seeded-heading strong {
  color: var(--text-dark);
}

.seeded-heading span {
  color: var(--primary);
  font-weight: 800;
}

.seeded-account {
  width: 100%;
  border: 1px solid #e2ebe6;
  background: #ffffff;
  border-radius: 10px;
  padding: 0.55rem 0.7rem;
  display: flex;
  justify-content: space-between;
  gap: 0.6rem;
  align-items: center;
  margin-top: 0.45rem;
  text-align: left;
}

.seeded-account:hover {
  border-color: var(--primary);
}

.seeded-account span {
  color: var(--text-light);
  font-size: 0.76rem;
  font-weight: 700;
}

.seeded-account strong {
  color: var(--text-dark);
  font-size: 0.78rem;
}
</style>
