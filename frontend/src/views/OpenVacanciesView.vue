<template>
  <div class="page-wrapper bg-light">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Open Vacancies</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.85;">Career Centre &amp; Municipal Employment Opportunities</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">Career Centre</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Open Vacancies</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Main Content Section -->
    <section class="py-5">
      <div class="container pb-5">
        <!-- Application Form Banner -->
        <div class="card border-0 shadow-sm mb-5" style="border-left: 5px solid #2e7d32 !important; background: #ffffff;">
          <div class="card-body p-4 p-md-5">
            <div class="row align-items-center g-4">
              <div class="col-lg-8">
                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 rounded-pill mb-2">
                  <i class="bi bi-info-circle-fill me-1"></i> Application Procedure
                </span>
                <h3 class="fw-bold text-dark mb-2">How to Apply for Advertised Posts</h3>
                <p class="text-secondary mb-0" style="line-height: 1.7;">
                  Applications must include a comprehensive CV, certified copies of academic certificates, and South African ID. Submissions can be emailed to <a href="mailto:Mailbox@ndz.gov.za" class="fw-semibold text-primary">Mailbox@ndz.gov.za</a> or hand-delivered to the Main Street Municipal Offices in Creighton.
                </p>
              </div>
              <div class="col-lg-4 text-lg-end">
                <a
                  href="mailto:Mailbox@ndz.gov.za?subject=Municipal%20Job%20Application"
                  class="btn btn-success btn-lg px-4 py-3 fw-bold shadow-sm d-inline-flex align-items-center gap-2"
                  style="background: #2e7d32; border-color: #2e7d32;"
                >
                  <span>Email Application</span>
                  <i class="bi bi-envelope-fill"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-5 align-items-start">
          <!-- Sidebar -->
          <div class="col-lg-3">
            <div class="quick-links-sidebar bg-primary text-white rounded overflow-hidden shadow-sm">
              <h4 class="p-4 mb-0 fw-bold border-bottom border-light border-opacity-25" style="font-size: 1.15rem;">Career Centre</h4>
              <ul class="list-unstyled mb-0">
                <li>
                  <router-link to="/open-vacancies" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10 active bg-black bg-opacity-10 fw-semibold">
                    Open Vacancies <i class="bi bi-chevron-right float-end"></i>
                  </router-link>
                </li>
                <li>
                  <router-link to="/closed-vacancies" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10">
                    Closed Vacancies <i class="bi bi-chevron-right float-end"></i>
                  </router-link>
                </li>
                <li>
                  <router-link to="/contact" class="d-block p-3 text-white text-decoration-none">
                    HR / Inquiries <i class="bi bi-chevron-right float-end"></i>
                  </router-link>
                </li>
              </ul>
            </div>

            <!-- Notice card -->
            <div class="card border-0 shadow-sm mt-4 bg-white p-4">
              <h6 class="fw-bold text-dark mb-2"><i class="bi bi-info-circle-fill text-primary me-2"></i>Application Guidelines</h6>
              <p class="text-secondary small mb-3">
                Completed applications must include a detailed CV, certified copies of academic qualifications, ID document, and driver’s license (where required).
              </p>
              <p class="text-secondary small mb-0">
                Email inquiries: <a href="mailto:Mailbox@ndz.gov.za" class="text-primary text-decoration-none fw-semibold">Mailbox@ndz.gov.za</a>
              </p>
            </div>
          </div>

          <!-- Main Vacancies List -->
          <div class="col-lg-9">
            <div class="content-card bg-white shadow-sm rounded p-4 p-md-5">
              <!-- Filter & Search Bar -->
              <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div>
                  <h3 class="fw-bold text-dark mb-1">Current Open Opportunities</h3>
                  <p class="text-muted small mb-0">Browse advertised municipal vacancies below</p>
                </div>
                <div class="d-flex gap-2">
                  <span class="badge bg-primary px-3 py-2 rounded-pill fs-6">{{ filteredVacancies.length }} Available</span>
                </div>
              </div>

              <!-- Search input -->
              <div class="row g-2 mb-4">
                <div class="col-md-7">
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input
                      v-model="searchQuery"
                      type="text"
                      class="form-control bg-light border-start-0"
                      placeholder="Search vacancies by title or department..."
                    >
                  </div>
                </div>
                <div class="col-md-5">
                  <select v-model="selectedDepartment" class="form-select bg-light">
                    <option value="">All Departments</option>
                    <option v-for="dept in departments" :key="dept" :value="dept">{{ dept }}</option>
                  </select>
                </div>
              </div>

              <!-- Vacancy Cards -->
              <div v-if="loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading vacancies...</span>
                </div>
                <p class="text-muted mt-2 small">Loading current open vacancies...</p>
              </div>

              <div v-else-if="filteredVacancies.length" class="d-flex flex-column gap-3">
                <div
                  v-for="vacancy in filteredVacancies"
                  :key="vacancy.id"
                  class="card border border-light-subtle rounded-3 hover-shadow transition-all"
                  style="transition: all 0.2s ease-in-out;"
                >
                  <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                      <span class="badge bg-success text-white px-2 py-1 small fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> {{ (vacancy.status || 'OPEN').toUpperCase() }}
                      </span>
                      <small class="text-muted" v-if="vacancy.closing_date || vacancy.closingDate">
                        <i class="bi bi-clock me-1"></i> Closing Date: <strong>{{ formatDate(vacancy.closing_date || vacancy.closingDate) }}</strong>
                      </small>
                    </div>

                    <h4 class="fw-bold text-dark mb-2">{{ vacancy.title }}</h4>
                    <p class="text-muted small mb-3">
                      <span class="me-3"><i class="bi bi-building me-1 text-primary"></i> <strong>Department:</strong> {{ vacancy.department }}</span>
                      <span class="me-3" v-if="vacancy.reference_no || vacancy.refNo"><i class="bi bi-hash me-1 text-primary"></i> <strong>Ref:</strong> {{ vacancy.reference_no || vacancy.refNo }}</span>
                      <span v-if="vacancy.remuneration || vacancy.salary"><i class="bi bi-cash me-1 text-primary"></i> <strong>Remuneration:</strong> {{ vacancy.remuneration || vacancy.salary }}</span>
                    </p>

                    <p class="text-secondary mb-3" style="font-size: 0.95rem; line-height: 1.6;">
                      {{ vacancy.description }}
                    </p>

                    <div v-if="vacancy.requirements" class="mb-3 p-3 bg-light rounded small text-secondary" style="white-space: pre-line;">
                      <strong class="text-dark d-block mb-1">Key Requirements:</strong>
                      {{ vacancy.requirements }}
                    </div>

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-3 border-top">
                      <small class="text-secondary"><i class="bi bi-geo-alt me-1 text-danger"></i> Location: {{ vacancy.location || 'Creighton Main Office' }}</small>
                      <div class="d-flex gap-2">
                        <a
                          v-if="vacancy.document_url"
                          :href="vacancy.document_url"
                          target="_blank"
                          rel="noopener noreferrer"
                          class="btn btn-sm btn-outline-danger fw-semibold px-3 py-2 d-inline-flex align-items-center gap-1"
                          title="Download official advert document"
                        >
                          <i class="bi bi-file-earmark-pdf-fill"></i>
                          <span>Download Advert</span>
                        </a>

                        <a
                          v-if="vacancy.application_url"
                          :href="vacancy.application_url"
                          target="_blank"
                          rel="noopener noreferrer"
                          class="btn btn-sm btn-primary fw-semibold px-3 py-2 d-inline-flex align-items-center gap-1"
                        >
                          <span>Apply Online</span>
                          <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                        <a
                          v-else
                          :href="'mailto:Mailbox@ndz.gov.za?subject=' + encodeURIComponent('Application: ' + vacancy.title + (vacancy.reference_no ? ' (' + vacancy.reference_no + ')' : ''))"
                          class="btn btn-sm btn-outline-primary fw-semibold px-3 py-2 d-inline-flex align-items-center gap-1"
                          title="Apply via email"
                        >
                          <i class="bi bi-envelope-fill"></i>
                          <span>Apply via Email</span>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Empty State -->
              <div v-else class="text-center py-5">
                <div class="text-muted mb-3" style="font-size: 3rem;"><i class="bi bi-briefcase"></i></div>
                <h5 class="fw-bold text-dark">No open vacancies matching your criteria</h5>
                <p class="text-secondary small mb-4">Please check back regularly or contact HR for upcoming career opportunities.</p>
                <a
                  href="mailto:Mailbox@ndz.gov.za?subject=Career%20Opportunity%20Inquiry"
                  class="btn btn-outline-success fw-bold px-4 py-2"
                >
                  Contact HR Recruitment <i class="bi bi-envelope-fill ms-1"></i>
                </a>
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
  name: 'OpenVacanciesView',
  data() {
    return {
      searchQuery: '',
      selectedDepartment: '',
      loading: false,
      departments: [
        'Office of the Municipal Manager',
        'Budget & Treasury',
        'Public Works & Basic Services',
        'Corporate Support Services',
        'Community and Social Services',
        'Development and Town Planning Services',
      ],
      vacancies: [],
    }
  },
  computed: {
    filteredVacancies() {
      return this.vacancies.filter((v) => {
        const title = (v.title || '').toLowerCase()
        const dept = (v.department || '').toLowerCase()
        const desc = (v.description || '').toLowerCase()
        const ref = (v.reference_no || v.refNo || '').toLowerCase()
        const q = this.searchQuery.toLowerCase()

        const matchesQuery = !this.searchQuery || title.includes(q) || dept.includes(q) || desc.includes(q) || ref.includes(q)
        const matchesDept = !this.selectedDepartment || v.department === this.selectedDepartment
        return matchesQuery && matchesDept
      })
    },
  },
  mounted() {
    this.fetchOpenVacancies()
  },
  methods: {
    async fetchOpenVacancies() {
      this.loading = true
      try {
        const { data } = await api.get('/api/vacancies?status=open')
        if (data?.data?.length) {
          this.vacancies = data.data
        } else {
          this.vacancies = []
        }
      } catch (err) {
        console.warn('Failed to load vacancies from API, using fallback data', err)
      } finally {
        this.loading = false
      }
    },
    formatDate(dateStr) {
      if (!dateStr) return 'Open until filled'
      if (dateStr.includes('T')) {
        const d = new Date(dateStr)
        return d.toLocaleDateString('en-ZA', { year: 'numeric', month: 'short', day: 'numeric' })
      }
      return dateStr
    },
  },
}
</script>

<style scoped>
.quick-links-sidebar {
  background: var(--primary, #004d40) !important;
}
.quick-links-sidebar .active {
  background: rgba(0, 0, 0, 0.15) !important;
}
.hover-shadow:hover {
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
}
</style>
