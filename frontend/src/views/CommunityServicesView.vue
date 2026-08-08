<template>
  <div class="page-wrapper bg-light">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Community Services</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Dr Nkosazana Dlamini-Zuma Local Municipality</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">Department</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Community Services</li>
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
                  <img :src="profileImageSrc" alt="Ms Kefuwe Gloria Mtolo" class="card-img-top w-100" style="object-fit: cover; height: 320px; object-position: top center;" @error="handleImageError" />
                </div>
                <div class="card-body p-4 bg-white">
                  <h4 class="card-title fw-bold text-dark mb-1">Ms Kefuwe Gloria Mtolo</h4>
                  <p class="text-primary fw-medium small mb-0 text-uppercase">Senior Manager: Community and Social Services</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Text Column -->
          <div class="col-lg-8">
            <div class="content-card p-4 p-md-5 bg-white shadow-sm rounded">
              <h3 class="fw-bold mb-4 text-dark" style="font-size: 1.75rem;">Senior Manager: Community and Social Services</h3>
              <p class="lead text-dark fw-normal mb-4" style="line-height: 1.8;">
                I am a Social Science graduate from the University of KwaZulu-Natal who is dedicated, results-driven, and a professional with extensive experience of more than 20 years in local government, focusing on community development and strategic planning.
              </p>

              <p class="mb-4" style="line-height: 1.8;">
                My expertise includes HIV/Aids counselling, social facilitation, municipal Integrated Development Planning (IDP), performance management, policy development, as well as public service administration. I hold a diploma in Project Management from Varsity College. To refine my skills in performance management, I studied Monitoring and Evaluation with Wits University.
              </p>

              <p class="mb-4" style="line-height: 1.8;">
                Throughout my career, I have worked with different stakeholders within the three spheres of government to improve service delivery by developing practical solutions and initiatives that are beneficial to communities. I studied Ethics for Local Government with the National School of Governance to ensure the provision of high-quality results in a reliable and ethical manner.
              </p>

              <p class="mb-4" style="line-height: 1.8;">
                For the purpose of translating municipal strategic objectives in the IDP into practical departmental policies and procedures, I studied Policy Formulation and Implementation to enhance my knowledge in identifying service delivery gaps and developing policies that address community needs while improving governance, accountability, and risk management.
              </p>

              <p class="fw-bold mb-4 text-dark" style="font-size: 1.25rem;">Professional focus areas include:</p>

              <ul class="list-unstyled focus-areas-list mb-5">
                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-people-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Community Development and Social Facilitation</h5>
                    <p class="text-secondary mb-0">Supporting programmes and initiatives that strengthen communities and improve local service delivery outcomes.</p>
                  </div>
                </li>

                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-diagram-3-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Integrated Development Planning and Strategic Alignment</h5>
                    <p class="text-secondary mb-0">Translating municipal objectives into practical plans, policies, and service delivery interventions.</p>
                  </div>
                </li>

                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-clipboard-data-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Performance Management and Monitoring</h5>
                    <p class="text-secondary mb-0">Using monitoring and evaluation practices to improve accountability, results, and continuous improvement.</p>
                  </div>
                </li>

                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-file-earmark-text-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Policy Development and Governance</h5>
                    <p class="text-secondary mb-0">Developing responsive policies that address community needs while strengthening governance and risk management.</p>
                  </div>
                </li>

                <li class="d-flex mb-0">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-cash-coin fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Public Finance Accountability</h5>
                    <p class="text-secondary mb-0">Applying financial management principles to ensure public funds support effective, economic, and sustainable service delivery.</p>
                  </div>
                </li>
              </ul>

              <p class="mb-4" style="line-height: 1.8;">
                To ensure effective accountability for public funds, and to ensure that every rand spent contributes to improving service delivery, I studied Financial Management with the Wits School of Governance and the National School of Governance.
              </p>

              <div class="alert mt-4 border-start border-4 border-primary bg-light p-4 shadow-sm" style="border-radius: 0 4px 4px 0;">
                <p class="mb-0 text-dark fw-medium" style="line-height: 1.7;">
                  My goal is to deliver professional services that support the municipality in achieving its desired results in an economic and sustainable manner.
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
  name: 'CommunityServicesView',
  data() {
    return {
      profileImageSrc: '/img/departments/Photo.jpeg',
    }
  },
  methods: {
    handleImageError(event) {
      event.target.src = 'https://ui-avatars.com/api/?name=K+G+Mtolo&background=00a651&color=fff&size=300';
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
