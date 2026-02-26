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
                this.error = err.response?.data?.message || 'Registration failed.'
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
                this.error = err.response?.data?.message || 'Login failed.'
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
    },
})
