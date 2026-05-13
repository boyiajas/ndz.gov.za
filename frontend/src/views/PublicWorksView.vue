<template>
  <div class="page-wrapper bg-light">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Public Works & Basic Services</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Dr Nkosazana Dlamini-Zuma Local Municipality</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">Department</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Public Works & Basic Services</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Main Content Section -->
    <section class="department-section py-5">
      <div class="container pb-5">
        <div class="row g-5 align-items-start">
          
          <!-- Manager Sidebar Column -->
          <div class="col-lg-4">
            <div class="position-sticky" style="top: 2rem;">
              <!-- Manager Profile Card -->
              <div class="card border-0 shadow-sm text-center mb-4 manager-card overflow-hidden">
                <div class="card-img-wrapper bg-light">
                  <img src="/img/departments/mr-v-mngadi.jpg" alt="Mr V Mngadi" class="card-img-top w-100" style="object-fit: cover; height: 320px; object-position: top center;" @error="handleImageError" />
                </div>
                <div class="card-body p-4 bg-white">
                  <h4 class="card-title fw-bold text-dark mb-1">Mr V Mngadi</h4>
                  <p class="text-primary fw-medium small mb-0 text-uppercase">Public Works & Basic Services Manager</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Text Column -->
          <div class="col-lg-8">
            <div class="content-card p-4 p-md-5 bg-white shadow-sm rounded">
              <h3 class="fw-bold mb-4 text-dark" style="font-size: 1.75rem;">Public Works Department</h3>
              <p class="lead text-dark fw-normal mb-4" style="line-height: 1.8;">
                The Public Works Department is responsible for the planning, construction, and maintenance of municipal infrastructure and facilities across the Dr. Nkosazana Dlamini-Zuma Municipality.
              </p>
              
              <p class="fw-bold mb-4 text-dark" style="font-size: 1.25rem;">This department plays a crucial role in improving the quality of life for residents through:</p>

              <ul class="list-unstyled focus-areas-list mb-5">
                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-cone-striped fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Infrastructure Development</h5>
                    <p class="text-secondary mb-0">Planning and implementing capital projects including roads, municipal buildings, stormwater systems, and public amenities.</p>
                  </div>
                </li>
                
                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-tools fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Maintenance of Public Assets</h5>
                    <p class="text-secondary mb-0">Ensuring ongoing upkeep and repair of existing municipal infrastructure to maintain safety and service standards.</p>
                  </div>
                </li>

                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-map-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Project Management</h5>
                    <p class="text-secondary mb-0">Overseeing construction projects to ensure they are delivered on time, within budget, and to the required standards.</p>
                  </div>
                </li>

                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-people-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Job Creation and Local Empowerment</h5>
                    <p class="text-secondary mb-0">Promoting local labour and SMME involvement in municipal projects through Expanded Public Works Programmes (EPWP).</p>
                  </div>
                </li>

                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-droplet-half fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Service Delivery Support</h5>
                    <p class="text-secondary mb-0">Working with other departments to provide infrastructure that supports water, sanitation, waste, and other community services.</p>
                  </div>
                </li>
              </ul>

              <div class="alert mt-4 border-start border-4 border-primary bg-light p-4 shadow-sm" style="border-radius: 0 4px 4px 0;">
                <p class="mb-0 text-dark fw-medium" style="line-height: 1.7;">
                  The department is committed to sustainable infrastructure development that meets the growing needs of communities and contributes to long-term socio-economic development.
                </p>
              </div>

              <!-- Attachments Section -->
              <div class="mt-5 border-top pt-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-paperclip me-2 text-primary"></i>Print or Download File Attachments</h5>
                <div class="table-responsive">
                  <table class="table table-hover align-middle border">
                    <thead class="table-light">
                      <tr>
                        <th>#</th>
                        <th>File</th>
                        <th class="text-center">Downloads</th>
                        <th class="text-end">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>1</td>
                        <td><i class="bi bi-file-earmark-pdf text-danger me-2"></i> Public Works Organogram</td>
                        <td class="text-center"><span class="badge bg-secondary rounded-pill">1855</span></td>
                        <td class="text-end">
                          <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Download</a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
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
export default {
  name: 'PublicWorksView',
  methods: {
    handleImageError(event) {
      event.target.src = 'https://ui-avatars.com/api/?name=V+Mngadi&background=00a651&color=fff&size=300';
    }
  }
}
</script>

<style scoped>
.page-wrapper {
  background-color: #f8f9fa;
}

.text-primary {
  color: #00a651 !important; /* NDZ Primary Brand Color */
}

.border-primary {
  border-color: #00a651 !important;
}

.btn-outline-primary {
  color: #00a651;
  border-color: #00a651;
}

.btn-outline-primary:hover {
  background-color: #00a651;
  color: #fff;
}

.focus-areas-list .icon-box {
  width: 50px;
  height: 50px;
  background-color: rgba(0, 166, 81, 0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.manager-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.manager-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 1rem 3rem rgba(0,0,0,.15)!important;
}

.table th {
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.85rem;
  letter-spacing: 0.05em;
}
</style>
