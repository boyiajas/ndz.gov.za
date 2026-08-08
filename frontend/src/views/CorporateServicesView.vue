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
                  <img :src="profileImageSrc" alt="Mr S.J. Sondezi" class="card-img-top w-100" style="object-fit: cover; height: 320px; object-position: top center;" @error="handleImageError" />
                </div>
                <div class="card-body p-4 bg-white">
                  <h4 class="card-title fw-bold text-dark mb-1">Mr S.J. Sondezi</h4>
                  <p class="text-primary fw-medium small mb-0 text-uppercase">Senior Manager: Corporate Support Services</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Text Column -->
          <div class="col-lg-8">
            <div class="content-card p-4 p-md-5 bg-white shadow-sm rounded">
              <h3 class="fw-bold mb-4 text-dark" style="font-size: 1.75rem;">Senior Manager: Corporate Support Services</h3>
              <p class="lead text-dark fw-normal mb-4" style="line-height: 1.8;">
                Mr S.J. Sondezi currently serves as the Senior Manager: Corporate Support Services at Dr Nkosazana Dlamini Zuma Local Municipality, bringing a wealth of expertise cultivated over 21 years of dedicated service in the field.
              </p>

              <p class="mb-4" style="line-height: 1.8;">
                His career trajectory reflects sustained professional growth, comprising 13 years of experience at Senior Management level and a further 5 years at Middle Management level, underpinned by a solid foundation built prior to his advancement into leadership roles.
              </p>

              <p class="mb-4" style="line-height: 1.8;">
                Mr Sondezi holds a Master's Degree in Public Administration (MPA), an academic achievement that significantly strengthens his ability to lead within the local government sphere. This qualification has equipped him with an advanced understanding of public sector governance, policy formulation and implementation, strategic planning, and the legislative and regulatory frameworks that govern municipal operations.
              </p>

              <p class="mb-4" style="line-height: 1.8;">
                In his current capacity, Mr Sondezi provides oversight and direction to four distinct operational units, each led by a Manager reporting directly to him:
              </p>

              <ul class="list-unstyled focus-areas-list mb-5">
                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-person-lines-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Human Resources Management</h5>
                    <p class="text-secondary mb-0">Provides leadership on workforce planning, employee development, organisational structure, and human capital administration.</p>
                  </div>
                </li>

                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-pc-display-horizontal fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Information and Communication Technology (ICT)</h5>
                    <p class="text-secondary mb-0">Guides the municipality's technology environment, systems support, information management, and digital enablement.</p>
                  </div>
                </li>

                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-building-gear fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Auxiliary Services</h5>
                    <p class="text-secondary mb-0">Oversees the support functions that sustain day-to-day municipal operations and administrative effectiveness.</p>
                  </div>
                </li>

                <li class="d-flex mb-0">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-people-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Labour Relations</h5>
                    <p class="text-secondary mb-0">Supports fair labour practices, employee relations, collective engagement, and compliance with labour governance requirements.</p>
                  </div>
                </li>
              </ul>

              <p class="mb-5" style="line-height: 1.8;">
                Mr Sondezi's passion for his work and steadfast commitment to excellence, reinforced by his academic grounding in public administration, have had a demonstrably positive impact on departmental efficiency, contributing meaningfully to the overall operational effectiveness of the Organisation.
              </p>

              <div class="mt-4">
                <h4 class="fw-bold mb-4 text-dark">Core Functions of the Role</h4>
                <ul class="list-unstyled focus-areas-list mb-0">
                  <li class="d-flex mb-4">
                    <div class="icon-box me-3 mt-1 text-primary">
                      <i class="bi bi-diagram-3-fill fs-4"></i>
                    </div>
                    <div>
                      <p class="text-secondary mb-0">Providing strategic and operational leadership across the full spectrum of Corporate Services functions, including Human Resources, Labour Relations, Information and Communication Technology, Records Management, Security, and other related functional areas, in order to support the Organisation in achieving its strategic objectives and service delivery mandate.</p>
                    </div>
                  </li>

                  <li class="d-flex mb-4">
                    <div class="icon-box me-3 mt-1 text-primary">
                      <i class="bi bi-people fs-4"></i>
                    </div>
                    <div>
                      <p class="text-secondary mb-0">Overseeing and managing key Council committees, including the ICT Steering Committee and the Local Labour Forum, ensuring these structures function effectively in support of good governance.</p>
                    </div>
                  </li>

                  <li class="d-flex mb-4">
                    <div class="icon-box me-3 mt-1 text-primary">
                      <i class="bi bi-cash-stack fs-4"></i>
                    </div>
                    <div>
                      <p class="text-secondary mb-0">Managing the Department's budget and expenditure, ensuring sound financial management and the effective execution of Corporate Services projects.</p>
                    </div>
                  </li>

                  <li class="d-flex mb-0">
                    <div class="icon-box me-3 mt-1 text-primary">
                      <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <div>
                      <p class="text-secondary mb-0">Overseeing Risk Management and Performance Management functions within the Department, thereby contributing to organisational accountability and continuous improvement.</p>
                    </div>
                  </li>
                </ul>
              </div>

              <div class="alert mt-4 border-start border-4 border-primary bg-light p-4 shadow-sm" style="border-radius: 0 4px 4px 0;">
                <p class="mb-0 text-dark fw-medium" style="line-height: 1.7;">
                  A defining milestone in Mr Sondezi's career was his appointment as Acting Municipal Manager, a distinction that affirmed his professional competence, unwavering work ethic, and genuine commitment to serving both the community and the institution. This appointment stands as a testament to the trust and confidence placed in his leadership capabilities by the Municipality.
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
  data() {
    return {
      profileImageSrc: '/img/departments/S.J.Sondezi.png',
    }
  },
  methods: {
    handleImageError(event) {
      event.target.src = 'https://ui-avatars.com/api/?name=S+J+Sondezi&background=00a651&color=fff&size=300';
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
