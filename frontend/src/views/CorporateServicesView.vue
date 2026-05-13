<template>
  <div class="page-wrapper bg-light">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Corporate Services</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Dr Nkosazana Dlamini-Zuma Local Municipality</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">Department</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Corporate Services</li>
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
                  <img src="/img/departments/mr-j-sondezi.jpg" alt="Mr J Sondezi" class="card-img-top w-100" style="object-fit: cover; height: 320px; object-position: top center;" @error="handleImageError" />
                </div>
                <div class="card-body p-4 bg-white">
                  <h4 class="card-title fw-bold text-dark mb-1">Mr J Sondezi</h4>
                  <p class="text-primary fw-medium small mb-0 text-uppercase">Corporate and Support Services Manager</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Text Column -->
          <div class="col-lg-8">
            <div class="content-card p-4 p-md-5 bg-white shadow-sm rounded">
              <h3 class="fw-bold mb-4 text-dark" style="font-size: 1.75rem;">Corporate Services Department</h3>
              <p class="lead text-dark fw-normal mb-4" style="line-height: 1.8;">
                The Corporate Services Department is a key support division within the Dr. Nkosazana Dlamini-Zuma Municipality, responsible for driving the internal operations that enable efficient and responsive municipal governance.
              </p>
              
              <p class="mb-4" style="line-height: 1.8;">
                This department is led by Mr. SJ Sondezi, who oversees two critical sub-units:
              </p>

              <ul class="list-unstyled focus-areas-list mb-5">
                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-person-lines-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Human Resources (HR)</h5>
                    <p class="text-secondary mb-0">Manages recruitment, staff development, performance management, and ensures compliance with labour legislation and employment equity policies.</p>
                  </div>
                </li>
                
                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-pc-display-horizontal fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Information Technology (IT)</h5>
                    <p class="text-secondary mb-0">Supports the municipality’s digital infrastructure, data security, and the implementation of technology solutions that improve service delivery and internal efficiency.</p>
                  </div>
                </li>
              </ul>

              <div class="alert mt-4 border-start border-4 border-primary bg-light p-4 shadow-sm" style="border-radius: 0 4px 4px 0;">
                <p class="mb-0 text-dark fw-medium" style="line-height: 1.7;">
                  Corporate Services plays a vital role in ensuring that the municipality functions smoothly, providing the backbone for administrative, technical, and personnel-related processes.
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
                        <td><i class="bi bi-file-earmark-pdf text-danger me-2"></i> Corporate Services Organogram</td>
                        <td class="text-center"><span class="badge bg-secondary rounded-pill">2974</span></td>
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
  name: 'CorporateServicesView',
  methods: {
    handleImageError(event) {
      event.target.src = 'https://ui-avatars.com/api/?name=J+Sondezi&background=00a651&color=fff&size=300';
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
