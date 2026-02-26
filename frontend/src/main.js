import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'

// Bootstrap JS + CSS
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'

// Global styles
import './assets/main.css'

import App from './App.vue'

const app = createApp(App)
app.use(createPinia())
app.use(router)
app.mount('#app')
