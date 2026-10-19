import axios from 'axios'

// Dynamically resolve API baseURL:
// 1. When browsing the site (e.g. at http://127.0.0.1:8000, http://localhost:8000, port 8001, or live domain),
//    always use window.location.origin so requests seamlessly target the active backend port.
// 2. When browsing directly via Vite dev server (port 5199 or 5173), target the backend dev port (8000).
function getApiBaseURL() {
  if (typeof window !== 'undefined') {
    const port = window.location.port
    if (port !== '5199' && port !== '5173') {
      return window.location.origin
    }
  }
  return import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000'
}

const api = axios.create({
  baseURL: getApiBaseURL(),
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
  withCredentials: true,
})

// Attach token from localStorage if present
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Handle 401 globally
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token')
      window.location.href = '/login'
    }
    return Promise.reject(error)
  }
)

export default api
