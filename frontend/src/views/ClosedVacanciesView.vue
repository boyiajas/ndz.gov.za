<template>
  <div class="page-wrapper bg-light">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Closed Vacancies</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.85;">Archive of Past Municipal Recruitment &amp; Employment Notices</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">Career Centre</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Closed Vacancies</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Main Content Section -->
    <section class="py-5">
      <div class="container pb-5">
        <div class="row g-5 align-items-start">
          <!-- Sidebar -->
          <div class="col-lg-3">
            <div class="quick-links-sidebar bg-primary text-white rounded overflow-hidden shadow-sm">
              <h4 class="p-4 mb-0 fw-bold border-bottom border-light border-opacity-25" style="font-size: 1.15rem;">Career Centre</h4>
              <ul class="list-unstyled mb-0">
                <li>
                  <router-link to="/open-vacancies" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10">
                    Open Vacancies <i class="bi bi-chevron-right float-end"></i>
                  </router-link>
                </li>
                <li>
                  <router-link to="/closed-vacancies" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10 active bg-black bg-opacity-10 fw-semibold">
                    Closed Vacancies <i class="bi bi-chevron-right float-end"></i>
                  </router-link>
                </li>
                <li>
                  <a
                    href="https://forms.cloud.microsoft/r/Rvv4zeUt9Y"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10"
                  >
                    Application Forms <i class="bi bi-box-arrow-up-right float-end"></i>
                  </a>
                </li>
                <li>
                  <router-link to="/contact" class="d-block p-3 text-white text-decoration-none">
                    HR / Inquiries <i class="bi bi-chevron-right float-end"></i>
                  </router-link>
                </li>
              </ul>
            </div>
          </div>

          <!-- Main List Column -->
          <div class="col-lg-9">
            <div class="content-card bg-white shadow-sm rounded p-4 p-md-5">
              <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div>
                  <h3 class="fw-bold text-dark mb-1">Archived Vacancies</h3>
                  <p class="text-muted small mb-0">Past employment vacancies whose application deadlines have closed</p>
                </div>
                <div>
                  <span class="badge bg-secondary px-3 py-2 rounded-pill fs-6">{{ filteredVacancies.length }} Archived</span>
                </div>
              </div>

              <!-- Search input -->
              <div class="mb-4">
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                  <input
                    v-model="searchQuery"
                    type="text"
                    class="form-control bg-light border-start-0"
                    placeholder="Search closed vacancies by title or ref number..."
                  >
                </div>
              </div>

              <!-- Table of closed vacancies -->
              <div v-if="loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading archived vacancies...</span>
                </div>
                <p class="text-muted mt-2 small">Loading archived vacancies...</p>
              </div>

              <div v-else-if="filteredVacancies.length === 0" class="text-center py-5 text-muted">
                <i class="bi bi-archive text-secondary" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;"></i>
                <p>No archived vacancies found matching your search.</p>
              </div>

              <div v-else class="table-responsive">
                <table class="table table-hover align-middle">
                  <thead class="table-light">
                    <tr>
                      <th scope="col" style="width: 15%;">Ref No</th>
                      <th scope="col" style="width: 40%;">Job Title</th>
                      <th scope="col" style="width: 25%;">Department</th>
                      <th scope="col" style="width: 10%;">Advert</th>
                      <th scope="col" style="width: 10%; text-align: right;">Closing Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="v in filteredVacancies" :key="v.id">
                      <td><span class="badge bg-light text-dark border">{{ v.reference_no || v.refNo || 'N/A' }}</span></td>
                      <td>
                        <strong>{{ v.title }}</strong>
                        <span class="badge bg-danger bg-opacity-10 text-danger ms-2" style="font-size: 0.75rem;">Closed</span>
                      </td>
                      <td class="text-secondary small">{{ v.department }}</td>
                      <td>
                        <a
                          v-if="v.document_url"
                          :href="v.document_url"
                          target="_blank"
                          rel="noopener noreferrer"
                          class="btn btn-sm btn-outline-secondary py-1 px-2"
                          title="Download past advert specification"
                          style="font-size: 0.75rem;"
                        >
                          <i class="bi bi-file-earmark-pdf-fill text-danger me-1"></i> PDF
                        </a>
                        <span v-else class="text-muted small">—</span>
                      </td>
                      <td class="text-muted small text-end">{{ formatDate(v.closing_date || v.closingDate) }}</td>
                    </tr>
                  </tbody>
                </table>
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
  name: 'ClosedVacanciesView',
  data() {
    return {
      searchQuery: '',
      loading: false,
      vacancies: [],
    }
  },
  computed: {
    filteredVacancies() {
      return this.vacancies.filter((v) => {
        const title = (v.title || '').toLowerCase()
        const ref = (v.reference_no || v.refNo || '').toLowerCase()
        const dept = (v.department || '').toLowerCase()
        const q = this.searchQuery.toLowerCase()

        return !this.searchQuery || title.includes(q) || ref.includes(q) || dept.includes(q)
      })
    },
  },
  mounted() {
    this.fetchClosedVacancies()
  },
  methods: {
    async fetchClosedVacancies() {
      this.loading = true
      try {
        const { data } = await api.get('/api/vacancies?status=closed')
        if (data?.data?.length) {
          this.vacancies = data.data
        } else {
          this.vacancies = []
        }
      } catch (err) {
        console.warn('Failed to fetch closed vacancies from API', err)
      } finally {
        this.loading = false
      }
    },
    formatDate(dateStr) {
      if (!dateStr) return 'Past Deadline'
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
</style>
