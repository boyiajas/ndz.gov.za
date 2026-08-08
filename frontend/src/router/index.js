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
import CorporateServicesView from '../views/CorporateServicesView.vue'
import CommunityServicesView from '../views/CommunityServicesView.vue'
import BudgetTreasuryView from '../views/BudgetTreasuryView.vue'
import PublicWorksView from '../views/PublicWorksView.vue'
import NdzNewsView from '../views/NdzNewsView.vue'
import OpenTendersView from '../views/OpenTendersView.vue'
import ClosedTendersView from '../views/ClosedTendersView.vue'
import ClosedQuotesView from '../views/ClosedQuotesView.vue'
import ContractReportingView from '../views/ContractReportingView.vue'
import ContactView from '../views/ContactView.vue'
import EventGalleryView from '../views/EventGalleryView.vue'
import DocumentsView from '../views/DocumentsView.vue'
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
    { path: '/corporate-services', name: 'corporate-services', component: CorporateServicesView },
    { path: '/community-services', name: 'community-services', component: CommunityServicesView },
    { path: '/budget-treasury', name: 'budget-treasury', component: BudgetTreasuryView },
    { path: '/public-works', name: 'public-works', component: PublicWorksView },
    { path: '/news', name: 'news', component: NdzNewsView },
    { path: '/open-tenders', name: 'open-tenders', component: OpenTendersView },
    { path: '/closed-tenders', name: 'closed-tenders', component: ClosedTendersView },
    { path: '/open-quotes', name: 'open-quotes', component: OpenTendersView },
    { path: '/bid-documents', name: 'bid-documents', component: OpenTendersView },
    { path: '/quote-documents', name: 'quote-documents', component: OpenTendersView },
    { path: '/closed-quotes', name: 'closed-quotes', component: ClosedQuotesView },
    { path: '/contract-reporting', name: 'contract-reporting', component: ContractReportingView },
    { path: '/contact', name: 'contact', component: ContactView },
    { path: '/gallery', name: 'gallery', component: EventGalleryView },
    { path: '/documents', name: 'documents', component: DocumentsView },
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
