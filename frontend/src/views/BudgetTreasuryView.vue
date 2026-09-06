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
        <div class="row align-items-start g-5">
          <div class="col-12">
            
            <!-- Floated Image Container -->
            <div class="float-lg-start me-lg-5 mb-2" style="max-width: 340px;">
              <div class="card border-0 shadow-sm text-center manager-card overflow-hidden">
                <div class="card-img-wrapper bg-light">
                  <img :src="profileImageSrc" alt="Mr P Mntungwa" class="card-img-top w-100" style="object-fit: cover; height: 320px; object-position: top center;" @error="handleImageError" />
                </div>
                <div class="card-body p-4 bg-white">
                  <h4 class="card-title fw-bold text-dark mb-1">Mr P Mntungwa</h4>
                  <p class="text-primary fw-medium small mb-0 text-uppercase">Chief Financial Officer</p>
                </div>
              </div>
            </div>

            <!-- Text Content -->
            <div class="section-label" style="color: var(--accent, #ffb300); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin-bottom: 0.5rem;">Senior Management</div>
            <h2 class="section-title" style="font-weight: 800; color: var(--text-dark, #1f2937); margin-bottom: 1.5rem;">Budget and Treasury Office</h2>
            <div class="section-divider" style="width: 50px; height: 3px; background: var(--accent, #ffb300); margin-bottom: 1.5rem;"></div>
            
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;">
              <strong>Mr. Mtungwa</strong> is a proven leader with extensive experience in municipal financial management. He has served as Acting Chief Financial Officer on several occasions at both Kwa Sani Municipality and Dr Nkosazana Dlamini Zuma Local Municipality, performing the role with distinction.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              Over the years, he has held several progressively responsible positions, including Senior Accountant at Kwa Sani Municipality and Deputy Chief Financial Officer at Dr Nkosazana Dlamini Zuma Local Municipality since its establishment. Mr. Mtungwa began his career as a trainee accountant in the private sector before joining local government in 2009. Since then, he has occupied various middle- and senior-management positions within the local government sector.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              Mr. Mtungwa has a strong academic and professional background in accounting, governance, public administration and municipal finance. He holds a Bachelor of Commerce in Accounting from the University of Zululand, a National Diploma from the University of South Africa (UNISA), and a Municipal Financial Management Certificate from KMS College. 
            </p>
            
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:0rem;">
              These qualifications have strengthened his practical knowledge and expertise in municipal financial management. He is currently completing a Postgraduate Diploma in Public Finance Management at Stellenbosch University.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              The Budget and Treasury Office is a critical department within Dr Nkosazana Dlamini Zuma Local Municipality. It is mandated to promote the sound, responsible and sustainable management of the municipality’s financial resources. Its primary responsibility is to oversee the effective administration of municipal finances in accordance with applicable legislation, financial regulations and good-governance principles.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              The importance of the Office is reinforced by the Municipal Finance Management Act, 56 of 2003 (MFMA), which requires municipalities to manage their financial resources transparently, efficiently and responsibly. In particular, section 62(1) of the MFMA requires the accounting officer to ensure that the municipality maintains an effective financial management system that complies with the Act and supports sound internal controls and effective financial governance.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              In his current role, Mr. Mtungwa oversees and provides strategic direction to five distinct operational units, each headed by a manager who reports directly to him:
            </p>
            <ul style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem; margin-top:0.5rem; padding-left: 1.2rem; margin-bottom: 2rem;">
              <li>Revenue Management</li>
              <li>Expenditure Management</li>
              <li>Supply Chain Management (SCM)</li>
              <li>Asset Management</li>
              <li>Fleet Management</li>
            </ul>

            <div class="clearfix"></div>

            <div style="background: #fff; padding: 2rem; border-radius: 8px; border-left: 4px solid var(--primary, #00a651); box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); margin-top: 2rem;">
              <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-dark, #1f2937); margin-bottom: 1rem;">Core Functions of the Role</h3>
              <ul style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem; margin-bottom: 0; padding-left: 1.2rem;">
                <li style="margin-bottom: 0.5rem;"><strong>Revenue Management:</strong> Responsible for billing and the collection of all municipal revenue including rates, service charges, and other income. Ensures accurate customer accounts and promotes financial viability by reducing debt levels. Implements credit control and debt collection strategies in line with municipal policies.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Expenditure Management:</strong> Manages the timely processing and payment of all municipal obligations, including employee salaries, supplier invoices, and grants. Ensures adherence to the budget and compliance with payment procedures. Supports effective cash flow and expenditure control.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Supply Chain Management (SCM):</strong> Oversees the procurement of goods and services in accordance with the MFMA and SCM Regulations. Ensures that procurement is fair, transparent, equitable, competitive, and cost-effective. Manages contracts and supports departments in procurement planning.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Asset Management:</strong> Maintains a complete and accurate asset register. Ensures that municipal assets are acquired, used, safeguarded, and disposed of responsibly. Supports planning for infrastructure maintenance and future capital investment.</li>
                <li><strong>Fleet Management:</strong> Manages the acquisition, allocation, maintenance, and utilization of municipal vehicles. Promotes cost-effective and efficient transport services to support service delivery. Monitors vehicle usage and ensures compliance with fleet policies.</li>
              </ul>
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
