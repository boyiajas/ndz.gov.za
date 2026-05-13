<template>
  <div class="page-wrapper bg-light">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Closed Quotes</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Supply Chain Management</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">SCM</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Closed Quotes</li>
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
                <li><a href="#" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10">SCM Documents <i class="bi bi-chevron-right float-end"></i></a></li>
              </ul>
            </div>
          </div>

          <!-- Main List Column -->
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

              <!-- List of Closed Quotes -->
              <div class="d-flex flex-column gap-3">
                
                <template v-for="(quote, index) in closedQuotes" :key="index">
                  <div class="card border border-light shadow-sm hover-shadow transition-all">
                    <div class="card-body p-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                      
                      <!-- Icon & Info -->
                      <div class="d-flex align-items-start gap-3">
                        <div class="text-danger pt-1" style="font-size: 2rem; line-height: 1;">
                          <i class="bi bi-file-earmark-pdf-fill"></i>
                        </div>
                        <div>
                          <h6 class="mb-1 text-primary fw-bold" style="line-height: 1.4;">{{ quote.title }}</h6>
                          <div class="text-muted small d-flex align-items-center gap-3">
                            <span><i class="bi bi-files me-1"></i> {{ quote.filesCount }} file(s)</span>
                            <span><i class="bi bi-hdd me-1"></i> {{ quote.size }}</span>
                          </div>
                        </div>
                      </div>

                      <!-- Action Button -->
                      <div class="text-md-end shrink-0">
                        <a href="#" class="btn btn-sm btn-primary px-4 py-2 rounded">Download</a>
                      </div>
                      
                    </div>
                  </div>
                </template>

              </div>

              <!-- Pagination -->
              <nav aria-label="Closed quotes pagination" class="mt-4 pt-4 border-top">
                <ul class="pagination mb-0 justify-content-center justify-content-md-start">
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
  name: 'ClosedQuotesView',
  data() {
    return {
      closedQuotes: [
        {
          title: 'Q-03/2026-SUPPLY AND DELIVERY OF CLEANING MATERIALS FOR MUNICIPAL OFFICES',
          filesCount: 1,
          size: '64.12 KB'
        },
        {
          title: 'Q-04/2026-PROVISION OF PRINTING SERVICES FOR MUNICIPAL NEWSLETTERS',
          filesCount: 1,
          size: '128.50 KB'
        },
        {
          title: 'Q-05/2026-APPOINTMENT OF A SERVICE PROVIDER TO REPAIR AIR CONDITIONERS AT THE UNDERBERG OFFICE',
          filesCount: 2,
          size: '340.21 KB'
        }
      ]
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

.hover-shadow:hover {
  box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
  transform: translateY(-2px);
}

.transition-all {
  transition: all 0.2s ease-in-out;
}

.shrink-0 {
  flex-shrink: 0;
}

.pagination .page-item.active .page-link {
  background-color: var(--primary, #004d40);
  border-color: var(--primary, #004d40);
}
</style>
