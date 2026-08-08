<template>
  <div class="page-wrapper bg-light">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Budget & Treasury</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Dr Nkosazana Dlamini-Zuma Local Municipality</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">Department</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Budget & Treasury</li>
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
                  <img :src="profileImageSrc" alt="P Mntungwa" class="card-img-top w-100" style="object-fit: cover; height: 320px; object-position: top center;" @error="handleImageError" />
                </div>
                <div class="card-body p-4 bg-white">
                  <h4 class="card-title fw-bold text-dark mb-1">Mr P Mntungwa</h4>
                  <p class="text-primary fw-medium small mb-0 text-uppercase">CFO</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Text Column -->
          <div class="col-lg-8">
            <div class="content-card p-4 p-md-5 bg-white shadow-sm rounded">
              <h3 class="fw-bold mb-4 text-dark" style="font-size: 1.75rem;">Chief Financial Officer</h3>
              <p class="lead text-dark fw-normal mb-4" style="line-height: 1.8;">
                A motivated and enthusiastic accounting graduate with vast knowledge and experience in local government management practices.
              </p>

              <p class="mb-4" style="line-height: 1.8;">
                He possesses proven leadership abilities and has acted as CFO on different occasions at Kwa Sani Municipality and at Dr Nkosazana Dlamini Zuma Municipality with excellence. Over the past years, he has served as a Senior Accountant at Kwa Sani Municipality and as Deputy CFO at NDZ since its establishment. Mr Mtungwa started his journey as a trainee accountant in the private sector and later joined local government in 2009. He has held different positions in local government at both middle and senior management levels.
              </p>

              <p class="fw-bold mb-4 text-dark" style="font-size: 1.25rem;">Academic and professional profile:</p>

              <ul class="list-unstyled focus-areas-list mb-5">
                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-mortarboard-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Accounting Qualification</h5>
                    <p class="text-secondary mb-0">Completed a BCom Accounting degree at the University of Zululand and a National Diploma at UNISA.</p>
                  </div>
                </li>

                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-patch-check-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Municipal Finance Training</h5>
                    <p class="text-secondary mb-0">Holds a Municipal Financial Management Certificate from KMS College, strengthening practical municipal finance capability.</p>
                  </div>
                </li>

                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-book-half fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Continuing Studies</h5>
                    <p class="text-secondary mb-0">Currently completing a Postgraduate Diploma in Public Finance Management at Stellenbosch University.</p>
                  </div>
                </li>

                <li class="d-flex mb-0">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-briefcase-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Leadership Experience</h5>
                    <p class="text-secondary mb-0">Brings experience across private sector accounting, local government finance, middle management, and senior management roles.</p>
                  </div>
                </li>
              </ul>

              <div class="alert mt-4 border-start border-4 border-primary bg-light p-4 shadow-sm" style="border-radius: 0 4px 4px 0;">
                <p class="mb-0 text-dark fw-medium" style="line-height: 1.7;">
                  With the new challenge as CFO, he has committed himself to making a valuable contribution to service delivery for NDZ residents and to ensuring that the municipality continues to be counted among the best performing municipalities.
                </p>
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
  name: 'BudgetTreasuryView',
  data() {
    return {
      profileImageSrc: '/img/departments/WhatsApp%20Image%202026-08-08%20at%207.40.55%20PM.jpeg',
    }
  },
  methods: {
    handleImageError(event) {
      event.target.src = 'https://ui-avatars.com/api/?name=P+Mntungwa&background=00a651&color=fff&size=300';
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
</style>
