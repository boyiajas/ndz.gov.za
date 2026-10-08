<template>
  <div class="page-wrapper bg-light documents-view">
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Documents</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">NDZ Local Municipality</p>

        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Documents</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="document-ribbon" aria-hidden="true"></div>

    <section class="py-5">
      <div class="container pb-5">
        <div v-if="loading" class="document-state">Loading document catalogue...</div>
        <div v-if="error" class="document-state error">{{ error }}</div>

        <div class="row g-4">
          <div class="col-md-6 col-lg-4" v-for="category in documentCategories" :key="category.id">
            <div class="doc-card h-100">
              <div class="doc-card-accent" aria-hidden="true"></div>

              <div class="doc-card-body">
                <h5 class="doc-card-title">
                  <router-link
                    v-if="category.slug"
                    :to="{ name: 'document-category', params: { categorySlug: category.slug } }"
                  >
                    {{ category.title }}
                  </router-link>
                  <span v-else>{{ category.title }}</span>
                </h5>
                <p class="doc-card-summary">
                  {{ category.subtitle || textSummary(category.description) || 'Browse official municipal documents and public records.' }}
                </p>
              </div>

              <div class="doc-card-meta">
                <span>{{ category.items.length }} sub {{ category.items.length === 1 ? 'section' : 'sections' }}</span>
                <span>{{ documentTotal(category) }} {{ documentTotal(category) === 1 ? 'document' : 'documents' }}</span>
              </div>

              <div class="doc-chip-list">
                <template v-for="(item, idx) in visibleItems(category)" :key="item.slug || item.label">
                  <router-link
                    v-if="category.slug && item.slug"
                    class="doc-chip"
                    :to="{ name: 'document-listing', params: { categorySlug: category.slug, subcategorySlug: item.slug } }"
                  >
                    {{ item.label }}
                    <span v-if="Number.isInteger(item.documents_count)">{{ item.documents_count }}</span>
                  </router-link>
                  <span v-else class="doc-chip">{{ item.label }}</span>
                </template>
                <span v-if="hiddenItemCount(category)" class="doc-chip more">
                  +{{ hiddenItemCount(category) }} more
                </span>
              </div>

              <router-link
                v-if="category.slug"
                class="doc-card-action"
                :to="{ name: 'document-category', params: { categorySlug: category.slug } }"
              >
                View category
                <span>→</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import api from '../api/axios'
import { stripRichText } from '../utils/richText'

const fallbackCategories = [
  {
    id: '01',
    slug: 'office-of-the-municipal-manager',
    title: 'Office of the municipal manager',
    items: [
      { label: 'Municipal Manager Organogram', slug: 'municipal-manager-organogram' },
      { label: 'APAC Charter', slug: 'apac-charter' },
      { label: 'Internal Audit Charter', slug: 'internal-audit-charter' },
      { label: 'Internal Audit Methodology', slug: 'internal-audit-methodology' },
      { label: 'Delegation of Authority', slug: 'delegation-of-authority' },
    ],
  },
  {
    id: '02',
    slug: 'corporate-services',
    title: 'Corporate Services',
    items: [
      { label: 'Organisational Structure', slug: 'organisational-structure' },
      { label: 'Administration', slug: 'administration' },
      { label: 'Information Technology', slug: 'information-technology' },
      { label: 'Human Resources', slug: 'human-resources' },
      { label: 'HR Policies', slug: 'hr-policies' },
    ],
  },
  {
    id: '03',
    slug: 'budget-treasury',
    title: 'Budget & Treasury',
    items: [
      { label: 'Budget Reports', slug: 'budget-reports' },
      { label: 'Monthly Reports', slug: 'monthly-reports' },
      { label: 'Quarterly Reports', slug: 'quarterly-reports' },
      { label: 'Annual Reports', slug: 'annual-reports' },
      { label: 'Budget Documents', slug: 'budget-documents' },
      { label: 'MPRA', slug: 'mpra' },
      { label: 'Organogram Finance', slug: 'organogram-finance' },
    ],
  },
  {
    id: '04',
    slug: 'public-works-basic-services',
    title: 'Public works & Basic Services',
    items: [
      { label: 'Service Delivery', slug: 'service-delivery' },
      { label: 'Infrastructure & Basic Services', slug: 'infrastructure-basic-services' },
      { label: 'Public Works Organogram', slug: 'public-works-organogram' },
    ],
  },
  {
    id: '05',
    slug: 'community-services',
    title: 'Community Services',
    items: [
      { label: 'Organogram Community Services', slug: 'organogram-community-services' },
      { label: 'Disaster Management', slug: 'disaster-management' },
      { label: 'Sports & Recreation', slug: 'sports-recreation' },
      { label: 'Community Programmes', slug: 'community-programmes' },
      { label: 'Youth Fund', slug: 'youth-fund' },
      { label: 'LED', slug: 'led' },
      { label: 'Arts & Culture', slug: 'arts-culture' },
    ],
  },
  {
    id: '06',
    slug: 'development-town-planning-services',
    title: 'Development & Town Planning Services',
    items: [
      { label: 'MSDF', slug: 'msdf' },
      { label: 'Planning and Land Use Documents', slug: 'planning-and-land-use-documents' },
      { label: 'Town Planning', slug: 'town-planning' },
      { label: 'Building Control', slug: 'building-control' },
    ],
  },
  {
    id: '07',
    slug: 'mpac-reports',
    title: 'Mpac Reports',
    items: [
      { label: 'Oversight Reports', slug: 'oversight-reports' },
      { label: 'Annual Oversight Reports', slug: 'annual-oversight-reports' },
      { label: 'MPAC Committee Reports', slug: 'mpac-committee-reports' },
    ],
  },
  {
    id: '08',
    slug: 'annual-reports',
    title: 'Annual Reports',
    items: [
      { label: '2025-2026', slug: '2025-2026' },
      { label: '2024-2025', slug: '2024-2025' },
      { label: '2023-2024', slug: '2023-2024' },
      { label: '2022-2023', slug: '2022-2023' },
      { label: '2021-2022', slug: '2021-2022' },
      { label: '2020-2021', slug: '2020-2021' },
    ],
  },
  {
    id: '09',
    slug: 'pms',
    title: 'Pms',
    items: [
      { label: 'Performance Agreements 2026/2027', slug: 'performance-agreements-20262027' },
      { label: 'Performance Agreements 2025/2026', slug: 'performance-agreements-20252026' },
      { label: 'Performance Agreements 2024/2025', slug: 'performance-agreements-20242025' },
      { label: 'Performance Agreements 2023/2024', slug: 'performance-agreements-20232024' },
      { label: 'Performance Agreements 2021/2022', slug: 'performance-agreements-20212022' },
      { label: 'Performance Agreements 2020/2021', slug: 'performance-agreements-20202021' },
      { label: 'Service Delivery and Budget Implementation Plan (SDBIP)', slug: 'service-delivery-and-budget-implementation-plan-sdbip' },
      { label: 'Performance Contracts', slug: 'performance-contracts' },
      { label: 'Mid Year Performance Reports', slug: 'mid-year-performance-reports' },
      { label: 'Quarterly Reports', slug: 'quarterly-reports' },
    ],
  },
  {
    id: '10',
    slug: 'idp',
    title: 'idp',
    items: [
      { label: '2026-2027', slug: '2026-2027' },
      { label: '2025-2026', slug: '2025-2026' },
      { label: '2024-2025', slug: '2024-2025' },
      { label: '2023-2024', slug: '2023-2024' },
      { label: '2022-2023', slug: '2022-2023' },
      { label: 'IDP Process Plan & Framework', slug: 'idp-process-plan-framework' },
    ],
  },
  {
    id: '11',
    slug: 'gazetted-by-laws',
    title: 'Gazetted By Laws',
    items: [
      { label: 'Gazetted By-Laws', slug: 'gazetted-by-laws' },
      { label: 'Municipal By-Laws', slug: 'municipal-by-laws' },
      { label: 'Tariff & Rates By-Laws', slug: 'tariff-rates-by-laws' },
      { label: 'Credit Control & Debt Collection By-Laws', slug: 'credit-control-debt-collection-by-laws' },
    ],
  },
  {
    id: '12',
    slug: 'ward-based-plans',
    title: 'Ward Based Plans',
    items: [
      { label: 'Ward 01 - 03 Plans', slug: 'ward-01-03-plans' },
      { label: 'Ward 04 - 06 Plans', slug: 'ward-04-06-plans' },
      { label: 'Ward 07 - 09 Plans', slug: 'ward-07-09-plans' },
      { label: 'Ward 10 - 12 Plans', slug: 'ward-10-12-plans' },
      { label: 'Ward 13 - 15 Plans', slug: 'ward-13-15-plans' },
      { label: 'Consolidated Ward Operational Plans', slug: 'consolidated-ward-operational-plans' },
    ],
  },
  {
    id: '13',
    slug: 'policies',
    title: 'Policies',
    items: [
      { label: 'Budget Related Policies', slug: 'budget-related-policies' },
      { label: 'Financial Policies', slug: 'financial-policies' },
      { label: 'Human Resources Policies', slug: 'human-resources-policies' },
      { label: 'Administrative Policies', slug: 'administrative-policies' },
      { label: 'Community Services Policies', slug: 'community-services-policies' },
      { label: 'Infrastructure Policies', slug: 'infrastructure-policies' },
      { label: 'Municipal Governance Policies', slug: 'municipal-governance-policies' },
    ],
  },
]

export default {
  name: 'DocumentsView',
  data() {
    return {
      documentCategories: fallbackCategories,
      loading: false,
      error: '',
    }
  },
  mounted() {
    this.loadDocumentCatalog()
  },
  methods: {
    async loadDocumentCatalog() {
      this.loading = true
      this.error = ''
      try {
        const { data } = await api.get('/api/document-catalog')
        this.documentCategories = (data.data || []).map((category, index) => ({
          id: String(index + 1).padStart(2, '0'),
          slug: category.slug,
          title: category.name,
          subtitle: category.subtitle,
          description: category.description,
          image_url: category.image_url,
          items: (category.subcategories || []).map((item) => ({
            label: item.name,
            slug: item.slug,
            documents_count: item.documents_count,
          })),
        }))
      } catch (error) {
        this.error = 'Showing the default document structure because the live catalogue is unavailable.'
      } finally {
        this.loading = false
      }
    },
    textSummary(value) {
      return stripRichText(value, '')
    },
    documentTotal(category) {
      return (category.items || []).reduce((total, item) => total + (Number(item.documents_count) || 0), 0)
    },
    visibleItems(category) {
      return (category.items || []).slice(0, 5)
    },
    hiddenItemCount(category) {
      return Math.max((category.items || []).length - 5, 0)
    },
  },
}
</script>

<style scoped>
.page-wrapper {
  background-color: #f8f9fa;
}

.document-ribbon {
  height: 18px;
  background: repeating-linear-gradient(
    135deg,
    var(--accent) 0 10px,
    #ffffff 10px 20px,
    var(--primary) 20px 30px,
    #ffffff 30px 40px
  );
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.document-state {
  margin-bottom: 1rem;
  color: var(--text-light);
  font-size: 0.9rem;
  font-weight: 700;
}

.document-state.error {
  color: #9b6900;
}

.doc-card {
  background: #ffffff;
  border-radius: 18px;
  padding: 1.25rem;
  box-shadow: 0 16px 42px rgba(26, 35, 50, 0.07);
  border: 1px solid #e7eee9;
  transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
  position: relative;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  min-height: 330px;
}

.doc-card:hover {
  transform: translateY(-4px);
  border-color: rgba(31, 156, 88, 0.35);
  box-shadow: 0 22px 52px rgba(31, 156, 88, 0.12);
}

.doc-card-accent {
  position: absolute;
  inset: 0 0 auto;
  height: 5px;
  background: linear-gradient(90deg, var(--primary), var(--accent));
}

.doc-card-body {
  padding-top: 0.35rem;
  min-height: 104px;
}

.doc-card-title {
  margin: 0;
  font-size: 1.12rem;
  font-weight: 800;
  line-height: 1.2;
  color: var(--text-dark);
}

.doc-card-title a:hover {
  color: var(--primary);
}

.doc-card-summary {
  margin: 0.6rem 0 0;
  color: var(--text-light);
  font-size: 0.84rem;
  line-height: 1.55;
}

.doc-card-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 0.45rem;
  margin: 1rem 0;
}

.doc-card-meta span {
  border-radius: 999px;
  background: #f4f8f5;
  color: var(--text-mid);
  border: 1px solid #e4ece7;
  padding: 0.28rem 0.65rem;
  font-size: 0.73rem;
  font-weight: 800;
}

.doc-chip-list {
  display: flex;
  flex-wrap: wrap;
  gap: 0.45rem;
  margin-bottom: 1.2rem;
}

.doc-chip {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  border-radius: 999px;
  background: rgba(31, 156, 88, 0.08);
  color: var(--text-mid);
  padding: 0.42rem 0.65rem;
  font-size: 0.78rem;
  font-weight: 700;
  line-height: 1.2;
}

.doc-chip:hover {
  background: rgba(31, 156, 88, 0.14);
  color: var(--primary);
}

.doc-chip span {
  min-width: 20px;
  height: 20px;
  border-radius: 999px;
  display: grid;
  place-items: center;
  background: #ffffff;
  color: var(--primary);
  font-size: 0.68rem;
  font-weight: 800;
}

.doc-chip.more {
  background: #f7f4e7;
  color: #9b6900;
}

.doc-card-action {
  margin-top: auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.8rem;
  border-top: 1px solid #edf2ee;
  padding-top: 1rem;
  color: var(--primary);
  font-weight: 800;
  font-size: 0.86rem;
}

.doc-card-action span {
  width: 28px;
  height: 28px;
  border-radius: 999px;
  display: grid;
  place-items: center;
  background: var(--primary);
  color: #ffffff;
  transition: transform 0.2s ease;
}

.doc-card-action:hover span {
  transform: translateX(3px);
}
</style>
