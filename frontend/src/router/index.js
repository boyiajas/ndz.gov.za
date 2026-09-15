import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import DashboardView from '../views/DashboardView.vue'
import DashboardDocumentsView from '../views/DashboardDocumentsView.vue'
import DashboardNewsView from '../views/DashboardNewsView.vue'
import DashboardGalleryView from '../views/DashboardGalleryView.vue'
import DashboardTendersView from '../views/DashboardTendersView.vue'
import DashboardUsersView from '../views/DashboardUsersView.vue'
import DashboardSettingsView from '../views/DashboardSettingsView.vue'
import MayorsOfficeView from '../views/MayorsOfficeView.vue'
import DeputyMayorView from '../views/DeputyMayorView.vue'
import MunicipalManagerView from '../views/MunicipalManagerView.vue'
import MeetSpeakerView from '../views/MeetSpeakerView.vue'
import CouncillorsView from '../views/CouncillorsView.vue'
import TourismView from '../views/TourismView.vue'
import CorporateServicesView from '../views/CorporateServicesView.vue'
import CommunityServicesView from '../views/CommunityServicesView.vue'
import BudgetTreasuryView from '../views/BudgetTreasuryView.vue'
import DevelopmentTownPlanningServicesView from '../views/DevelopmentTownPlanningServicesView.vue'
import PublicWorksView from '../views/PublicWorksView.vue'
import NdzNewsView from '../views/NdzNewsView.vue'
import OpenTendersView from '../views/OpenTendersView.vue'
import ClosedTendersView from '../views/ClosedTendersView.vue'
import ClosedQuotesView from '../views/ClosedQuotesView.vue'
import ContractReportingView from '../views/ContractReportingView.vue'
import ContactView from '../views/ContactView.vue'
import EventGalleryView from '../views/EventGalleryView.vue'
import DocumentsView from '../views/DocumentsView.vue'
import DocumentCategoryView from '../views/DocumentCategoryView.vue'
import DocumentListingView from '../views/DocumentListingView.vue'
import NotFoundView from '../views/NotFoundView.vue'

const routes = [
    { path: '/', name: 'home', component: HomeView },
    { path: '/login', name: 'login', component: LoginView, meta: { guestOnly: true } },
    { path: '/register', name: 'register', component: RegisterView, meta: { guestOnly: true } },
    { path: '/dashboard', name: 'dashboard', component: DashboardView, meta: { requiresAuth: true, portal: true } },
    {
        path: '/dashboard/documents',
        name: 'dashboard-documents',
        component: DashboardDocumentsView,
        meta: { requiresAuth: true, requiresDocumentManager: true, portal: true },
    },
    {
        path: '/dashboard/news',
        name: 'dashboard-news',
        component: DashboardNewsView,
        meta: { requiresAuth: true, requiresContentManager: true, portal: true },
    },
    {
        path: '/dashboard/gallery',
        name: 'dashboard-gallery',
        component: DashboardGalleryView,
        meta: { requiresAuth: true, requiresContentManager: true, portal: true },
    },
    {
        path: '/dashboard/tenders',
        name: 'dashboard-tenders',
        component: DashboardTendersView,
        meta: { requiresAuth: true, requiresProcurementManager: true, portal: true },
    },
    {
        path: '/dashboard/users',
        name: 'dashboard-users',
        component: DashboardUsersView,
        meta: { requiresAuth: true, requiresAdmin: true, portal: true },
    },
    {
        path: '/dashboard/settings',
        name: 'dashboard-settings',
        component: DashboardSettingsView,
        meta: { requiresAuth: true, requiresAdmin: true, portal: true },
    },
    { path: '/mayors-office', name: 'mayors-office', component: MayorsOfficeView },
    { path: '/deputy-mayor', name: 'deputy-mayor', component: DeputyMayorView },
    { path: '/municipal-manager', name: 'municipal-manager', component: MunicipalManagerView },
    { path: '/meet-speaker', name: 'meet-speaker', component: MeetSpeakerView },
    { path: '/councillors', name: 'councillors', component: CouncillorsView },
    { path: '/tourism', name: 'tourism', component: TourismView },
    { path: '/corporate-services', name: 'corporate-services', component: CorporateServicesView },
    { path: '/community-services', name: 'community-services', component: CommunityServicesView },
    { path: '/budget-treasury', name: 'budget-treasury', component: BudgetTreasuryView },
    { path: '/development-town-planning-services', name: 'development-town-planning-services', component: DevelopmentTownPlanningServicesView },
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
    { path: '/documents/:categorySlug', name: 'document-category', component: DocumentCategoryView },
    { path: '/documents/:categorySlug/:subcategorySlug', name: 'document-listing', component: DocumentListingView },
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

    if (to.meta.requiresDocumentManager && !auth.canManageDocuments) {
        return { name: 'dashboard' }
    }

    if (to.meta.requiresContentManager && !auth.canManageContent) {
        return { name: 'dashboard' }
    }

    if (to.meta.requiresProcurementManager && !auth.canManageProcurement) {
        return { name: 'dashboard' }
    }

    if (to.meta.requiresAdmin && !auth.isAdmin) {
        return { name: 'dashboard' }
    }

    if (to.meta.guestOnly && auth.isAuthenticated) {
        return { name: 'dashboard' }
    }
})

export default router
