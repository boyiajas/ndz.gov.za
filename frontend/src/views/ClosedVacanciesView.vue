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
              <div class="table-responsive">
                <table class="table table-hover align-middle">
                  <thead class="table-light">
                    <tr>
                      <th scope="col" style="width: 15%;">Ref No</th>
                      <th scope="col" style="width: 45%;">Job Title</th>
                      <th scope="col" style="width: 25%;">Department</th>
                      <th scope="col" style="width: 15%; text-align: right;">Closing Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="v in filteredVacancies" :key="v.id">
                      <td><span class="badge bg-light text-dark border">{{ v.refNo }}</span></td>
                      <td>
                        <strong>{{ v.title }}</strong>
                        <span class="badge bg-danger bg-opacity-10 text-danger ms-2" style="font-size: 0.75rem;">Closed</span>
                      </td>
                      <td class="text-secondary small">{{ v.department }}</td>
                      <td class="text-muted small text-end">{{ v.closingDate }}</td>
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
export default {
  name: 'ClosedVacanciesView',
  data() {
    return {
      searchQuery: '',
      vacancies: [
        {
          id: 1,
          title: 'Assistant Librarian',
          refNo: 'NDZ-COMM-08/2024',
          department: 'Community Services',
          closingDate: '13 October 2024',
        },
        {
          id: 2,
          title: 'Personal Assistant to the Deputy Mayor',
          refNo: 'NDZ-CORP-06/2024',
          department: 'Corporate Services',
          closingDate: '29 September 2024',
        },
        {
          id: 3,
          title: 'SCM Contract Management Officer',
          refNo: 'NDZ-BTO-05/2024',
          department: 'Budget & Treasury',
          closingDate: '29 September 2024',
        },
        {
          id: 4,
          title: 'Disaster Management Officer',
          refNo: 'NDZ-COMM-03/2024',
          department: 'Community Services',
          closingDate: '15 June 2024',
        },
        {
          id: 5,
          title: 'Town Planning Technician',
          refNo: 'NDZ-DTPS-02/2024',
          department: 'Development & Town Planning Services',
          closingDate: '30 April 2024',
        },
      ],
    }
  },
  computed: {
    filteredVacancies() {
      return this.vacancies.filter((v) => {
        return (
          !this.searchQuery ||
          v.title.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          v.refNo.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          v.department.toLowerCase().includes(this.searchQuery.toLowerCase())
        )
      })
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
