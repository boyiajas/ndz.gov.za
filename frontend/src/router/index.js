import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import DashboardView from '../views/DashboardView.vue'
import MayorsOfficeView from '../views/MayorsOfficeView.vue'
import MunicipalManagerView from '../views/MunicipalManagerView.vue'
import MeetSpeakerView from '../views/MeetSpeakerView.vue'
import CouncillorsView from '../views/CouncillorsView.vue'
import TourismView from '../views/TourismView.vue'
import ContactView from '../views/ContactView.vue'
import EventGalleryView from '../views/EventGalleryView.vue'
import NotFoundView from '../views/NotFoundView.vue'

const routes = [
    { path: '/', name: 'home', component: HomeView },
    { path: '/login', name: 'login', component: LoginView, meta: { guestOnly: true } },
    { path: '/register', name: 'register', component: RegisterView, meta: { guestOnly: true } },
    { path: '/dashboard', name: 'dashboard', component: DashboardView, meta: { requiresAuth: true } },
    { path: '/mayors-office', name: 'mayors-office', component: MayorsOfficeView },
    { path: '/municipal-manager', name: 'municipal-manager', component: MunicipalManagerView },
    { path: '/meet-speaker', name: 'meet-speaker', component: MeetSpeakerView },
    { path: '/councillors', name: 'councillors', component: CouncillorsView },
    { path: '/tourism', name: 'tourism', component: TourismView },
    { path: '/contact', name: 'contact', component: ContactView },
    { path: '/gallery', name: 'gallery', component: EventGalleryView },
    { path: '/:pathMatch(.*)*', name: 'not-found', component: NotFoundView },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior: () => ({ top: 0 }),
})

router.beforeEach((to) => {
    const auth = useAuthStore()

    if (to.meta.requiresAuth && !auth.isAuthenticated) {
        return { name: 'login', query: { redirect: to.fullPath } }
    }

    if (to.meta.guestOnly && auth.isAuthenticated) {
        return { name: 'dashboard' }
    }
})

export default router
