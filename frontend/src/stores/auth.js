import { defineStore } from 'pinia'
import api from '../api/axios'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: JSON.parse(localStorage.getItem('auth_user') || 'null'),
        token: localStorage.getItem('auth_token') || null,
        loading: false,
        error: null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
        role: (state) => state.user?.role || 'citizen',
        roleLabel: (state) => {
            const labels = {
                admin: 'Administrator',
                manager: 'Municipal Manager',
                editor: 'Content Editor',
                citizen: 'Citizen',
            }

            return labels[state.user?.role] || 'Citizen'
        },
        canManageDocuments: (state) => ['admin', 'manager', 'editor'].includes(state.user?.role),
        canManageContent: (state) => ['admin', 'manager', 'editor'].includes(state.user?.role),
        canManageProcurement: (state) => ['admin', 'manager'].includes(state.user?.role),
        canManageUsers: (state) => state.user?.role === 'admin',
        canManageSettings: (state) => state.user?.role === 'admin',
        isAdmin: (state) => state.user?.role === 'admin',
    },

    actions: {
        async register(name, email, password, passwordConfirmation) {
            this.loading = true
            this.error = null
            try {
                const { data } = await api.post('/api/register', {
                    name,
                    email,
                    password,
                    password_confirmation: passwordConfirmation,
                })
                this._persist(data)
                return true
            } catch (err) {
                this.error = this._formatAuthError(err, 'Registration failed.')
                return false
            } finally {
                this.loading = false
            }
        },

        async login(email, password) {
            this.loading = true
            this.error = null
            try {
                const { data } = await api.post('/api/login', { email, password })
                this._persist(data)
                return true
            } catch (err) {
                this.error = this._formatAuthError(err, 'Login failed.')
                return false
            } finally {
                this.loading = false
            }
        },

        async logout() {
            try {
                await api.post('/api/logout')
            } finally {
                this._clear()
            }
        },

        _persist({ user, token }) {
            this.user = user
            this.token = token
            localStorage.setItem('auth_user', JSON.stringify(user))
            localStorage.setItem('auth_token', token)
        },

        _clear() {
            this.user = null
            this.token = null
            localStorage.removeItem('auth_user')
            localStorage.removeItem('auth_token')
        },

        _formatAuthError(err, fallback) {
            const errors = err.response?.data?.errors

            if (errors) {
                return Object.values(errors).flat()[0]
            }

            if (!err.response) {
                return 'Could not reach the API. Check that the Laravel server URL matches the browser URL.'
            }

            return err.response?.data?.message || fallback
        },
    },
})
