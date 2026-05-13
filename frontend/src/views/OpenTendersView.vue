<template>
  <div class="page-wrapper bg-light">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Open Tenders</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Supply Chain Management</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">SCM</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Open Tenders</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Main Content Section -->
    <section class="py-5">
      <div class="container pb-5">
        <div class="row g-5 align-items-start">
          
          <!-- Quick Links Sidebar Column -->
          <div class="col-lg-3">
            <div class="quick-links-sidebar bg-primary text-white rounded overflow-hidden shadow-sm">
              <h4 class="p-4 mb-0 fw-bold border-bottom border-light border-opacity-25">Quick Links</h4>
              <ul class="list-unstyled mb-0">
                <li><router-link to="/open-tenders" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10">Open Tenders <i class="bi bi-chevron-right float-end"></i></router-link></li>
                <li><router-link to="/closed-tenders" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10">Closed Tenders <i class="bi bi-chevron-right float-end"></i></router-link></li>
                <li><router-link to="/open-quotes" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10">Open Quotes <i class="bi bi-chevron-right float-end"></i></router-link></li>
                <li><router-link to="/closed-quotes" class="d-block p-3 text-white text-decoration-none active border-bottom border-light border-opacity-10">Closed Quotes <i class="bi bi-chevron-right float-end"></i></router-link></li>
                <li><a href="#" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10">Award <i class="bi bi-chevron-right float-end"></i></a></li>
                <li><a href="#" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10">Erratum Notices <i class="bi bi-chevron-right float-end"></i></a></li>
                <li><a href="#" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10">SCM Documents <i class="bi bi-chevron-right float-end"></i></a></li>
                <li><a href="#" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10">Contracts <i class="bi bi-chevron-right float-end"></i></a></li>
                <li><a href="#" class="d-block p-3 text-white text-decoration-none">Database Forms <i class="bi bi-chevron-right float-end"></i></a></li>
              </ul>
            </div>
          </div>

          <!-- Main Table Column -->
          <div class="col-lg-9">
            <div class="content-card bg-white shadow-sm rounded p-4">
              
              <!-- Filters -->
              <div class="row g-2 mb-4">
                <div class="col-md-5">
                  <input type="text" class="form-control" placeholder="Search Keyword...">
                </div>
                <div class="col-md-3">
                  <select class="form-select">
                    <option>Publish Date</option>
                    <option>Closing Date</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <select class="form-select">
                    <option>Descending</option>
                    <option>Ascending</option>
                  </select>
                </div>
                <div class="col-md-2 d-grid">
                  <button class="btn btn-primary">Apply Filter</button>
                </div>
              </div>

              <!-- Table -->
              <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                  <thead class="table-light text-center" style="font-size: 0.85rem;">
                    <tr>
                      <th style="width: 40px;"></th>
                      <th>CATEGORY</th>
                      <th>TENDER DESCRIPTION</th>
                      <th>eSUBMISSION</th>
                      <th>ADVERTISED</th>
                      <th>CLOSING</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-for="tender in tenders" :key="tender.id">
                      <!-- Main Row -->
                      <tr>
                        <td class="text-center" @click="toggleRow(tender.id)">
                          <i class="bi fs-5 cursor-pointer" :class="expandedRows.includes(tender.id) ? 'bi-dash-circle text-danger' : 'bi-plus-circle text-primary'" style="cursor: pointer;"></i>
                        </td>
                        <td class="text-secondary small">{{ tender.category }}</td>
                        <td>
                          <a href="#" class="text-decoration-none text-dark fw-medium hover-primary" @click.prevent="toggleRow(tender.id)">
                            {{ tender.description }}
                          </a>
                        </td>
                        <td class="text-center">{{ tender.eSubmission }}</td>
                        <td class="text-center small" v-html="tender.advertised"></td>
                        <td class="text-center small" v-html="tender.closing"></td>
                      </tr>
                      
                      <!-- Expanded Details Row -->
                      <tr v-if="expandedRows.includes(tender.id)" class="bg-light">
                        <td colspan="6" class="p-0">
                          <div class="px-4 py-3 border-start border-4 border-primary m-3 bg-white shadow-sm rounded">
                            <div class="row text-secondary" style="font-size: 0.9rem; line-height: 1.8;">
                              <div class="col-12 mb-4">
                                <div><strong class="text-dark">Tender Number:</strong> {{ tender.details.tenderNumber }}</div>
                                <div><strong class="text-dark">Organ Of State:</strong> {{ tender.details.organOfState }}</div>
                                <div><strong class="text-dark">Province:</strong> {{ tender.details.province }}</div>
                                <div><strong class="text-dark">Date Published:</strong> {{ tender.details.datePublished }}</div>
                                <div><strong class="text-dark">Closing Date:</strong> {{ tender.details.closingDate }}</div>
                                <div><strong class="text-dark">Place where goods, works or services are required:</strong> {{ tender.details.placeRequired }}</div>
                                <div><strong class="text-dark">Special Conditions:</strong> {{ tender.details.specialConditions }}</div>
                                <div><strong class="text-dark">Technical Contact Person:</strong> {{ tender.details.techContactPerson }}</div>
                                <div><strong class="text-dark">Email:</strong> <a :href="'mailto:'+tender.details.techEmail">{{ tender.details.techEmail }}</a></div>
                                <div><strong class="text-dark">Telephone number:</strong> {{ tender.details.techPhone }}</div>
                                <div><strong class="text-dark">SCM Contact Person:</strong> {{ tender.details.scmContactPerson }}</div>
                                <div><strong class="text-dark">Email:</strong> <a :href="'mailto:'+tender.details.scmEmail">{{ tender.details.scmEmail }}</a></div>
                                <div><strong class="text-dark">Telephone number:</strong> {{ tender.details.scmPhone }}</div>
                                <div><strong class="text-dark">Briefing Session:</strong> {{ tender.details.briefingSession }}</div>
                              </div>
                              
                              <div class="col-12" v-if="tender.files && tender.files.length > 0">
                                <h6 class="fw-bold text-dark mb-3">Attached Files</h6>
                                
                                <div class="card border-0 bg-light mb-2">
                                  <div class="card-body p-2 px-3 d-flex justify-content-center align-items-center rounded">
                                    <span class="text-muted small">{{ tender.files.length }} files</span>
                                  </div>
                                </div>
                                
                                <div v-for="(file, idx) in tender.files" :key="idx" class="card border-light shadow-sm mb-2 hover-shadow">
                                  <div class="card-body p-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                      <div class="text-danger fs-3"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                                      <div>
                                        <div class="fw-bold text-dark small" style="max-width: 450px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ file.name }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ file.size }}</div>
                                      </div>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-primary px-4 rounded-pill">Download</a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <nav aria-label="Tenders pagination" class="mt-4">
                <ul class="pagination mb-0">
                  <li class="page-item active"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>
                </ul>
              </nav>

            </div>
          </div>

        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'OpenTendersView',
  data() {
    return {
      expandedRows: [],
      tenders: [
        {
          id: 1,
          category: 'Services: Professional',
          description: 'Appointment of a Service Provider to provide Internet and VOIP (Telephone Management System) service for the Municipality for a period of 36 months',
          eSubmission: 'No',
          advertised: 'March 16,<br>2026',
          closing: 'April 16,<br>2026<br>12:00 pm',
          details: {
            tenderNumber: 'UMN18/2025-26',
            organOfState: 'Dr Nkosazana Dlamini Zuma Local Municipality',
            province: 'KwaZulu-Natal',
            datePublished: 'March 16, 2026',
            closingDate: 'April 16, 2026 12:00 pm',
            placeRequired: 'Main Office Building',
            specialConditions: 'N/A',
            techContactPerson: 'Musa Gumede',
            techEmail: 'musa.gumede@ndz.gov.za',
            techPhone: '033 239 8348',
            scmContactPerson: 'Mbongeni Zuma',
            scmEmail: 'mbongeni.zuma@ndz.gov.za',
            scmPhone: '033 239 8313',
            briefingSession: 'Date: 24 March 2026, Venue: Main Hall, Time: 10H00, Site to be visited: N/A'
          },
          files: [
            { name: 'UMN 18-2025-26 - Advert for the Appointment of a service provider to provide telephone and internet.pdf', size: '148.88 KB' },
            { name: 'UMN 18-2025-26 Tender Document', size: '532.55 KB' }
          ]
        },
        {
          id: 2,
          category: 'Services: Professional',
          description: 'APPOINTMENT OF A PANEL OF TOWN PLANNERS FOR THE MUNICIPALITY FOR A PERIOD OF 36 MONTHS',
          eSubmission: 'No',
          advertised: 'January 5,<br>2026',
          closing: 'March 4,<br>2026<br>12:00 pm',
          details: {
            tenderNumber: 'UMN20/2025-26',
            organOfState: 'Dr Nkosazana Dlamini Zuma Local Municipality',
            province: 'KwaZulu-Natal',
            datePublished: 'January 5, 2026',
            closingDate: 'March 4, 2026 12:00 pm',
            placeRequired: 'Municipal Offices',
            specialConditions: 'N/A',
            techContactPerson: 'Sihle Ngcobo',
            techEmail: 'sihle.ngcobo@ndz.gov.za',
            techPhone: '033 239 8300',
            scmContactPerson: 'Zinhle Mkhize',
            scmEmail: 'zinhle.mkhize@ndz.gov.za',
            scmPhone: '033 239 8314',
            briefingSession: 'N/A'
          },
          files: [
            { name: 'Tender Document - Town Planners Panel.pdf', size: '2.1 MB' }
          ]
        },
        {
          id: 3,
          category: 'Services: Professional',
          description: 'Appointment of a Service Provider for Servicing of fire extinguishers, fire hoses and installation of new fire extinguishers...',
          eSubmission: 'No',
          advertised: 'January 5,<br>2026',
          closing: 'March 5,<br>2026<br>12:00 pm',
          details: {
            tenderNumber: 'UMN21/2025-26',
            organOfState: 'Dr Nkosazana Dlamini Zuma Local Municipality',
            province: 'KwaZulu-Natal',
            datePublished: 'January 5, 2026',
            closingDate: 'March 5, 2026 12:00 pm',
            placeRequired: 'Various Municipal Buildings',
            specialConditions: 'CIDB Grading 2SF or higher',
            techContactPerson: 'Bheki Cele',
            techEmail: 'bheki.cele@ndz.gov.za',
            techPhone: '033 239 8455',
            scmContactPerson: 'Philani Ndlovu',
            scmEmail: 'philani.ndlovu@ndz.gov.za',
            scmPhone: '033 239 8315',
            briefingSession: 'Compulsory Briefing - Date: 20 Jan 2026, Venue: Fire Station'
          },
          files: [
            { name: 'Fire Equipment Servicing Tender Document.pdf', size: '1.4 MB' },
            { name: 'Pricing Schedule Annexure A.xlsx', size: '45 KB' }
          ]
        }
      ]
    }
  },
  methods: {
    toggleRow(id) {
      if (this.expandedRows.includes(id)) {
        this.expandedRows = this.expandedRows.filter(rowId => rowId !== id);
      } else {
        this.expandedRows.push(id);
      }
    }
  }
}
</script>

<style scoped>
.page-wrapper {
  background-color: #f8f9fa;
}

.text-primary {
  color: var(--primary, #004d40) !important;
}

.bg-primary {
  background-color: var(--primary, #004d40) !important;
}

.btn-primary {
  background-color: var(--primary, #004d40);
  border-color: var(--primary, #004d40);
}

.btn-primary:hover {
  background-color: #00362c;
  border-color: #00362c;
}

.quick-links-sidebar ul li a {
  transition: all 0.3s ease;
}

.quick-links-sidebar ul li a:hover {
  background-color: rgba(255, 255, 255, 0.1);
  padding-left: 1.5rem !important;
}

.quick-links-sidebar ul li a.active {
  background-color: rgba(255, 255, 255, 0.15);
  font-weight: 700;
  border-left: 4px solid var(--accent, #ffc107);
}

.hover-primary:hover {
  color: var(--primary, #004d40) !important;
  text-decoration: underline !important;
}

.table th {
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #555;
}

.pagination .page-item.active .page-link {
  background-color: var(--primary, #004d40);
  border-color: var(--primary, #004d40);
}
</style>
