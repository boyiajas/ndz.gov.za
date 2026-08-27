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
        <div class="row align-items-start g-5">
          <div class="col-12">
            
            <!-- Floated Image Container -->
            <div class="float-lg-start me-lg-5 mb-4" style="max-width: 340px;">
              <div class="card border-0 shadow-sm text-center manager-card overflow-hidden">
                <div class="card-img-wrapper bg-light">
                  <img :src="profileImageSrc" alt="Mr Siyabonga Valentine Mngadi" class="card-img-top w-100" style="object-fit: cover; height: 320px; object-position: top center;" @error="handleImageError" />
                </div>
                <div class="card-body p-4 bg-white">
                  <h4 class="card-title fw-bold text-dark mb-1">Mr V Mngadi</h4>
                  <p class="text-primary fw-medium small mb-0 text-uppercase">Public Works & Basic Services Manager</p>
                </div>
              </div>
            </div>

            <!-- Text Content -->
            <div class="section-label" style="color: var(--accent, #ffb300); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin-bottom: 0.5rem;">Senior Management</div>
            <h2 class="section-title" style="font-weight: 800; color: var(--text-dark, #1f2937); margin-bottom: 1.5rem;">Public Works Department & Basic Service</h2>
            <div class="section-divider" style="width: 50px; height: 3px; background: var(--accent, #ffb300); margin-bottom: 1.5rem;"></div>
            
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;">
              <strong>Siyabonga Valentine Mngadi</strong> is an experienced municipal technical services professional with extensive experience in infrastructure development, project management, engineering, and local government administration. He has held senior management positions within local government and has developed strong expertise in the planning, implementation, monitoring, and management of municipal infrastructure and basic services. His experience includes leadership of multidisciplinary teams, stakeholder engagement, capital budget planning, project management, and the delivery of sustainable services to communities.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              Mr Mngadi holds a National Diploma in Civil Engineering from Mangosuthu University of Technology and a Bachelor of Technology in Civil Engineering from Durban University of Technology. His academic background has provided him with a strong foundation in civil engineering, infrastructure development, construction management, and technical services. He has also pursued further professional development and is registered as a Candidate Engineering Technologist with ECSA and is registered with IMESA.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              He has extensive work experience spanning the engineering, construction, higher education, and local government sectors. He began his career in construction and engineering roles, gaining practical experience in roads, stormwater, water and sewer reticulation, concrete works, housing construction, transportation engineering, site supervision, and quality control. He subsequently worked as a Project Manager at Mabophe Energy Solution, a Junior Lecturer at Mangosuthu University of Technology, and a Director of Technical Services at Ezinqoleni Municipality.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              From November 2014 to January 2019, he served as General Manager: Technical Services at Umdoni Municipality, where he was responsible for managing technical services, project implementation, capital budget planning, stakeholder engagement, and departmental performance. Since March 2019, he has served as Senior Manager: Public Works and Basic Services at Dr Nkosazana Dlamini Zuma Municipality, where he provides strategic and operational leadership in the delivery of public works and basic services. His responsibilities include project and capital programme management, infrastructure planning, budget implementation, community and stakeholder engagement, development of municipal sector plans, and ensuring compliance with applicable local government legislation and performance management requirements.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              Mr. Mngadi oversees and provides strategic direction to distinct operational units, each headed by a manager who reports directly to him:
            </p>
            <ul style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem; margin-top:0.5rem; padding-left: 1.2rem; margin-bottom: 2rem;">
              <li>Refuse removal</li>
              <li>Electrification</li>
              <li>Municipal environmental management services</li>
              <li>Municipal roads and storm water drainage</li>
              <li>Street lighting</li>
              <li>Construction of community facilities</li>
            </ul>

            <div class="clearfix"></div>

            <div style="background: #fff; padding: 2rem; border-radius: 8px; border-left: 4px solid var(--primary, #00a651); box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); margin-top: 2rem;">
              <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-dark, #1f2937); margin-bottom: 1rem;">Core Functions of the Role</h3>
              <ul style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem; margin-bottom: 0; padding-left: 1.2rem;">
                <li style="margin-bottom: 0.5rem;"><strong>Infrastructure Development:</strong> Planning and implementing capital projects including roads, municipal buildings, stormwater systems, and public amenities.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Maintenance of Public Assets:</strong> Ensuring ongoing upkeep and repair of existing municipal infrastructure to maintain safety and service standards.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Project Management:</strong> Overseeing construction projects to ensure they are delivered on time, within budget, and to the required standards.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Job Creation and Local Empowerment:</strong> Promoting local labour and SMME involvement in municipal projects through Expanded Public Works Programmes (EPWP).</li>
                <li><strong>Service Delivery Support:</strong> Working with other departments to provide infrastructure that supports water, sanitation, waste, and other community services.</li>
              </ul>
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
    </section>
  </div>
</template>

<script>
export default {
  name: 'PublicWorksView',
  data() {
    return {
      profileImageSrc: '/img/departments/WhatsApp%20Image%202026-08-08%20at%207.41.43%20PM.jpeg',
    }
  },
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
