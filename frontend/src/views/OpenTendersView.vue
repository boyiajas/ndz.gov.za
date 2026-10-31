<template>
  <div class="page-wrapper bg-light">
    <!-- Header Banner -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 3.5rem 0 3rem; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">{{ currentSection.title }}</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.85;">Supply Chain Management</p>

        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.12); padding: 0.5rem 1.25rem; border-radius: 50px;">
            <li class="breadcrumb-item">
              <router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link>
            </li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">SCM</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">
              {{ currentSection.title }} <span v-if="!isDirectListing && selectedYear">- {{ selectedYear }}</span>
            </li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Main Content Section -->
    <section class="open-tenders-section">
      <div class="container">
        <div class="tenders-shell">
          <!-- SCM Category Tabs -->
          <nav class="tenders-tabs" aria-label="SCM tabs">
            <router-link
              v-for="tab in tabs"
              :key="tab.id"
              :to="tab.to"
              class="tenders-tab"
              :class="{ 'is-active': tab.id === currentSection.id }"
            >
              {{ tab.label }}
            </router-link>
          </nav>

          <div class="tenders-panel">
            <!-- DIRECT DOCUMENT LISTINGS (Bid Documents / Quote Documents) -->
            <div v-if="isDirectListing" class="direct-docs-wrapper">
              <!-- Dark Header Banner (Old Website Style) -->
              <div class="old-site-banner">
                <h2 class="old-site-banner-title">{{ currentSection.title }}</h2>
              </div>

              <div class="old-site-content-body">
                <!-- Section Subhead -->
                <div class="table-intro-divider" style="margin-top: 0.5rem; margin-bottom: 1rem;">
                  <h3 class="attachments-label">Print or Download File Attachments</h3>
                </div>

                <!-- Year / Filter Pills if multiple available -->
                <div v-if="directYears.length > 1" class="doc-filter-pills">
                  <button
                    type="button"
                    class="doc-pill-btn"
                    :class="{ 'is-active': activeDirectYear === 'all' }"
                    @click="activeDirectYear = 'all'"
                  >
                    All Documents ({{ directTotalCount }})
                  </button>
                  <button
                    v-for="yr in directYears"
                    :key="yr"
                    type="button"
                    class="doc-pill-btn"
                    :class="{ 'is-active': activeDirectYear === yr }"
                    @click="activeDirectYear = yr"
                  >
                    {{ yr }} ({{ countForDirectYear(yr) }})
                  </button>
                </div>

                <!-- Search & Counter -->
                <div class="table-search-bar">
                  <input
                    v-model="searchQuery"
                    type="text"
                    :placeholder="`Search ${currentSection.title.toLowerCase()} by title or keyword...`"
                    class="search-input"
                  />
                  <span class="search-counter" v-if="filteredDocuments.length">
                    {{ filteredDocuments.length }} file<span v-if="filteredDocuments.length !== 1">s</span> available
                  </span>
                </div>

                <!-- Loading State -->
                <div v-if="loading" class="loading-state">
                  <span class="spinner-icon">⏳</span> Loading {{ currentSection.title.toLowerCase() }}...
                </div>

                <!-- Documents Table (Old Website Style) -->
                <div v-else-if="filteredDocuments.length > 0" class="table-responsive">
                  <table class="da-attachments-table">
                    <thead>
                      <tr>
                        <th class="attachment-index" style="width: 60px;">#</th>
                        <th class="attachment-title">FILE</th>
                        <th class="attachment-downloads" style="width: 140px; text-align: right;">DOWNLOADS</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(doc, idx) in filteredDocuments"
                        :key="doc.id || idx"
                        class="pdf-row"
                      >
                        <td class="attachment-index">{{ idx + 1 }}</td>
                        <td class="attachment-title">
                          <a
                            :href="getDocumentUrl(doc)"
                            target="_blank"
                            rel="noopener"
                            class="attachment-link"
                            @click="handleDocumentClick(doc)"
                          >
                            <span class="doc-icon">{{ getFileIcon(doc) }}</span>
                            <span class="link-text">{{ doc.title }}</span>
                            <span v-if="doc.file_size" class="doc-size">({{ doc.file_size }})</span>
                          </a>
                        </td>
                        <td class="attachment-downloads" style="text-align: right;">
                          <span class="dl-count-badge">{{ doc.download_count ?? 0 }}</span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- Empty State -->
                <div v-else class="old-site-empty">
                  <p>No {{ currentSection.title.toLowerCase() }} found matching your criteria.</p>
                </div>
              </div>
            </div>

            <!-- FINANCIAL YEAR DRIVEN LISTINGS (Open Tenders / Open Quotations) -->
            <div v-else class="year-driven-wrapper">
              <!-- Year Cards Grid -->
              <div class="year-cards-header">
                <h2 class="year-cards-heading">Select Financial Year</h2>
                <p class="year-cards-sub">
                  Click a {{ currentSection.type === 'quote' ? 'quotation' : 'tender' }} year below to view and download all available procurement opportunities and documents.
                </p>
              </div>

              <div class="tenders-grid">
                <button
                  v-for="year in availableYears"
                  :key="year"
                  class="tender-year-card"
                  :class="{ 'is-active': selectedYear === year }"
                  type="button"
                  @click="selectYear(year)"
                >
                  <span class="year-title">{{ year }}</span>
                  <span class="year-badge-count">
                    <span v-if="loading && notices.length === 0">Loading...</span>
                    <span v-else>{{ countForYear(year) }} {{ countForYear(year) === 1 ? 'document' : 'documents' }}</span>
                  </span>
                </button>
              </div>

              <!-- Selected Year Document Section (Matching Old Website Design) -->
              <div id="year-results-section" class="old-site-tender-section" v-if="selectedYear">
                <!-- Dark Header Banner -->
                <div class="old-site-banner">
                  <h2 class="old-site-banner-title">{{ currentSection.title }} {{ selectedYear }}</h2>
                </div>

                <!-- Introductory Municipal Notice -->
                <div class="old-site-content-body">
                  <h3 class="section-kicker">{{ currentSection.kickerPrefix }}: {{ selectedYear }} Financial Year</h3>

                  <p class="intro-paragraph">
                    Welcome to the official <strong>{{ currentSection.welcomePrefix }}</strong> of the
                    <strong>Dr. Nkosazana Dlamini-Zuma Municipality</strong> for the
                    <strong>{{ selectedYear }} financial year</strong>.
                  </p>

                  <p class="intro-paragraph">
                    {{ currentSection.introText }}
                  </p>

                  <p class="intro-paragraph">
                    We are committed to a <strong>fair, transparent, and competitive procurement process</strong> that promotes
                    local economic development, empowers emerging businesses, and ensures quality service delivery to our communities.
                  </p>

                  <div class="bullet-list-box">
                    <p class="bullet-list-title">Here you will find:</p>
                    <ul class="bullet-list">
                      <li v-for="bullet in currentSection.bullets" :key="bullet">{{ bullet }}</li>
                    </ul>
                  </div>

                  <p class="intro-paragraph">
                    {{ currentSection.closingText }}
                  </p>

                  <!-- Quick Action Link (e.g. Download Quote Documents / Download Bid Documents) -->
                  <div v-if="currentSection.quickAction" class="quick-action-link-row">
                    <router-link :to="currentSection.quickAction.to" class="green-text-link">
                      {{ currentSection.quickAction.text }}
                    </router-link>
                  </div>

                  <div class="table-intro-divider">
                    <p class="table-intro-title">{{ currentSection.tableIntro }}</p>
                    <h4 class="attachments-label">Print or Download File Attachments</h4>
                  </div>

                  <!-- Search & Filter within Selected Year -->
                  <div class="table-search-bar">
                    <input
                      v-model="searchQuery"
                      type="text"
                      :placeholder="`Search ${selectedYear} ${currentSection.singularTitle.toLowerCase()} by title or keyword...`"
                      class="search-input"
                    />
                    <span class="search-counter" v-if="filteredDocuments.length">
                      {{ filteredDocuments.length }} file<span v-if="filteredDocuments.length !== 1">s</span> available
                    </span>
                  </div>

                  <!-- Loading State -->
                  <div v-if="loading" class="loading-state">
                    <span class="spinner-icon">⏳</span> Loading documents for {{ selectedYear }}...
                  </div>

                  <!-- Documents Table (Old Website Style) -->
                  <div v-else-if="filteredDocuments.length > 0" class="table-responsive">
                    <table class="da-attachments-table">
                      <thead>
                        <tr>
                          <th class="attachment-index" style="width: 60px;">#</th>
                          <th class="attachment-title">FILE</th>
                          <th class="attachment-downloads" style="width: 140px; text-align: right;">DOWNLOADS</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="(doc, idx) in filteredDocuments"
                          :key="doc.id || idx"
                          class="pdf-row"
                        >
                          <td class="attachment-index">{{ idx + 1 }}</td>
                          <td class="attachment-title">
                            <a
                              :href="getDocumentUrl(doc)"
                              target="_blank"
                              rel="noopener"
                              class="attachment-link"
                              @click="handleDocumentClick(doc)"
                            >
                              <span class="doc-icon">{{ getFileIcon(doc) }}</span>
                              <span class="link-text">{{ doc.title }}</span>
                              <span v-if="doc.file_size" class="doc-size">({{ doc.file_size }})</span>
                            </a>
                          </td>
                          <td class="attachment-downloads" style="text-align: right;">
                            <span class="dl-count-badge">{{ doc.download_count ?? 0 }}</span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <!-- Empty State -->
                  <div v-else class="old-site-empty">
                    <div v-if="errorMessage" class="error-box">
                      <p class="error-text">{{ errorMessage }}</p>
                      <button type="button" class="btn-retry" @click="fetchProcurementNotices">Retry</button>
                    </div>
                    <p v-else>No procurement documents found for {{ selectedYear }} matching your criteria.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import api from '../api/axios'

export default {
  name: 'OpenTendersView',
  data() {
    return {
      selectedYear: '2025-2026',
      searchQuery: '',
      activeDirectYear: 'all',
      loading: false,
      errorMessage: '',
      notices: (() => {
        try {
          return JSON.parse(localStorage.getItem('cached_procurement_notices') || '[]')
        } catch {
          return []
        }
      })(),
      availableYears: [
        '2026-2027',
        '2025-2026',
        '2024-2025',
        '2023-2024',
        '2022-2023',
        '2021-2022',
        '2020-2021',
        '2018-2019',
        '2017-2018',
        '2016-2017',
      ],
      tabs: [
        { id: 'open-tenders', label: 'Open Tenders', to: '/open-tenders' },
        { id: 'open-quotes', label: 'Open Quotations', to: '/open-quotes' },
        { id: 'closed-tenders', label: 'Closed Tenders', to: '/closed-tenders' },
        { id: 'closed-quotes', label: 'Closed Quotes', to: '/closed-quotes' },
        { id: 'bid-documents', label: 'Bid Documents', to: '/bid-documents' },
        { id: 'quote-documents', label: 'Quote Documents', to: '/quote-documents' },
        { id: 'intention-to-award', label: 'Intention To Award', to: '/intention-to-award' },
        { id: 'briefing-link', label: 'Briefing Link', to: '/briefing-link' },
        { id: 'erratum-and-addendum', label: 'Erratum And Addendum', to: '/erratum-and-addendum' },
      ],
      sections: {
        'open-tenders': {
          id: 'open-tenders',
          title: 'Open Tenders',
          singularTitle: 'Tenders',
          type: 'tender',
          isClosed: false,
          category: 'Open Tenders',
          isDirect: false,
          kickerPrefix: 'Tenders',
          welcomePrefix: 'Tenders Section',
          introText: 'This platform provides all relevant tender notices, bid documents, and procurement opportunities available to Service Providers, Contractors, and Suppliers interested in doing business with the municipality.',
          bullets: [
            'Open Tender Invitations',
            'Bid Specifications and Requirements',
            'Closing Dates and Submission Guidelines',
          ],
          closingText: "All interested parties are encouraged to regularly check this page for updates and to ensure compliance with the specific conditions of each tender. Together, let's build a responsive, inclusive, and service-driven municipality.",
          quickAction: {
            text: 'Download Bid Documents',
            to: '/bid-documents',
          },
          defaultYear: '2025-2026',
        },
        'closed-tenders': {
          id: 'closed-tenders',
          title: 'Closed Tenders',
          singularTitle: 'Tenders',
          type: 'tender',
          isClosed: true,
          category: 'Open Tenders',
          isDirect: false,
          kickerPrefix: 'Closed Tenders',
          welcomePrefix: 'Closed Tenders Archive',
          introText: 'This platform provides historical tender notices, closed procurement listings, and awarded bid specifications for Service Providers, Contractors, and the public.',
          bullets: [
            'Closed Tender Notices and Outcomes',
            'Historical Bid Specifications and Requirements',
            'Archived Procurement Documentation',
          ],
          closingText: 'For enquiries regarding past tenders or archived procurement records, please contact the Supply Chain Management unit.',
          quickAction: {
            text: 'Download Bid Documents',
            to: '/bid-documents',
          },
          defaultYear: '2024-2025',
        },
        'open-quotes': {
          id: 'open-quotes',
          title: 'Open Quotations',
          singularTitle: 'Quotations',
          type: 'quote',
          isClosed: false,
          category: 'Quotations',
          isDirect: false,
          kickerPrefix: 'Quotations',
          welcomePrefix: 'Quotation Section',
          introText: 'This platform provides all relevant quotations notices, quote documents, and procurement opportunities available to Service Providers, Contractors, and Suppliers interested in doing business with the municipality.',
          bullets: [
            'Request for Quotations (RFQs)',
            'Quote Specifications and Requirements',
            'Closing Dates and Submission Guidelines',
          ],
          closingText: "All interested parties are encouraged to regularly check this page for updates and to ensure compliance with the specific conditions of each quotation. Together, let's build a responsive, inclusive, and service-driven municipality.",
          quickAction: {
            text: 'Download Quote Documents',
            to: '/quote-documents',
          },
          defaultYear: '2026-2027',
        },
        'closed-quotes': {
          id: 'closed-quotes',
          title: 'Closed Quotes',
          singularTitle: 'Quotations',
          type: 'quote',
          isClosed: true,
          category: 'Quotations',
          isDirect: false,
          kickerPrefix: 'Closed Quotations',
          welcomePrefix: 'Closed Quotation Archive',
          introText: 'This platform provides historical quotation notices, closed RFQs, and procurement documentation for Service Providers, Contractors, and the public.',
          bullets: [
            'Closed Request for Quotations (RFQs)',
            'Historical Quote Specifications and Requirements',
            'Archived Quotation Records',
          ],
          closingText: 'For enquiries regarding past quotations or archived procurement records, please contact the Supply Chain Management unit.',
          quickAction: {
            text: 'Download Quote Documents',
            to: '/quote-documents',
          },
          defaultYear: '2024-2025',
        },
        'bid-documents': {
          id: 'bid-documents',
          title: 'Bid Documents',
          singularTitle: 'Bid Documents',
          type: 'tender',
          category: 'Bid Documents',
          isDirect: true,
        },
        'quote-documents': {
          id: 'quote-documents',
          title: 'Quote Documents',
          singularTitle: 'Quote Documents',
          type: 'quote',
          category: 'Quote Documents',
          isDirect: true,
        },
        'intention-to-award': {
          id: 'intention-to-award',
          title: 'Intention To Award',
          singularTitle: 'Intention To Award',
          type: 'tender',
          category: 'Intention To Award',
          isDirect: true,
        },
        'briefing-link': {
          id: 'briefing-link',
          title: 'Briefing Link',
          singularTitle: 'Briefing Link',
          type: 'tender',
          category: 'Briefing Link',
          isDirect: true,
        },
        'erratum-and-addendum': {
          id: 'erratum-and-addendum',
          title: 'Erratum And Addendum',
          singularTitle: 'Erratum And Addendum',
          type: 'tender',
          category: 'Erratum And Addendum',
          isDirect: true,
        },
      },
    }
  },
  computed: {
    currentSection() {
      return this.sections[this.$route.name] || this.sections['open-tenders']
    },
    isDirectListing() {
      return Boolean(this.currentSection.isDirect)
    },
    directYears() {
      const set = new Set()
      this.notices.forEach((n) => {
        if (n.category === this.currentSection.category && n.financial_year) {
          set.add(n.financial_year.replace('/', '-'))
        }
      })
      return Array.from(set).sort().reverse()
    },
    directTotalCount() {
      return this.notices.filter((n) => n.category === this.currentSection.category).length
    },
    filteredDocuments() {
      const q = this.searchQuery.trim().toLowerCase()
      const targetYear = (this.selectedYear || '').toLowerCase().replace('/', '-')

      let list = []

      if (this.isDirectListing) {
        list = this.notices.filter((n) => {
          if (n.category !== this.currentSection.category) return false
          if (this.activeDirectYear !== 'all') {
            const itemYear = (n.financial_year || '').toLowerCase().replace('/', '-')
            if (itemYear !== this.activeDirectYear.toLowerCase()) return false
          }
          return true
        })
      } else {
        list = this.notices.filter((n) => {
          const itemYear = (n.financial_year || '').toLowerCase().replace('/', '-')
          if (itemYear !== targetYear) return false

          if (this.currentSection.isClosed) {
            if (n.status !== 'closed') return false
          } else {
            if (n.status === 'closed') return false
          }

          if (this.currentSection.type === 'quote') {
            return n.type === 'quote' || n.category === 'Quotations' || n.category === 'Open Quotations'
          }
          return n.type === 'tender' || n.category === 'Open Tenders' || (!n.type && !n.category?.includes('Quote'))
        })
      }

      if (q) {
        list = list.filter(
          (doc) =>
            doc.title?.toLowerCase().includes(q) ||
            doc.reference_no?.toLowerCase().includes(q) ||
            doc.file_name?.toLowerCase().includes(q)
        )
      }

      list.sort((a, b) => {
        const dateA = new Date(a.created_at || a.updated_at || a.closing_date || 0).getTime()
        const dateB = new Date(b.created_at || b.updated_at || b.closing_date || 0).getTime()
        if (dateB !== dateA) {
          return dateB - dateA
        }
        return (b.id || 0) - (a.id || 0)
      })

      return list
    },
  },
  watch: {
    '$route.name'() {
      this.searchQuery = ''
      this.activeDirectYear = 'all'
      if (!this.isDirectListing && !this.$route.query.year) {
        this.selectedYear = this.pickDefaultYear()
      }
      this.fetchProcurementNotices()
    },
  },
  mounted() {
    if (this.$route.query.year) {
      this.selectedYear = this.$route.query.year.replace('/', '-')
    } else {
      this.selectedYear = this.currentSection.defaultYear || '2025-2026'
    }
    this.fetchProcurementNotices()
  },
  methods: {
    pickDefaultYear() {
      if (this.$route.query.year) {
        return this.$route.query.year.replace('/', '-')
      }
      const section = this.currentSection
      if (section.defaultYear && this.countForYear(section.defaultYear) > 0) {
        return section.defaultYear
      }
      for (const yr of this.availableYears) {
        if (this.countForYear(yr) > 0) {
          return yr
        }
      }
      return section.defaultYear || '2025-2026'
    },
    getFileIcon(doc) {
      const name = (doc.file_name || doc.document_url || doc.title || '').toLowerCase()
      if (name.endsWith('.xlsx') || name.endsWith('.xls') || name.endsWith('.csv') || name.includes('boq')) {
        return '📊'
      }
      if (name.endsWith('.doc') || name.endsWith('.docx')) {
        return '📝'
      }
      if (name.endsWith('.zip') || name.endsWith('.rar')) {
        return '🗜️'
      }
      return '📄'
    },
    getDocumentUrl(doc) {
      if (!doc || !doc.document_url) return '#'
      if (doc.document_url.startsWith('http://') || doc.document_url.startsWith('https://')) {
        return doc.document_url
      }
      if (typeof window !== 'undefined' && (window.location.port === '5199' || window.location.port === '5173')) {
        return `http://127.0.0.1:8000${doc.document_url}`
      }
      return doc.document_url
    },
    async fetchProcurementNotices() {
      this.loading = true
      this.errorMessage = ''
      try {
        const { data } = await api.get('/api/procurement')
        this.notices = data.data || []
        try {
          localStorage.setItem('cached_procurement_notices', JSON.stringify(this.notices))
        } catch {}

        // Extract available years from live data
        const yearSet = new Set(this.availableYears)
        this.notices.forEach((n) => {
          if (n.financial_year && n.financial_year !== 'Current') {
            yearSet.add(n.financial_year.replace('/', '-'))
          }
        })
        this.availableYears = Array.from(yearSet).sort().reverse()

        // Ensure selected year is in available list and has items if possible
        if (!this.isDirectListing) {
          if (!this.availableYears.includes(this.selectedYear) || (this.countForYear(this.selectedYear) === 0 && !this.$route.query.year)) {
            this.selectedYear = this.pickDefaultYear()
          }
        }
      } catch (err) {
        console.error('Failed to load procurement notices', err)
        if (this.notices.length === 0) {
          this.errorMessage = 'Unable to load procurement documents. Please check backend connection and retry.'
        }
      } finally {
        this.loading = false
      }
    },
    countForYear(year) {
      const targetYear = (year || '').toLowerCase().replace('/', '-')
      return this.notices.filter((n) => {
        const itemYear = (n.financial_year || '').toLowerCase().replace('/', '-')
        if (itemYear !== targetYear) return false

        if (this.currentSection.isClosed) {
          if (n.status !== 'closed') return false
        } else {
          if (n.status === 'closed') return false
        }

        if (this.currentSection.type === 'quote') {
          return n.type === 'quote' || n.category === 'Quotations' || n.category === 'Open Quotations'
        }
        return n.type === 'tender' || n.category === 'Open Tenders' || (!n.type && !n.category?.includes('Quote'))
      }).length
    },
    countForDirectYear(yr) {
      const target = (yr || '').toLowerCase().replace('/', '-')
      return this.notices.filter((n) => {
        if (n.category !== this.currentSection.category) return false
        const itemYear = (n.financial_year || '').toLowerCase().replace('/', '-')
        return itemYear === target
      }).length
    },
    selectYear(year) {
      this.selectedYear = year
      this.searchQuery = ''
      // Scroll smoothly to the results section
      this.$nextTick(() => {
        const el = document.getElementById('year-results-section')
        if (el) {
          el.scrollIntoView({ behavior: 'smooth', block: 'start' })
        }
      })
    },
    handleDocumentClick(doc) {
      doc.download_count = (doc.download_count || 0) + 1
      if (doc.id) {
        api.get(`/api/procurement/${doc.id}/download`).catch(() => {})
      }
    },
  },
}
</script>

<style scoped>
.page-wrapper {
  min-height: 100vh;
  background-color: #f7f9f8;
}

.open-tenders-section {
  padding: 2.5rem 0 5rem;
}

.page-header :deep(.breadcrumb-item + .breadcrumb-item::before) {
  color: rgba(255, 255, 255, 0.7);
}

.page-header :deep(.breadcrumb-item.active) {
  color: #ffffff;
}

.page-header :deep(.breadcrumb-item a:hover) {
  color: #ffffff !important;
}

.tenders-shell {
  width: 100%;
  box-shadow: 0 4px 24px rgba(0, 0, 0, 0.05);
  border-radius: 12px;
  overflow: hidden;
}

.tenders-tabs {
  display: grid;
  grid-template-columns: repeat(5, minmax(0, 1fr));
  align-items: stretch;
  gap: 0;
  background: #ffffff;
}

.tenders-tab {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 3.75rem;
  padding: 0.85rem 0.65rem;
  background: #ffffff;
  color: #1f2f4a;
  font-size: 0.98rem;
  font-weight: 700;
  text-align: center;
  text-decoration: none;
  border: 1px solid #eef2f0;
  transition: all 0.2s ease;
}

.tenders-tab:hover {
  color: #0f6b3b;
  background: #f4faf6;
}

.tenders-tab.is-active {
  background: #0f6b3b;
  color: #ffffff;
  border-color: #0f6b3b;
}

.tenders-panel {
  padding: 2.5rem 2rem;
  background: #ffffff;
}

.year-cards-header {
  margin-bottom: 1.5rem;
}

.year-cards-heading {
  font-size: 1.45rem;
  font-weight: 800;
  color: #17231c;
  margin: 0 0 0.4rem;
}

.year-cards-sub {
  color: #5a6678;
  font-size: 0.95rem;
  margin: 0;
}

.tenders-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.25rem;
  margin-bottom: 2.5rem;
}

.tender-year-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 10.5rem;
  padding: 1.5rem;
  width: 100%;
  background: #ffffff;
  color: #003323;
  border: 2px solid #0f6b3b;
  border-radius: 12px;
  appearance: none;
  cursor: pointer;
  transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
  box-shadow: 0 4px 12px rgba(15, 107, 59, 0.05);
}

.tender-year-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 24px rgba(15, 107, 59, 0.15);
  border-color: #0c562f;
}

.tender-year-card.is-active {
  background: #0f6b3b;
  color: #ffffff;
  border-color: #0f6b3b;
  box-shadow: 0 12px 28px rgba(15, 107, 59, 0.25);
}

.year-title {
  font-size: 2.1rem;
  font-weight: 800;
  letter-spacing: -0.02em;
}

.year-badge-count {
  margin-top: 0.5rem;
  font-size: 0.85rem;
  font-weight: 700;
  padding: 0.25rem 0.75rem;
  border-radius: 50px;
  background: #eef8f2;
  color: #0f6b3b;
}

.tender-year-card.is-active .year-badge-count {
  background: rgba(255, 255, 255, 0.22);
  color: #ffffff;
}

/* Old Site Tender Section */
.old-site-tender-section {
  border-top: 2px solid #eef2f0;
  padding-top: 2rem;
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(6px); }
  to { opacity: 1; transform: translateY(0); }
}

.old-site-banner {
  background: #17231c;
  color: #ffffff;
  padding: 1.25rem 1.75rem;
  border-radius: 8px 8px 0 0;
  margin-bottom: 1.75rem;
}

.old-site-banner-title {
  margin: 0;
  font-size: 1.6rem;
  font-weight: 800;
  letter-spacing: 0.02em;
}

.old-site-content-body {
  padding: 0 0.5rem;
}

.section-kicker {
  font-size: 1.15rem;
  font-weight: 800;
  color: #17231c;
  margin-bottom: 1rem;
}

.intro-paragraph {
  font-size: 0.96rem;
  line-height: 1.65;
  color: #334155;
  margin-bottom: 0.9rem;
}

.bullet-list-box {
  background: #f8faf9;
  border-left: 4px solid #0f6b3b;
  padding: 1rem 1.25rem;
  border-radius: 0 8px 8px 0;
  margin: 1.25rem 0;
}

.bullet-list-title {
  font-weight: 800;
  font-size: 0.95rem;
  color: #17231c;
  margin-bottom: 0.4rem;
}

.bullet-list {
  margin: 0;
  padding-left: 1.25rem;
  color: #334155;
  font-size: 0.93rem;
  line-height: 1.6;
}

.quick-action-link-row {
  margin: 1.25rem 0 1.5rem;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid #dce8e0;
}

.green-text-link {
  color: #0f6b3b;
  font-weight: 700;
  font-size: 1.05rem;
  text-decoration: underline;
  transition: color 0.15s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
}

.green-text-link:hover {
  color: #094726;
  text-decoration: underline;
}

.table-intro-divider {
  margin-top: 1.75rem;
  margin-bottom: 1rem;
}

.table-intro-title {
  font-size: 0.95rem;
  color: #334155;
  margin-bottom: 0.5rem;
}

.attachments-label {
  font-size: 1.1rem;
  font-weight: 800;
  color: #17231c;
  margin: 0;
}

/* Document Filter Pills */
.doc-filter-pills {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1.25rem;
}

.doc-pill-btn {
  padding: 0.45rem 1rem;
  border-radius: 50px;
  background: #f1f6f3;
  color: #17231c;
  font-size: 0.88rem;
  font-weight: 700;
  border: 1px solid #c2dbcd;
  cursor: pointer;
  transition: all 0.2s ease;
}

.doc-pill-btn:hover {
  background: #e1efe7;
  color: #0f6b3b;
}

.doc-pill-btn.is-active {
  background: #0f6b3b;
  color: #ffffff;
  border-color: #0f6b3b;
}

.table-search-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1rem;
  margin-top: 1rem;
  flex-wrap: wrap;
}

.search-input {
  flex: 1;
  min-width: 250px;
  padding: 0.65rem 1rem;
  border: 1px solid #d9e6de;
  border-radius: 8px;
  font-size: 0.9rem;
  background: #fbfdfc;
}

.search-counter {
  font-size: 0.85rem;
  font-weight: 700;
  color: #0f6b3b;
}

/* Da-Attachments-Table matching the old website */
.table-responsive {
  overflow-x: auto;
  background: #ffffff;
  border: 1px solid #eef2f0;
  border-radius: 8px;
}

.da-attachments-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.92rem;
}

.da-attachments-table thead th {
  background: #f8faf9;
  color: #4a5c52;
  font-weight: 800;
  font-size: 0.8rem;
  letter-spacing: 0.05em;
  padding: 0.85rem 1.2rem;
  border-bottom: 2px solid #dde6e1;
  text-align: left;
}

.da-attachments-table tbody tr {
  border-bottom: 1px solid #edf3ef;
  transition: background-color 0.15s ease;
}

.da-attachments-table tbody tr:hover {
  background-color: #f4faf6;
}

.da-attachments-table td {
  padding: 0.9rem 1.2rem;
  vertical-align: middle;
}

.attachment-index {
  color: #6c7d73;
  font-weight: 700;
  font-size: 0.85rem;
}

.attachment-link {
  color: #0f6b3b;
  font-weight: 700;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  line-height: 1.4;
}

.attachment-link:hover {
  color: #0c562f;
  text-decoration: underline;
}

.doc-icon {
  font-size: 1.1rem;
  flex-shrink: 0;
}

.doc-size {
  color: #6c7d73;
  font-size: 0.78rem;
  font-weight: 500;
}

.dl-count-badge {
  display: inline-block;
  background: #f0f7f3;
  color: #0f6b3b;
  padding: 0.2rem 0.65rem;
  border-radius: 6px;
  font-size: 0.82rem;
  font-weight: 700;
  border: 1px solid #c2dbcd;
}

.old-site-empty {
  text-align: center;
  padding: 3rem 1rem;
  color: #6c7d73;
  background: #f8faf9;
  border-radius: 8px;
  border: 1px dashed #c2dbcd;
}

.loading-state {
  text-align: center;
  padding: 3rem 1rem;
  color: #0f6b3b;
  font-weight: 700;
  font-size: 1.05rem;
}

.spinner-icon {
  display: inline-block;
  margin-right: 0.4rem;
  animation: pulse 1s infinite;
}

@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.15); }
  100% { transform: scale(1); }
}

@media (max-width: 1199.98px) {
  .tenders-tab {
    font-size: 0.88rem;
    padding: 0.75rem 0.45rem;
  }
}

@media (max-width: 991.98px) {
  .tenders-tabs {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 575.98px) {
  .tenders-tabs {
    grid-template-columns: 1fr;
  }

  .tender-year-card {
    min-height: 8.5rem;
  }

  .year-title {
    font-size: 1.8rem;
  }

  .tenders-panel {
    padding: 1.5rem 1rem;
  }
}
</style>
